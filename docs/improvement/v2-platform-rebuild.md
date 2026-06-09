# V2 — Platform Rebuild Plan

**Track:** Next-generation Nexa platform  
**Branch base:** `v2/main` (new Laravel 11 app — monorepo `apps/v2/` or separate repo)  
**Duration:** 4–6 months to production cutover  
**Stack target:** **Laravel 11 API** (PHP 8.3) + **Next.js 16** web (TypeScript, Tailwind, shadcn/ui)

> **Locked:** [ADR-002 — Next.js + API-first](../adr/002-nextjs-api-first-ui.md). Mobile app ships **after** V2 web cutover on the **same API**.

---

## Strategic decisions (lock before Phase 0)

| Decision | Choice | Notes |
|----------|--------|-------|
| Repo layout | Monorepo: `legacy/v1/` + `apps/sherin-api/` + `apps/sherin-web/` | See [ADR-002](../adr/002-nextjs-api-first-ui.md) |
| Database | **Shared DB** initially (same MySQL schema, evolved via V2 migrations) | V1 + V2 API share schema until cutover |
| **Web UI** | **Next.js 16 App Router + shadcn/ui** | No Blade/Livewire in V2 |
| **API** | **Laravel API-first** (`/api/v1/*`) | Web + future mobile use same endpoints |
| Auth | **Sanctum** — SPA cookies for Next.js; PAT for mobile (Phase 5) | Roles: `admin`, `owner`, `employer` |
| Multi-tenancy | `store_id` scoping + Policies (not package-based) | `stancl/tenancy` only if true isolation needed later |

Document choices in `docs/adr/` (Architecture Decision Records).

---

## V2 directory structure (target)

```
legacy/v1/                         # Laravel 5.7 — maintenance until cutover
apps/
├── sherin-api/                    # Laravel 11+ — API only
│   ├── app/
│   │   ├── Domain/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/V1/
│   │   │   ├── Middleware/
│   │   │   ├── Requests/
│   │   │   └── Resources/         # API Resources — stable JSON shapes
│   │   ├── Policies/
│   │   ├── Services/              # Ported + refactored from V1
│   │   ├── Integrations/          # BulkSms, CMI gateways
│   │   ├── Jobs/
│   │   └── Models/
│   ├── routes/
│   │   ├── api.php                # /api/v1/*
│   │   └── console.php
│   ├── database/
│   └── tests/
│
└── sherin-web/                    # Next.js 16 — web UI
    ├── app/                       # App Router (role-based route groups)
    │   ├── (auth)/
    │   ├── (admin)/
    │   ├── (owner)/
    │   └── (employer)/
    ├── components/                # shadcn/ui + domain components
    ├── lib/
    │   └── api/                   # Typed fetch client → sherin-api
    └── tests/                     # Vitest + Playwright (critical flows)
```

---

## Phase 0 — Bootstrap (Week 2–4)

### V2-PR-01: Initialize Laravel 11 API

**Branch:** `v2/chore/bootstrap-laravel11-api`

**Tasks:**
- `composer create-project laravel/laravel apps/sherin-api`
- PHP 8.3, Laravel 11 — **API-only** (remove default web views or keep minimal `/up` health)
- `routes/api.php` prefix `v1`
- Configure `.env` for shared dev database
- CORS + Sanctum prep in `.env.example`

**Commits:**
```
chore(v2): scaffold Laravel 11 API application
chore(v2): configure database connection for shared schema
chore(v2): add API v1 route prefix and health check
docs(v2): add local development setup guide
```

---

### V2-PR-01b: Initialize Next.js web application

**Branch:** `v2/chore/bootstrap-nextjs-web`

**Tasks:**
- `npx create-next-app@latest apps/sherin-web` (TypeScript, Tailwind, App Router)
- Install shadcn/ui; set design tokens aligned with Nexa brand
- `lib/api/client.ts` — base URL from `NEXT_PUBLIC_API_URL`
- Role-based layout shells: `(admin)`, `(owner)`, `(employer)`
- ESLint + Prettier; CI job for `npm run build` + `npm run lint`

