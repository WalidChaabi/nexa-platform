# V2 — Platform Rebuild Plan

**Track:** Next-generation Sherin platform  
**Branch base:** `v2/main` (new Laravel 11 app — monorepo `apps/v2/` or separate repo)  
**Duration:** 4–6 months to production cutover  
**Stack target:** Laravel 11, PHP 8.3, Pest, Pint, PHPStan, Livewire 3 *or* Inertia + Vue 3

---

## Strategic decisions (lock before Phase 0)

| Decision | Recommendation | Alternative |
|----------|----------------|-------------|
| Repo layout | Monorepo: `/` = V1, `apps/sherin-v2/` = V2 | Separate `sherin-v2` repo |
| Database | **Shared DB** initially (same MySQL schema, evolved via V2 migrations) | Read replica + dual-write (overkill) |
| UI | **Livewire 3 + Flux/shadcn-style** — fastest parity for Blade team | Inertia + Vue if SPA required |
| API | Web-first; API routes for future mobile in Phase 3 | API-only + separate frontend |
| Auth | Laravel Breeze/Fortify + roles (`admin`, `owner`, `employer`) | Sanctum for SPA |
| Multi-tenancy | `store_id` scoping + Policies (not package-based) | `stancl/tenancy` if true isolation needed later |

Document choices in `docs/adr/` (Architecture Decision Records).

---

## V2 directory structure (target)

```
apps/sherin-v2/                    # or repo root for greenfield
├── app/
│   ├── Domain/
│   │   ├── Invoice/
│   │   ├── Campaign/
│   │   ├── Customer/
│   │   └── Payment/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/              # Form Request per write action
│   ├── Policies/
│   ├── Services/                  # Ported + refactored from V1
│   ├── Integrations/
│   │   ├── BulkSms/
│   │   │   ├── BulkSmsClient.php
│   │   │   └── BulkSmsGateway.php
│   │   └── Cmi/
│   │       ├── CmiClient.php
│   │       └── CmiPaymentGateway.php
│   ├── Jobs/                      # SMS, email, campaign batches
│   ├── Events/
│   ├── Listeners/
│   └── Models/
├── database/
│   ├── migrations/                # Ported + normalized
│   └── factories/
├── resources/
│   └── views/ or js/              # Livewire or Inertia
├── routes/
│   ├── web.php
│   └── console.php
├── tests/
│   ├── Feature/
│   └── Unit/
└── docs/
```

---

## Phase 0 — Bootstrap (Week 2–4)

### V2-PR-01: Initialize Laravel 11 application

**Branch:** `v2/chore/bootstrap-laravel11`

**Tasks:**
- `composer create-project laravel/laravel apps/sherin-v2` (or dedicated repo)
- PHP 8.3, Laravel 11
- Configure `.env` for shared dev database
- Add README with local setup

**Commits:**
```
chore(v2): scaffold Laravel 11 application
chore(v2): configure database connection for shared schema
docs(v2): add local development setup guide
```

---

### V2-PR-02: Tooling and CI

**Branch:** `v2/chore/ci-quality-gates`

**Packages:**
- `pestphp/pest`
- `laravel/pint`
- `phpstan/phpstan` + `larastan/larastan` (level 5 → 8 over time)
- Optional: `laravel/pail` for local logs

**GitHub Actions** (`.github/workflows/v2-ci.yml`):

```yaml
# Triggers on PRs touching apps/sherin-v2/**
- composer install
- cp .env.example .env && php artisan key:generate
- ./vendor/bin/pint --test
- ./vendor/bin/phpstan analyse
- php artisan test
```

**Commits:**
```
chore(ci): add GitHub Actions workflow for V2
chore(v2): configure Pest and example feature test
chore(v2): add Pint and PHPStan with larastan
```

---

### V2-PR-03: Architecture Decision Records

**Branch:** `v2/docs/adr`

Create `docs/adr/`:

| ADR | Title |
|-----|-------|
| 001 | V2 rebuild vs in-place upgrade |
| 002 | UI stack selection (Livewire vs Inertia) |
| 003 | Store scoping and authorization model |
| 004 | Integration boundary (SMS, CMI gateways) |
| 005 | Queue and scheduler strategy |

**Template:** Context → Decision → Consequences.

---

### V2-PR-04: Port and normalize database schema

**Branch:** `v2/db/port-migrations`

**Source:** V1 `database/migrations/` (33 files)

**Normalization tasks:**
- Consolidate duplicate timestamps (`2023_03_05_000003_*` collision pattern)
- Add **foreign keys** with `constrained()` and `cascadeOnDelete()` where safe
- Add **indexes:**

