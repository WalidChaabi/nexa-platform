# V1 — Security & Maintenance Plan

**Track:** Production maintenance on Laravel 5.7  
**Branch base:** `main`  
**Duration:** 2–4 weeks active work, then **feature freeze**  
**Goal:** Keep Sherin.ma safe and functional while V2 is built — **no UI refactors, no framework upgrade on V1**

---

## Principles

1. **Smallest diff that closes risk** — no drive-by refactors.
2. **One vulnerability class per PR** when possible.
3. **Rotate secrets** in ops the same day a credential is removed from code.
4. **Test what you touch** — even on V1, add a feature test for every security fix.
5. **Document env changes** in `.env.example` and PR description.

---

## Phase 0 — Security triage (Week 1–2) — CRITICAL

> **Gate:** Do not defer. Production may be exploitable today.

### 0.1 Remove malicious / foreign code from repository

**Issue:** `gb/` directory contains phishing kit (Microsoft-login mimic). Not part of Sherin.

| Task | Detail |
|------|--------|
| Delete | Entire `gb/` directory |
| Audit | Search repo for other foreign PHP: `test/index.php`, `app/Http/Controllers/test.php` |
| Ops | Remove from production server; scan access logs |
| Rotate | Any reCAPTCHA / third-party keys used in `gb/` |

**PR:** `security(repo): remove gb phishing kit and unrelated test entrypoints`

**Commits:**
```
security(repo): remove gb phishing kit directory
chore(repo): remove dead test controllers and entrypoints
docs(security): document incident response and key rotation
```

**Acceptance criteria:**
- [ ] `gb/` absent from repo and production
- [ ] No suspicious standalone PHP outside `public/index.php` chain
- [ ] Rotation checklist completed (see PR template)

---

### 0.2 Externalize hardcoded secrets

**Files to change:**

| File | Secret | Env variable (proposed) |
|------|--------|-------------------------|
| `app/Services/CampaignService.php` | BulkSMS token | `BULKSMS_TOKEN` |
| `app/Services/SmsService.php` | BulkSMS token (if duplicated) | `BULKSMS_TOKEN` |
| `app/Services/SmsCrossSellingService.php` | BulkSMS token | `BULKSMS_TOKEN` |
| `app/Services/BirthdayService.php` | BulkSMS token | `BULKSMS_TOKEN` |
| `config/app.php` | CMI store key defaults | `CMI_STORE_KEY`, `CMI_CLIENT_ID` |
| `config/services.php` | Mailgun fallback secret | Remove fallback; require env |

**PR:** `security(config): externalize SMS and payment credentials to environment`

**Implementation pattern:**
```php
// config/services.php
'bulksms' => [
    'token' => env('BULKSMS_TOKEN'),
    'endpoint' => env('BULKSMS_ENDPOINT', 'https://bulksms.ma/developer/sms/send'),
],
```

```php
// Service — never log full token
Log::info('sms.send', [
    'tel' => $maskedPhone,
    'shortcode' => $emetteur,
    'store_id' => $storeId,
]);
```

**Ops (same day as merge):**
1. Generate new BulkSMS token; revoke old token in provider dashboard.
2. Rotate CMI credentials if ever committed or logged.
3. Update production `.env`; run `php artisan config:cache`.

**Acceptance criteria:**
- [ ] `git grep -i 'token=' app/ config/` returns no live secrets
- [ ] App fails fast with clear error if `BULKSMS_TOKEN` missing in production
- [ ] `.env.example` documents all new variables

---

### 0.3 Close unauthenticated attack surface

**Routes to remove or protect** (`routes/web.php`):

| Route | Lines | Action |
|-------|-------|--------|
| `GET /clear` | 34–40 | **Delete** or restrict to `APP_ENV=local` + IP allowlist |
| `GET /cross-selling` | 413 | **Delete** or move behind `auth` + `admin` |
| `GET /rendez-vous` | 414 | Same |
| `GET /unique` | 415 | Same |
| `GET /bienvenu` | 416 | Same |
| `GET /rdv` | 417–420 | **Delete** (hardcoded IDs, triggers SMS) |
| `GET /rdv/update` | 421–424 | **Delete** |

**PR:** `security(routes): remove public cache clear and debug campaign endpoints`

**Acceptance criteria:**
- [ ] Unauthenticated request to former endpoints returns 404 or 403
- [ ] `php artisan route:list` shows no debug SMS triggers in production config
- [ ] Feature test asserts `/clear` is not publicly accessible

---

### 0.4 Fix SQL injection

**File:** `app/Http/Controllers/Web/CampaignDetailController.php` (~lines 42–50)