**Commits:**
```
chore(v2): scaffold Next.js 16 web application
chore(v2): add shadcn/ui and app shell layouts
chore(v2): add typed API client for sherin-api
docs(v2): document local dev (API :8000, web :3000)
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
# Triggers on PRs touching apps/sherin-api/** or apps/sherin-web/**
# API job:
- composer install (apps/sherin-api)
- pint, phpstan, php artisan test
# Web job:
- npm ci (apps/sherin-web)
- npm run lint && npm run build
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
| 002 | UI stack — Next.js web + Laravel API ([accepted](../adr/002-nextjs-api-first-ui.md)) |
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

### V2-PR-05: Authentication and roles (API + Next.js)

**Branch:** `v2/feat/auth-roles`

**API (`apps/sherin-api`):**
- Laravel Sanctum — SPA cookie auth for Next.js (`SANCTUM_STATEFUL_DOMAINS`, CORS)
- `POST /api/v1/auth/login`, `POST /api/v1/auth/logout`, `GET /api/v1/auth/me`
- Enum: `App\Enums\UserRole` (`Admin`, `Owner`, `Employer`)
- Middleware: `EnsureUserIsActive`; role middleware on route groups
- Login throttle on API login route

**Web (`apps/sherin-web`):**
- Login page → calls API; stores session via Sanctum cookie
- Middleware / layout guards redirect by role (`admin` → `/admin`, etc.)
- Locale switcher (fr/en) — `next-intl` or similar

**Tests:**
- API: admin cannot hit owner-only endpoints; inactive user 403; throttle
- Web: smoke test login redirect (Playwright, optional in Phase 1)

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

Port in **business value order**. Each module = stacked PRs:

**API:** models → policy → service → **API Resource + Controller** → tests  
**Web:** typed API client methods → pages/components (shadcn/ui) → E2E for critical flows

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

#### V2-PR-10c: `v2/feat/invoices-api`

- `InvoiceController` (API v1): index, store, show, cancel, print (returns PDF URL or stream)
- `InvoiceResource`, `StoreInvoiceRequest`, pagination
- Feature tests: CRUD + IDOR denial via API

#### V2-PR-10d: `v2/feat/invoices-web`

- Next.js: employer invoice list, create, detail; owner invoice history
- POS / caisse flow (client-heavy UI; totals via API)
- PDF: open print endpoint from API or signed URL

**Commits (examples):**
```
feat(invoice): port Invoice models with factories
feat(invoice): add InvoicePolicy and API v1 endpoints
refactor(invoice): port InvoiceService with eager loading
feat(invoice-web): add invoice list and create pages (Next.js)
test(invoice): API feature tests for CRUD and IDOR denial
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

## Phase 3 — Web UI polish (Month 4–5)

### V2-PR-20: Unified Next.js layout system

**Replace V1 Blade layouts** (`templates/*`, `admin/*`, `owner/*`, `employe/*`) **with:**
- Shared `app-shell` component (sidebar, header, breadcrumbs)
- Role-based nav config per layout group `(admin)`, `(owner)`, `(employer)`
- shadcn/ui data tables, forms, dialogs — one design token set (Tailwind 4)

**PR:** `feat(web): unified app shell with role-based navigation`

---

### V2-PR-21: i18n completion

- Port `resources/lang/en`, `resources/lang/fr` → `apps/sherin-web/messages/{fr,en}.json`
- API error messages remain Laravel `lang/` for validation; web maps codes to UI copy
- Locale cookie / `next-intl` routing (`/fr/...`, `/en/...` or cookie-based)

---

### V2-PR-22: Accessibility pass

- Form labels associated
- Focus management on modals
- Color + icon status indicators
- Target WCAG 2.2 Level AA on core flows

---

## Phase 4 — Cutover (Month 5–6)

Deploy **two apps** (or reverse proxy): `app.nexa.app` → Next.js, `api.nexa.app` → Laravel (or `/api` on same host).

### Parity checklist

| Flow | V1 route | V2 built | V2 verified | Owner |
|------|----------|----------|-------------|-------|
| Owner login | `auth.login` | [x] | [ ] | Frontend + Backend |
| Employer login | `auth.login` | [x] | [ ] | Frontend + Backend |
| Admin login | `auth.login` | [x] | [ ] | Frontend + Backend |
| Create invoice | employer caisse | [x] | [ ] | Frontend + Backend |
| Print invoice | `invoices.print` | [x] | [ ] | Frontend + Backend |
| CMI checkout | `checkout` | [x] | [ ] | Backend + Frontend |
| CMI callback | `success` / `callBack` | [x] | [ ] | Backend |
| Create campaign | owner campaigns | [x] | [ ] | Frontend + Backend |
| Send birthday SMS | scheduler | [x] | [ ] | Backend |
| Cross-selling cron | command | [x] | [ ] | Backend |
| RDV calendar + reminders | owner calendar | [x] | [ ] | Frontend + Backend |
| Admin owner CRUD | admin owners | [x] | [ ] | Frontend + Backend |
| Excel import products | owner products | [ ] | [ ] | Backend + Frontend |

**Legend:** *V2 built* = code exists locally; *V2 verified* = manual + automated parity sign-off against V1 behavior.