| Table | Columns |
|-------|---------|
| `invoices` | `store_id`, `customer_id`, `invoice_date` |
| `customers` | `store_id`, `phone` |
| `invoice_products` | `invoice_id`, `product_id` |
| `campaign_details` | `store_id`, `campaign_id` |
| `rdvs` | `store_id`, `customer_id`, `start` |
| `messages_customers` | `store_id`, `customer_id` |

- Resolve duplicate `User` model (`app/User.php` vs `app/Models/User.php`) → single `App\Models\User`

**Commits:**
```
db(v2): port base schema migrations from V1
db(v2): add foreign keys and indexes for performance
db(v2): fix migration timestamp ordering collisions
```

**Acceptance criteria:**
- [ ] `php artisan migrate:fresh` succeeds on empty DB
- [ ] Schema matches V1 production (verify with `schema:dump` compare)

---

## Phase 1 — Foundation (Month 2)

### V2-PR-05: Authentication and roles

**Branch:** `v2/feat/auth-roles`

**Port from V1:**
- `app/Http/Middleware/IsAdmin.php`, `IsOwner.php`, `IsEmployer.php` → Laravel 11 middleware aliases
- `app/Http/Controllers/Auth/AuthController.php` logic

**Implement:**
- Fortify or Breeze with custom `LoginResponse` redirect by role
- Enum: `App\Enums\UserRole` (`Admin`, `Owner`, `Employer`)
- Middleware: `EnsureUserIsActive` (port `active` middleware)
- `LanguageSwitcher` → Laravel localization

**Tests:**
- Admin cannot access owner routes
- Inactive user blocked
- Login throttle

---

### V2-PR-06: Policy layer scaffold

**Branch:** `v2/feat/policies-scaffold`

**Policies to create:**

| Policy | Rules |
|--------|-------|
| `StorePolicy` | Owner owns store; admin all |
| `InvoicePolicy` | `view/update` if `invoice.store_id` matches user store |
| `CustomerPolicy` | Same store scoping |
| `ProductPolicy` | Same |
| `CampaignPolicy` | Same |
| `RdvPolicy` | Same |

Register in `AuthServiceProvider`. Use `$this->authorize()` in every controller action.

**Tests:** One denial test per policy.

---

### V2-PR-07: Integration gateways

**Branch:** `v2/feat/integration-gateways`

**Port from V1 with interfaces:**

```php
interface SmsGateway {
    public function send(string $phone, string $message, string $senderId): SmsResult;
}

interface PaymentGateway {
    public function initiate(PaymentRequest $request): PaymentRedirect;
    public function verifyCallback(array $payload): PaymentVerification;
}
```

**Implement:**
- `BulkSmsGateway` — from `CampaignService::sendSms`
- `CmiPaymentGateway` — from `CheckoutController::success`, `callBack`

**Never:** Log tokens, hardcode credentials, call cURL from controllers.

**Tests:** Mock HTTP; assert hash verification logic for CMI.

---

### V2-PR-08: Structured logging

**Branch:** `v2/chore/structured-logging`

**Configure** `config/logging.php`:
- Channel `integrations` for SMS/payment
- JSON formatter in production

**Standard context keys:**
```php
Log::channel('integrations')->info('sms.sent', [
    'store_id' => $storeId,
    'campaign_id' => $campaignId,
    'phone_masked' => substr($phone, 0, 4) . '****',
    'duration_ms' => $ms,
]);
```

See [Engineering Standards](./engineering-standards.md).

---

### V2-PR-09: Queue infrastructure

**Branch:** `v2/feat/queue-jobs`

**Jobs to create (port from sync listeners):**

| Job | Source V1 |
|-----|-----------|
| `SendCampaignSmsJob` | `CampaignService::sendSms` |
| `SendPaymentConfirmationEmailJob` | `PaidEmailListener` |
| `SendPaymentConfirmationSmsJob` | `PaiedSmsListener` |
| `ActivateAccountJob` | `ActivateAccountListener` |

- Implement `ShouldQueue` on listeners
- `QUEUE_CONNECTION=redis` in production
- Failed job table + monitoring

**Scheduler** (port from `app/Console/Kernel.php` + Commands):
- `birthday:campaign`
- `cross-selling:campaign`
- `rendez:vous`

---

## Phase 2 — Business modules (Month 2–4)

Port in **business value order**. Each module = stacked PRs (models → policy → service → UI → tests).

### Module map: V1 → V2