**Problem:** `$request->store_id` concatenated into `DB::raw()`.

**Fix:** Validate `store_id` as integer; use bindings or Eloquent subqueries.

```php
$storeId = (int) $request->validate(['store_id' => 'required|integer|exists:stores,id'])['store_id'];

// Prefer Eloquent over raw concatenation
$maxPoints = Customer::where('store_id', $storeId)->max('points');
```

**PR:** `security(campaign): parameterize store_id queries in CampaignDetailController`

**Acceptance criteria:**
- [ ] No user input in string-concatenated `DB::raw()`
- [ ] Test sends malicious `store_id` payload; no SQL error / no data leak

---

### 0.5 Mass assignment hardening

**Models to fix:**

| Model | Remove from `$fillable` |
|-------|-------------------------|
| `app/User.php` + `app/Models/User.php` | `is_admin`, `token` |
| `app/Models/Customer.php` | `id`, `points` (assign via dedicated methods) |
| `app/Models/Campaign.php` | `id` |
| `app/Models/Code.php` | `id` |
| `app/Models/Filter.php` | `id` |
| `app/Models/Message.php` | `id` |
| `app/Models/ProductQuantite.php` | `id` |
| `app/Models/ProductHestorique.php` | `id` |

**Controllers audit:** Replace `$request->all()` with `$request->only([...])` or validated subset in:

- `CheckoutController.php` (10 usages)
- `CampaignDetailController.php`
- `InvoiceController.php`
- `ProductController.php`
- Others from grep audit

**PR split:**
1. `security(models): restrict mass-assignable attributes on User and Customer`
2. `security(controllers): replace request->all with validated fields on write endpoints`

**Acceptance criteria:**
- [ ] Cannot elevate to admin via customer/owner update forms
- [ ] Cannot set arbitrary `points` via mass assignment

---

### 0.6 IDOR — resource ownership (minimum viable)

**Priority resources:** Invoice, Customer, Store, Rdv, Product

**Pattern (apply in controllers until V2 Policies exist):**

```php
$invoice = Invoice::where('id', $id)
    ->where('store_id', auth()->user()->store_id)
    ->firstOrFail();
```

**Files (first wave):**

| Controller | Methods |
|------------|---------|
| `InvoiceController` | `show`, `print`, `printInvoice`, `cancel`, `edit`, `update` |
| `CustomerController` | `edit`, `update`, `show` |
| `StoreController` | `edit`, `update`, `destroy` |
| `RdvController` | `delete`, `deleteRdv`, `update` |
| `ProductController` | `edit`, `update`, `destroy` |

**PR:** `security(invoice): enforce store ownership on read and write actions` (split per resource)

**Acceptance criteria:**
- [ ] User from store A cannot access store B invoice by ID (404/403)
- [ ] Feature tests for cross-store access denial

---

### 0.7 Replace GET-based destructive routes

**Current GET deletes** (`routes/web.php`):

```
owners/{id}/delete
stores/{id}/delete
cross-products/{id}/delete
employees/{id}/delete
rdv/delete-rdv/{id}
```

**PR:** `security(routes): use DELETE/POST with CSRF for destructive actions`

**Steps:**
1. Add `Route::delete(...)` alongside deprecated GET (deprecation period) OR break immediately if no external links.
2. Update Blade forms to `@method('DELETE')`.
3. Remove GET routes after UI updated.

**Acceptance criteria:**
- [ ] Destructive actions require POST/DELETE + CSRF token
- [ ] No state change via GET link

---

### 0.8 XSS — quick mitigations

| Location | Fix |
|----------|-----|
| `resources/views/owner/products/hestorique.blade.php` | Change `{!! !!}` to `{{ }}` or sanitize HTML server-side |
| Calendar JS `innerHTML` | Escape customer names or use `textContent` |
| All `{!! !!}` in views | Audit: `grep -r '{!!' resources/views` |

**PR:** `security(views): escape stored product history notifications`

---

### 0.9 File upload validation

**Files:** `StoreController`, `ProductController`

**Add:**
- `mimes:jpeg,png,webp` (or actual allowed types)
- `max:2048` (KB)
- Store with `Storage::putFile()` + hashed name — never `getClientOriginalName()` in path

**PR:** `security(upload): validate and sanitize store logo and product image uploads`

---

### 0.10 Authentication hardening (V1 scope)

| Task | Detail |
|------|--------|
| Rate limiting | `Route::post('/login')->middleware('throttle:5,1')` |
| POS token | Hash tokens at rest; compare with `Hash::check()` — document migration |
| Payment admin bypass | Gate `CampaignController@resetCampaigns` and `payAll` behind extra confirmation + audit log |