---

### Remaining work board (ordered to cutover)

Work in **waves**. Finish each wave’s *verified* column before cutover. Roles: **BE** = `apps/sherin-api`, **FE** = `apps/sherin-web`, **Ops** = infra/runbooks, **QA** = parity sign-off.

#### Wave 0 — Stabilize current branch (now)

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W0-1 | Commit + push uncommitted UI (login-02, form patterns, invoices DataTable, Docker naming) | FE + Ops | P0 | — | [x] |
| W0-2 | Smoke-test employer invoices list actions + create flow | QA | P0 | W0-1 | [ ] |
| W0-3 | Confirm demo seed + `nexa_platform` Docker stack on fresh clone | Ops | P1 | — | [ ] |

#### Wave 1 — Money path (cutover blocker)

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W1-1 | E2E: owner checkout preview → redirect payload → return page | QA | P0 | W0-1 | [ ] |
| W1-2 | Verify CMI callback idempotency + hash (staging with CMI sandbox) | BE + QA | P0 | — | [ ] |
| W1-3 | Confirm payment confirmation email/SMS jobs run on `QUEUE_CONNECTION=redis` | BE + Ops | P0 | W1-2 | [ ] |
| W1-4 | FE: checkout/return error states match V1 copy + i18n | FE | P1 | W1-1 | [ ] |

#### Wave 2 — Core daily operations

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W2-1 | Parity: login + role redirect (admin / owner / employer) | QA | P0 | W0-2 | [ ] |
| W2-2 | Parity: employer create invoice (catalog, lines, payment, totals) | QA | P0 | W0-2 | [ ] |
| W2-3 | Parity: invoice print/PDF matches V1 layout (logo, footer, lines) | BE + FE + QA | P0 | W2-2 | [ ] |
| W2-4 | Parity: admin owner CRUD + activate/deactivate | QA | P1 | W2-1 | [ ] |
| W2-5 | IDOR regression suite on store-scoped resources | BE | P1 | — | [ ] |

#### Wave 3 — Campaigns & scheduler

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W3-1 | **Wire birthday command** — `nexa:birthday-campaign` creates unpaid `messages_customers` rows (V1 parity) | BE | P0 | — | [x] |
| W3-2 | Parity: owner create/edit campaign + filters | QA | P1 | W2-1 | [ ] |
| W3-3 | Verify `nexa:cross-selling` against V1 sample data (queued SMS count) | BE + QA | P1 | — | [ ] |
| W3-4 | Verify `nexa:rendez-vous` reminders (30-min window) | BE + QA | P1 | — | [ ] |
| W3-5 | Ops: document scheduler cron (`schedule:run` every minute) + queue worker in prod | Ops | P0 | W3-1 | [ ] |

#### Wave 4 — Calendar & CRM polish

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W4-1 | Parity: owner/employer calendar — create, edit, delete RDV | QA | P1 | W2-1 | [ ] |
| W4-2 | Parity: cross-selling rules UI + SMS history | QA | P1 | W3-3 | [ ] |
| W4-3 | Parity: reports (sales, customers, products) + export | QA | P2 | W2-1 | [ ] |

#### Wave 5 — Excel & data import (post-core or parallel)

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W5-1 | Port V1 `ProductsImport` (Maatwebsite) → API endpoint + validation | BE | P2 | — | [ ] |
| W5-2 | FE: upload UI on owner products (progress, row errors) | FE | P2 | W5-1 | [ ] |
| W5-3 | Parity: import sample spreadsheet vs V1 row counts | QA | P2 | W5-2 | [ ] |

#### Wave 6 — Quality gates (run in parallel with Waves 1–4)

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W6-1 | Add PHPStan + Pint to V2 CI (per V2-PR-02 plan) | BE | P1 | — | [ ] |
| W6-2 | Playwright: login, invoice create, checkout happy path | FE + QA | P1 | W1-1, W2-2 | [ ] |
| W6-3 | API contract tests for critical Resources (invoice, payment) | BE | P2 | — | [ ] |
| W6-4 | WCAG spot-check on login, invoice create, checkout | FE | P2 | W2-2 | [ ] |

#### Wave 7 — Production readiness (before DNS flip)

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W7-1 | Write `docs/runbooks/deployment-v2.md` | Ops | P0 | W1-3 | [ ] |
| W7-2 | Write `docs/runbooks/incident-response.md` | Ops | P0 | — | [ ] |
| W7-3 | Staging deploy with production DB copy; full parity pass | Ops + QA | P0 | W2–W4 | [ ] |
| W7-4 | Sanctum cookie domains + CORS on production hostnames | BE + Ops | P0 | W7-3 | [ ] |
| W7-5 | Horizon (queues) + error tracking (e.g. Sentry) | Ops | P1 | W7-1 | [ ] |
| W7-6 | Rollback drill: restore DB + revert DNS within 14-day window | Ops | P0 | W7-3 | [ ] |