| Module | V1 Controllers | V1 Services | V2 PR series |
|--------|----------------|-------------|--------------|
| **Stores** | `StoreController`, `OwnerController` | `StoreService` | `v2/feat/stores-*` |
| **Customers** | `CustomerController` | `CustomerService` | `v2/feat/customers-*` |
| **Products** | `ProductController`, `CategorieController` | `ProductService`, `CategorieService` | `v2/feat/products-*` |
| **Invoices / POS** | `InvoiceController`, `CaisseController`, `PosController` | `InvoiceService` | `v2/feat/invoices-*` |
| **Payments** | `CheckoutController`, `PayementController`, `HistoriquePaiementController` | `CheckoutService`, `HistoriquePaiementService` | `v2/feat/payments-*` |
| **Campaigns** | `CampaignController`, `CampaignDetailController` | `CampaignService`, `MessageService` | `v2/feat/campaigns-*` |
| **Cross-selling** | `CrossSellingController`, `CrossProductController` | `CrossSellingService`, `BienvenuService`, etc. | `v2/feat/cross-selling-*` |
| **Calendar / RDV** | `CalendarController`, `RdvController` | `CalendarService`, `RdvService`, `RendezVousService` | `v2/feat/calendar-*` |
| **Reports** | `RepportController` | — | `v2/feat/reports-*` |
| **Admin** | `ActivityController`, `EmployerController` | `UserService` | `v2/feat/admin-*` |
| **Guest site** | `GuestController` | — | `v2/feat/marketing-*` (last or separate) |

---

### Module template (example: Invoices)

#### V2-PR-10a: `v2/feat/invoices-models`

- Port `Invoice`, `InvoiceProduct`, `InvoicePaiement`, `Fond`, `InitialFond` models
- Factories + relationships
- Unit tests for model helpers

#### V2-PR-10b: `v2/feat/invoices-policy-service`

- Port `InvoiceService` (~526 lines) — **refactor while porting:**
  - Extract query objects for `getBestSellers`, `getCrossSelling`
  - Eager-load `store`, `customer`, `products`
  - Remove commented `dd()` blocks
- `InvoicePolicy` full CRUD
- Feature tests: create invoice, return invoice, cancel

#### V2-PR-10c: `v2/feat/invoices-ui`

- Livewire: invoice list, create, print (DomPDF port)
- POS / caisse flow for employer role
- Empty states, loading states

#### V2-PR-10d: `v2/feat/invoices-pdf`

- Port DomPDF templates from `resources/views/owner/invoices/`
- Test PDF generation returns 200

**Commits (examples):**
```
feat(invoice): port Invoice and InvoiceProduct models with factories
feat(invoice): add InvoicePolicy with store-scoped authorization
refactor(invoice): port InvoiceService with eager loading for best sellers
feat(invoice): add Livewire invoice list and create components
test(invoice): add feature tests for invoice CRUD and IDOR denial
```

---

### Campaign module notes

**V1 complexity:** `CampaignService`, `CampaignDetailController`, filters, filters subqueries.

**V2 improvements during port:**
- Replace raw `DB::raw` aggregations with query builder / Eloquent where possible
- `CampaignFilterService` extracted from controller
- Form Requests: `StoreCampaignRequest`, `UpdateFilterRequest`
- Queue all SMS sends
- Credit calculation: resolve `Helper.php` TODO with tests

---

### Payment module notes

**Port:** `CheckoutService`, CMI hash validation, events (`OrderPlaced`, etc.)

**V2 fixes baked in:**
- No admin `payAll` without audit trail + policy
- Callback resolves order by `cmd`, not session
- Idempotent callback handler (duplicate CMI posts)

---

## Phase 3 — UI consolidation (Month 4–5)

### V2-PR-20: Unified layout system

**Replace V1 layouts:**
- `templates/index.blade.php`, `admin/*`, `owner/*`, `employe/*`, `simple/*`

**With:**
- `resources/views/layouts/app.blade.php`
- Role-based sidebar config (array or View Composer)
- One design token set (Tailwind 4)

**PR:** `feat(ui): unified app layout with role-based navigation`

---

### V2-PR-21: i18n completion

- Port `resources/lang/en`, `resources/lang/fr`
- `__('sideBar.key')` → JSON or PHP lang files
- Locale middleware

---

### V2-PR-22: Accessibility pass

- Form labels associated
- Focus management on modals
- Color + icon status indicators
- Target WCAG 2.2 Level AA on core flows

---

## Phase 4 — Cutover (Month 5–6)

### Parity checklist

| Flow | V1 route | V2 verified | Owner |
|------|----------|---------------|-------|
| Owner login | `auth.login` | [ ] | |
| Create invoice | employer caisse | [ ] | |
| Print invoice | `invoices.print` | [ ] | |
| CMI checkout | `checkout` | [ ] | |
| CMI callback | `success` / `callBack` | [ ] | |
| Create campaign | owner campaigns | [ ] | |
| Send birthday SMS | scheduler | [ ] | |
| Cross-selling cron | command | [ ] | |
| RDV calendar | owner calendar | [ ] | |
| Admin owner CRUD | admin owners | [ ] | |
| Excel import products | owner products | [ ] | |

---

### Cutover strategy

