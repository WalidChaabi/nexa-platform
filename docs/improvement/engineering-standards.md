# Engineering Standards

Conventions for code, documentation, logging, testing, and reviews — applied to **V1 maintenance** and **V2 rebuild**.

---

## 1. Code standards

### PHP / Laravel

| Rule | Detail |
|------|--------|
| PHP version | V1: 7.4 (frozen). V2: 8.3+ |
| Style | PSR-12; enforce with Laravel Pint on V2 (Pint on V1 optional) |
| Strict types | `declare(strict_types=1);` on all new V2 files |
| Type hints | Parameters and return types on all new methods |
| Controllers | Thin — validate, authorize, delegate to service, return response |
| Validation | Form Request classes for every write endpoint (V2); V1: minimum `validate()` in controller |
| Authorization | Policies + `$this->authorize()` — never rely on middleware alone for resource access |
| Queries | Eloquent first; `DB::` only with bindings; **never** concatenate user input into raw SQL |
| N+1 | Eager-load relationships in services; use `with()` in controllers only for simple cases |
| Mass assignment | `$fillable` minimal; never include `id`, `is_admin`, role flags, or `points` |
| Secrets | `.env` only; `env()` only in config files, not in application code |
| Debug | No `dd()`, `dump()`, `var_dump()` in committed code — use `Log::debug()` behind `APP_DEBUG` |
| Dead code | Delete; don't comment out large blocks — git history exists |

### Naming

| Item | Convention |
|------|------------|
| Controllers | `InvoiceController` — singular resource |
| Services | `InvoiceService` — domain noun |
| Form requests | `StoreInvoiceRequest`, `UpdateCustomerRequest` |
| Policies | `InvoicePolicy` |
| Jobs | `SendCampaignSmsJob` — verb + noun |
| Events | `InvoiceCreated` — past tense |
| DB tables | snake_case plural (existing schema preserved) |
| Routes | kebab-case URLs; named routes `owner.invoices.show` |

### File organization (V2)

```
app/Domain/{Module}/       # Optional: Actions, DTOs, Query objects
app/Services/              # Application services (or under Domain)
app/Integrations/          # Third-party clients only
app/Http/Requests/{Module}/
app/Policies/
```

---

## 2. Git standards

See [Git, PR & Release Workflow](./git-pr-and-release-workflow.md).

**Summary:**
- Conventional Commits: `type(scope): imperative message`
- One concern per branch / PR
- No secrets, no `vendor/`, no `.env` in commits
- Squash merge to `main` / `v2/main` (team default)

---

## 3. Pull request standards

Every PR must include:

1. **Summary** — what and why (not a file list only)
2. **Test plan** — automated + manual steps
3. **Security impact** — explicit checkbox
4. **Screenshots** — for UI changes
5. **Migration notes** — if DB or env changed

**Reviewer checklist:**
- [ ] Authorization considered
- [ ] Input validated
- [ ] No secrets in diff
- [ ] Tests added or justified
- [ ] Docs/env example updated

---

## 4. Documentation standards

### What to document

| Change type | Update |
|-------------|--------|
| New env variable | `.env.example` + `docs/runbooks/deployment-*.md` |
| New integration | `docs/integrations/{name}.md` |
| Architecture choice | `docs/adr/NNN-title.md` |
| Breaking behavior | PR description + CHANGELOG |
| Security fix | `docs/runbooks/secret-rotation.md` if rotation needed |

### ADR format (`docs/adr/000-template.md`)

```markdown
# ADR-NNN: Title

**Status:** Proposed | Accepted | Deprecated
**Date:** YYYY-MM-DD

## Context
What is the issue?

## Decision
What we decided.

## Consequences
Positive, negative, and follow-up actions.
```

### Code comments

- Explain **why**, not what
- No commented-out code blocks
- PHPDoc on public service methods and non-obvious domain rules

### README hierarchy

```
readme.md                    # Project overview (replace default Laravel readme)
docs/improvement/README.md   # Improvement program index
docs/technical-audit-report.md
docs/runbooks/
docs/adr/
docs/integrations/
```