#### Wave 8 — Cutover & decommission

| ID | Task | Role | Priority | Depends on | Status |
|----|------|------|----------|------------|--------|
| W8-1 | Freeze V1 features (T-7) | All | P0 | W7-3 green | [ ] |
| W8-2 | Maintenance window: DNS web → Next.js, API → Laravel | Ops | P0 | W8-1 | [ ] |
| W8-3 | T+1 monitor: CMI callbacks, failed jobs, integrations log | Ops | P0 | W8-2 | [ ] |
| W8-4 | T+7 decommission V1; archive `main` → `archive/v1` | Ops | P0 | W8-3 | [ ] |
| W8-5 | Merge `v2/main` → `main` if production line still tracks V1 | Ops | P1 | W8-2 | [ ] |

---

### Suggested execution order (summary)

```text
W0 stabilize → W1 payments → W2 core POS/auth → W3 scheduler (birthday!) → W4 calendar/reports
     ↘ W6 quality gates (parallel) ↗
W7 staging + runbooks → W8 cutover
W5 Excel import (can slip after cutover if owners accept manual import temporarily)
```

**Critical path:** W0-1 → W1 (payments) → W2 (invoice/print) → **W3-1 (birthday stub)** → W7 (staging) → W8.

**Note:** Birthday scheduler creates billing rows (`messages_customers`); owners still pay via CMI before SMS sends. Parity **verification** (W3-2+) remains open.

---

### Cutover strategy

**Recommended:** Big-bang with maintenance window (simpler than dual-write).

1. **T-7 days:** Freeze V1 features; final data sync script if schema diverged
2. **T-1 day:** Full DB backup; deploy V2 to staging with production data copy
3. **T-0:** DNS — web → Next.js, API → Laravel; enable queues + scheduler; verify Sanctum cookies on production domain
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

## Phase 5 — Mobile app (post-cutover)

> **Not in V2 cutover scope.** Web ships first; mobile reuses `apps/sherin-api`.

| Task | Detail |
|------|--------|
| Auth | Sanctum personal access tokens or dedicated mobile login (`POST /api/v1/auth/mobile/login`) |
| API | Harden OpenAPI spec from existing v1 routes; version only when breaking |
| Clients | Flutter / React Native / native — team choice later |
| Parity | Subset of flows first (login, customers, invoices, RDV) |

No duplicate business logic in the mobile app — API Resources remain the contract.

---

## V2 testing strategy

### Coverage targets

| Layer | Target |
|-------|--------|
| Policies | 100% of rules |
| Payment callback | 100% |
| Integration gateways | Mocked HTTP, all edge cases |
| Services | 80% on ported services |
| API | Feature tests on all v1 endpoints; contract tests for Resources |
| Web | Playwright on login, invoice create, checkout; Vitest for utilities |

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
| V2-PR-01 | `v2/chore/bootstrap-laravel11-api` | Scaffold Laravel 11 API |
| V2-PR-01b | `v2/chore/bootstrap-nextjs-web` | Scaffold Next.js + shadcn/ui |
| V2-PR-02 | `v2/chore/ci` | CI (API + web) + Pint + PHPStan + Pest |
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
| V2-PR-20 | `v2/feat/web-layout` | Unified Next.js app shell |
| V2-PR-21 | `v2/feat/i18n` | Localization |
| V2-PR-22 | `v2/feat/a11y` | Accessibility |
| V2-PR-23 | `v2/feat/excel` | Maatwebsite Excel import/export |
| V2-PR-30 | `v2/chore/production` | Runbooks, monitoring, cutover |

---

## What we port vs rewrite

| Port (refactor) | Rewrite |
|-----------------|---------|
| `InvoiceService` business rules | Blade/jQuery views → **Next.js pages** |
| `CampaignService` filter logic | V1 `routes/web.php` monolith |
| CMI hash verification | Middleware duplicates |
| Eloquent models + relationships | `CampaignDetailController` raw SQL |
| Event/listener **flow** | GET delete routes |
| DomPDF **generation** (API) | PDF HTML templates (cleanup); **no Blade UI** |
| API Resource shapes (new) | Livewire / Inertia (not used) |

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
| 2026-06-08 | **ADR-002:** Next.js web + Laravel API; mobile Phase 5 on same API |
| 2026-06-09 | Added remaining work board (W0–W8), expanded parity checklist with built vs verified |