**PR:** `security(auth): add login throttle and audit admin payment overrides`

---

## Phase 1 — Critical bug fixes (Week 3–4)

> Only bugs that **break production workflows**. No cosmetic work.

### 1.1 Remove active `dd()` calls

| File | Line | Impact |
|------|------|--------|
| `RepportController.php` | 232 | Campaign reports broken |
| `InvoiceController.php` | 355 | Invoice show broken |
| `CrossSellingController.php` | 45 | Cross-selling debug halt |

**PR:** `fix(debug): remove dd() from production controller paths`

---

### 1.2 Fix admin customer list

**File:** `app/Http/Controllers/Web/CustomerController.php` (~64–66)

**Bug:** `$clients` and `$stores` undefined for `is_admin == 1`.

**Fix:** Load collections before switch or split methods per role.

**PR:** `fix(customer): load clients and stores for admin index view`

**Test:** `tests/Feature/AdminCustomerListTest.php` — admin gets 200 on customer index.

---

### 1.3 Payment callback session handling

**File:** `app/Http/Controllers/Web/CheckoutController.php`

**Bug:** `success()` may call `auth()->user()` after CMI redirect when session expired.

**Fix:** Resolve owner/store from payment `cmd` / order reference, not session alone.

**PR:** `fix(payment): resolve store from CMI callback payload when session missing`

---

### 1.4 Enable scheduler (minimal)

**File:** `app/Console/Kernel.php`

Uncomment and verify **one** campaign command in staging first; document cron on server.

**PR:** `fix(scheduler): enable birthday campaign schedule with Africa/Casablanca timezone`

---

## Phase 2 — V1 maintenance mode (ongoing during V2)

After Phase 0 + 1:

| Allowed | Not allowed |
|---------|-------------|
| Security hotfixes | New features |
| Data fixes with migration | Blade theme work |
| Dependency patches **only** if CVE critical | Laravel version upgrade |
| Cherry-pick to V2 if applicable | Large refactors |

**Monthly:** Re-run secret scan (`gitleaks`, `git grep`), review `storage/logs` for token leakage.

---

## V1 testing baseline

Add before closing Phase 0:

| Test file | Covers |
|-----------|--------|
| `tests/Feature/Security/PublicRoutesTest.php` | `/clear`, debug routes blocked |
| `tests/Feature/Security/InvoiceIdorTest.php` | Cross-store invoice denied |
| `tests/Feature/Auth/LoginThrottleTest.php` | Throttle after N attempts |
| `tests/Feature/AdminCustomerListTest.php` | Admin customer index |
| `tests/Feature/CampaignDetailSqlInjectionTest.php` | Malicious store_id |

**PR:** `test(security): add feature tests for phase 0 remediations`

**CI:** GitHub Actions — `composer install`, `php artisan test` on PHP 7.4.

---

## V1 documentation deliverables

| Document | Location | PR |
|----------|----------|-----|
| Env variable reference | `.env.example` | With secrets PR |
| Incident / rotation runbook | `docs/runbooks/secret-rotation.md` | Phase 0 |
| Deployment checklist | `docs/runbooks/deployment-v1.md` | Phase 0 |
| Known limitations | `docs/improvement/v1-known-limitations.md` | Phase 1 |

---

## V1 PR execution order

```
Week 1:
  V1-PR-01  Remove phishing kit
  V1-PR-03  Close public routes
  V1-PR-04  Fix SQLi
  V1-PR-09  GitHub templates + CI skeleton

Week 1–2 (parallel after PR-02 ops ready):
  V1-PR-02  Externalize secrets (+ rotation)
  V1-PR-07  Stop logging secrets
  V1-PR-05  Mass assignment
  V1-PR-08  IDOR invoices (then customers, stores)

Week 2:
  V1-PR-06  GET → DELETE routes
  V1-PR-10  Remove dd()
  V1-PR-11  Security feature tests

Week 3–4:
  V1-PR-12  Admin customer list fix
  V1-PR-13  Payment callback fix
  V1-PR-14  Upload validation
  V1-PR-15  XSS mitigations
```

---

## Definition of done — V1 Phase 0

- [ ] All Phase 0 PRs merged to `main`
- [ ] Production secrets rotated
- [ ] CI green on `main`
- [ ] No open **critical** or **high** items from [technical audit](../technical-audit-report.md)
- [ ] Feature freeze announced
- [ ] V2 bootstrap started

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-08 | Initial V1 plan |