---

## 5. Logging standards

### Principles

1. **Never log secrets** — tokens, passwords, full card data, CMI hashes with keys
2. **Structured context** — array second argument, stable keys
3. **Right level** — `debug` dev only; `info` business events; `warning` recoverable; `error` failures
4. **PII minimization** — mask phones: `0612****78`

### Channel strategy (V2)

| Channel | Purpose |
|---------|---------|
| `stack` / `single` | Default application |
| `integrations` | SMS, CMI, Mailgun |
| `security` | Auth failures, IDOR attempts, throttle hits |
| `stderr` | Container-friendly JSON in production |

### Standard log events

```php
// Business
Log::info('invoice.created', ['invoice_id' => $id, 'store_id' => $storeId, 'total' => $total]);

// Integration
Log::channel('integrations')->info('cmi.callback.received', [
    'cmd' => $cmd,
    'status' => $status,
    'verified' => $verified,
]);

// Security
Log::channel('security')->warning('auth.idor_attempt', [
    'user_id' => auth()->id(),
    'resource' => 'invoice',
    'resource_id' => $id,
    'user_store_id' => auth()->user()->store_id,
]);

// Error
Log::error('sms.send.failed', [
    'store_id' => $storeId,
    'exception' => $e->getMessage(),
]);
```

### V1 immediate fix

Remove from `CampaignService::sendSms`:
```php
Log::info($fields_string); // LEAKS TOKEN — delete
```

### Production config

```env
LOG_CHANNEL=stack
LOG_LEVEL=info
LOG_STACK=single,integrations
```

---

## 6. Testing standards

### Pyramid

```
        /  E2E (few)  \
       / Feature (many) \
      /  Unit (services) \
```

### Requirements by track

| Track | Minimum |
|-------|---------|
| V1 security PR | 1 feature test per fix |
| V2 module PR | Feature tests for happy path + authorization denial |
| V2 integration | Unit tests with HTTP fake |
| V2 policy | Dedicated policy test class |

### Test naming

```php
it('denies owner from viewing another stores invoice', function () { ... });

test('admin can list all owners', function () { ... });
```

### Factories

- Use model factories for all test data
- Never rely on production DB or fixed IDs (V1 `Route::get('/rdv')` anti-pattern)

### External services

```php
Http::fake(['bulksms.ma/*' => Http::response(['status' => 'ok'])]);
```

---

## 7. Security standards

| Control | Implementation |
|---------|----------------|
| CSRF | All state-changing web routes |
| Auth | Session for web; Sanctum if API added |
| Authorization | Policy per model |
| Rate limit | Login, password reset, public forms |
| Uploads | MIME whitelist, size limit, random storage names |
| Output | `{{ }}` default; `{!! !!}` only with HTML purifier |
| Headers | `HTTPS` forced in production (existing `AppServiceProvider`) |
| Dependencies | `composer audit` in CI (V2) |

---

## 8. Database standards (V2)

- All new columns via migrations — never manual prod SQL without migration
- Foreign keys on relational columns
- Indexes on: FK columns, `store_id`, dates used in `WHERE`, `phone`
- Use `decimal` for money — document if legacy `float` retained
- Soft deletes only where business requires audit trail

---

## 9. API / integration standards

### SMS (BulkSMS)

- Config: `config/services.php` → `env('BULKSMS_TOKEN')`
- Interface: `SmsGateway`
- Always queue sends in V2
- Retry: 3 attempts, exponential backoff
- Log: `sms.sent`, `sms.failed` — no token

### CMI Payment

- Verify hash on **every** callback
- Idempotent: same `cmd` processed once
- Store raw callback payload in `payment_callbacks` table (V2) for audit — **redact sensitive fields**

---

## 10. Review culture

- **24h max** first review on PRs &lt; 400 lines
- Author responds to all comments — resolve or explain
- `nit:` prefix for non-blocking style comments
- Blocking: security, missing tests, no authorization
- Post-merge: author monitors production for 24h on critical paths

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-08 | Initial engineering standards |