**Recommended:** Big-bang with maintenance window (simpler than dual-write).

1. **T-7 days:** Freeze V1 features; final data sync script if schema diverged
2. **T-1 day:** Full DB backup; deploy V2 to staging with production data copy
3. **T-0:** DNS / `app.sherin.ma` → V2; enable queues + scheduler
4. **T+1:** Monitor `integrations` log channel, failed jobs, CMI callbacks
5. **T+7:** Decommission V1 app server; archive `main` as `archive/v1`

**Rollback:** Keep V1 container/image for 14 days; DB backup restore procedure documented.

---

### V2-PR-30: Production readiness

- `docs/runbooks/deployment-v2.md`
- `docs/runbooks/incident-response.md`
- Health check route `/up`
- Sentry or similar error tracking
- Horizon for queue monitoring

---

## V2 testing strategy

### Coverage targets

| Layer | Target |
|-------|--------|
| Policies | 100% of rules |
| Payment callback | 100% |
| Integration gateways | Mocked HTTP, all edge cases |
| Services | 80% on ported services |
| UI | Feature tests on critical Livewire components |

### Test categories

```
tests/Feature/Auth/
tests/Feature/Invoice/
tests/Feature/Payment/CmiCallbackTest.php
tests/Feature/Campaign/
tests/Feature/Security/IdorTest.php
tests/Unit/Integrations/BulkSmsGatewayTest.php
tests/Unit/Integrations/CmiHashVerificationTest.php
```

### Characterization tests from V1

Before porting a service, optionally add a test **on V1** that captures current behavior, then port test to V2 — ensures business rules survive.

---

## V2 PR roadmap (full catalog)

### Phase 0
| ID | Branch | Title |
|----|--------|-------|
| V2-PR-01 | `v2/chore/bootstrap` | Scaffold Laravel 11 |
| V2-PR-02 | `v2/chore/ci` | CI + Pint + PHPStan + Pest |
| V2-PR-03 | `v2/docs/adr` | Architecture decision records |
| V2-PR-04 | `v2/db/port-migrations` | Schema port + indexes + FKs |

### Phase 1
| ID | Branch | Title |
|----|--------|-------|
| V2-PR-05 | `v2/feat/auth-roles` | Auth + role middleware |
| V2-PR-06 | `v2/feat/policies` | Policy layer |
| V2-PR-07 | `v2/feat/gateways` | SMS + CMI integration gateways |
| V2-PR-08 | `v2/chore/logging` | Structured logging |
| V2-PR-09 | `v2/feat/queues` | Jobs + scheduler |

### Phase 2 (modules)
| ID | Branch | Title |
|----|--------|-------|
| V2-PR-10 | `v2/feat/stores` | Store management |
| V2-PR-11 | `v2/feat/customers` | Customer CRM |
| V2-PR-12 | `v2/feat/products` | Products + categories |
| V2-PR-13 | `v2/feat/invoices` | Invoicing + POS (stacked) |
| V2-PR-14 | `v2/feat/payments` | CMI checkout + history |
| V2-PR-15 | `v2/feat/campaigns` | Campaigns + filters |
| V2-PR-16 | `v2/feat/cross-selling` | Cross-selling automation |
| V2-PR-17 | `v2/feat/calendar` | RDV + calendar |
| V2-PR-18 | `v2/feat/reports` | Reports + PDF exports |
| V2-PR-19 | `v2/feat/admin` | Admin panel (owners, activities) |

### Phase 3–4
| ID | Branch | Title |
|----|--------|-------|
| V2-PR-20 | `v2/feat/ui-layout` | Unified layout |
| V2-PR-21 | `v2/feat/i18n` | Localization |
| V2-PR-22 | `v2/feat/a11y` | Accessibility |
| V2-PR-23 | `v2/feat/excel` | Maatwebsite Excel import/export |
| V2-PR-30 | `v2/chore/production` | Runbooks, monitoring, cutover |

---

## What we port vs rewrite

| Port (refactor) | Rewrite |
|-----------------|---------|
| `InvoiceService` business rules | Blade/jQuery views |
| `CampaignService` filter logic | `routes/web.php` structure |
| CMI hash verification | Middleware duplicates |
| Eloquent models + relationships | `CampaignDetailController` raw SQL |
| Event/listener **flow** | GET delete routes |
| DomPDF templates (markup cleanup) | `gb/`, dead controllers |

---

## Definition of done — V2

- [ ] Parity checklist 100% green
- [ ] PHPStan level 6+ clean
- [ ] CI green on all PRs
- [ ] No hardcoded secrets
- [ ] All policies in place
- [ ] Queues processing SMS/email
- [ ] Scheduler running in production
- [ ] Runbooks published
- [ ] V1 decommissioned

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-08 | Initial V2 rebuild plan |
