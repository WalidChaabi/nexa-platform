# Technical Audit Report — Sherin Laravel Application

**Codebase:** `sherin-laravel-app-main`  
**Stack:** Laravel **5.7**, PHP **7.4**, Blade + jQuery/Vue 2, Laravel Mix 4, MySQL  
**Domain:** Multi-store retail/POS platform (Sherin.ma) — invoices, customers, SMS/email campaigns, CMI payments, calendar/RDV, cross-selling  
**Audit date:** June 8, 2026

This is a **legacy monolith with real business complexity**, but it carries **critical security debt**, **near-zero test coverage**, and **outdated dependencies**. The audit below is based on direct inspection of routes, controllers, services, models, migrations, views, tests, and config.

---

## 1. Architecture & Design Patterns

### Overall architecture

Classic **Laravel MVC monolith** with a **partial service layer**. Not microservices, not layered/hexagonal.

| Layer | Count | Notes |
|-------|-------|-------|
| Controllers | 42 | `Web\`, `Auth\`, `Guest\` |
| Services | 25 | Campaign, invoice, SMS, checkout, etc. |
| Repositories | 2 | Interface + `StoreRepository` — **unused, incomplete** |
| Models | 34 | Eloquent |
| Form Requests | **1** | `OwnerRequest.php` only |
| Policies | **0** | Commented out in `AuthServiceProvider` |
| Jobs | **0** | No `app/Jobs/` directory |

### Separation of concerns — uneven

Services hold heavy domain logic (`InvoiceService` ~526 lines, `CampaignService` ~405 lines), but controllers remain fat (`InvoiceController` ~489 lines, `CampaignController` ~356 lines). Controllers still run queries, validation, and view orchestration directly.

**DI is inconsistent:** some controllers inject services; others use `new CampaignService()` in constructors.

```php
// app/Http/Controllers/Web/CheckoutController.php
public function __construct()
{
    $this->checkoutService =  new CheckoutService;
    $this->userService =  new UserService;
}
```

### Design patterns

- **Used:** Middleware role gates (`IsAdmin`, `IsOwner`, `IsEmployer`), Events/Listeners (7 each), Observers (`RdvObserver`), Excel import/export.
- **Misused / abandoned:** Repository pattern (scaffold only), JWT (`tymon/jwt-auth` configured but unused — `routes/api.php` fully commented out).
- **Missing:** Policies, DTOs, API Resources, queued jobs, Form Request validation layer.

### Folder structure

Reasonable Laravel defaults, but cluttered:

- Duplicate middleware: `Owner.php` / `Employer.php` vs `IsOwner` / `IsEmployer` (only latter registered).
- Dead files: `CaisseController-copie.php`, `app\Http\Controllers\test.php`.
- **Phishing kit in repo:** `gb/` directory (Microsoft-login-style pages) — not part of the app domain.
- `routes/api.php` ~400 lines of commented debug routes.

### Role-based routing

`routes/web.php` groups by domain and role prefix (`admin`, `owner`, `employe`). Works conceptually, but the file is monolithic (~425 lines), mixes string and class syntax, and hardcodes `127.0.0.1` as the app domain.

**Architecture score drivers:** partial service extraction (+), no authorization layer (−), duplication (−), dead code (−).

---

## 2. Security Vulnerabilities

### Critical findings

#### SQL injection

User input concatenated into `DB::raw()`:

```php
// app/Http/Controllers/Web/CampaignDetailController.php (lines 42–50)
$result = DB::table('stores')
    ->select(
        DB::raw('(SELECT MAX(customers.points) FROM customers WHERE customers.store_id =' . $request->store_id . ') as maxPoints'),
        DB::raw('(SELECT MIN(customers.birth) FROM customers WHERE customers.store_id =' . $request->store_id . ') as ageMin'),
        DB::raw('(SELECT MAX(customers.birth) FROM customers WHERE customers.store_id =' . $request->store_id . ') as ageMax'),
        DB::raw('(SELECT MAX(invoices.invoice_date) FROM invoices JOIN customers ON invoices.customer_id = customers.id WHERE customers.store_id =' . $request->store_id . ') as last_visit')
    )
    ->where('stores.id', '=', $request->store_id)
    ->first();
```

#### Hardcoded secrets (in source, not `.env`)

BulkSMS API token in multiple services:

```php
// app/Services/CampaignService.php (lines 125–129)
public function sendSms($num = "0675295551", $texte = "hello hamza today ", $emetteur = "MORINO")
{
    $url = 'https://bulksms.ma/developer/sms/send';
    $fields_string = 'token=a4B2EZpCjP72JwA33JRTwYQ1e&tel=' . $num . '&message=' . urlencode($texte) . '&shortcode=' . $emetteur;
```

Also in `config/app.php` (CMI payment defaults) and `config/services.php` (Mailgun sandbox secret as fallback).

#### Unauthenticated dangerous routes

```php
// routes/web.php (lines 34–40)
Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return "campaign done ";
});
```

Public campaign/debug endpoints at lines 413–424 expose customer phone data and trigger SMS logic without auth.

#### Phishing kit in repository

`gb/main/index.php` mimics Microsoft login, uses reCAPTCHA, redirects to `office.com` — **must be removed immediately** and keys rotated.

#### IDOR (Insecure Direct Object Reference)

No resource ownership checks across invoices, customers, payments, stores, RDV. Example — any invoice by ID:

```php
// app/Http/Controllers/Web/InvoiceController.php (lines 340–348)
public function show($id)
{
    $store = Store::find(auth()->user()->store_id);
    // ...
    $invoice = Invoice::find($id);
    $customer = Customer::find($invoice->customer_id);
```

No verification that `$invoice->store_id` matches the user's store.

#### Mass assignment

`User` model allows `is_admin`, `store_id` in `$fillable`. `Customer` allows `id`, `points`. Widespread `$request->all()` in controllers.

#### XSS

Stored XSS in product history — HTML built from user names, rendered with `{!! !!}`:

```blade
{{-- resources/views/owner/products/hestorique.blade.php (line 39) --}}
<p>{!! $product_hestorique->notification !!}</p>
```

DOM XSS in calendar via `innerHTML` with customer data.

#### File uploads

No MIME/size validation; `getClientOriginalName()` used for storage paths — path traversal and arbitrary file upload risk in `StoreController` and `ProductController`.

#### Authentication flaws

- POS auth compares plaintext `token` field.
- Destructive operations via **GET** (`owners/{id}/delete`, etc.) — bypass CSRF.
- Login has no visible rate limiting.
- Admin can mark payments complete without CMI verification (`CampaignController::resetCampaigns`, `payAll`).

#### Sensitive data in logs

`CampaignService::sendSms` logs the full API token string via `Log::info($fields_string)`.

### OWASP Top 10 (2021) snapshot

| Risk | Status |
|------|--------|
| A01 Broken Access Control | **Fail** — IDOR, missing policies, public routes |
| A02 Cryptographic Failures | **Fail** — hardcoded secrets, plaintext tokens |
| A03 Injection | **Fail** — SQL injection in `CampaignDetailController` |
| A04 Insecure Design | **Fail** — GET deletes, admin payment bypass |
| A05 Security Misconfiguration | **Fail** — `/clear`, debug routes, default creds in config |
| A06 Vulnerable Components | **Fail** — Laravel 5.7, PHP 7.4, axios 0.18 EOL |
| A07 Auth Failures | **Partial fail** — role middleware exists but coarse |
| A08 Data Integrity Failures | **Fail** — CSRF exemptions on payment success/fail |
| A09 Logging Failures | **Fail** — secrets logged, no monitoring |
| A10 SSRF | Low exposure observed |

---

## 3. UI / UX Quality

### Frontend stack

- **202 Blade templates**, Bootstrap 4 admin theme (multiple layout variants: `admin`, `owner`, `employe`, `simple`, `restore`).
- jQuery + Vue 2 via Laravel Mix; prebuilt assets in `public/`.
- i18n partially via `trans()` (French/English mix).
- Guest marketing site uses a separate theme (`templates/index.blade.php`).

### Component structure

No modern component system (no Livewire, no React). Reusable Blade components are minimal (`components/text-input.blade.php`, `components/header.blade.php`). Heavy duplication across role-specific views.

### Accessibility

- Viewport meta present on main layouts.
- Some `aria-*` attributes in sidebars/headers, but **not systematic**.
- Forms often lack proper `<label>` associations.
- Color-only status indicators common.
- **Estimated WCAG 2.2 compliance: poor** (likely Level A failures).

### Responsiveness

`responsive.css` exists on guest site; admin dashboards use Bootstrap grid — **functional on desktop, uneven on mobile** (POS/calendar views are dense).

### Error / loading / empty states

- Global loading overlay in `templates/index.blade.php`.
- Errors often rendered as raw text: `{{ implode('', $errors->all(':message')) }}` without structure.
- **Almost no empty-state handling** in list views.
- `RepportController` has a `dd($item)` that **breaks the campaign report feature entirely**.

### Consistency

Multiple login/signup template variants (`login.blade.php`, `login-one.blade.php`, `login-bs-validation.blade.php`, etc.) suggest theme experimentation without consolidation.

---

## 4. Testing

| Asset | Status |
|-------|--------|
| Test files | **4** (`TestCase`, `CreatesApplication`, 2 examples) |
| Meaningful tests | **0** |
| Feature test | `GET /` → assert 200 |
| Unit test | `assertTrue(true)` |
| CI/CD | **None** (no `.github/workflows`, GitLab CI, Jenkins) |
| Factories/seeders | Exist but not used in tests |

**No coverage** for invoices, payments, campaigns, SMS, auth roles, or services. `phpunit.xml` is configured but effectively unused.

Tests assert framework scaffolding, not business behavior. No mocks for external SMS/payment APIs.

---

## 5. Scalability & Performance

### N+1 queries — widespread

Examples:

- `OwnerController@getDashboard`: `Store::findOrFail()` inside a loop per store.
- `InvoiceService::getBestSellers`: accesses `$seller->store` without eager loading.
- `InvoiceService::getCrossSelling`: queries inside nested `map()` loops.
- `BirthdayService::SmsBirthday`: per-customer `Code` and `Store` lookups.

Some `with()` usage exists (`with('campaign')`, `with('invoices')`) but is inconsistent.

### Database indexing — critical gap

Migrations define **no indexes** on foreign keys (`store_id`, `customer_id`, `user_id`) or frequently filtered columns (`invoice_date`, `phone`). Only `password_resets.email` is indexed.

```php
// database/migrations/2021_03_14_210739_create_invoices_table.php
Schema::create('invoices', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('store_id');
    $table->integer('user_id');
    // ...
    $table->dateTime('invoice_date');
```

No foreign key constraints either — referential integrity is application-enforced only.

### Caching

Essentially absent. One `Cache::put('payed', true, 10)` in `CheckoutService`. No `Cache::remember()`, no query caching, no Redis usage despite config.

### Async / concurrency

- `QUEUE_CONNECTION=sync` — everything runs inline.
- Events/listeners import `ShouldQueue` but **never implement it**.
- SMS sending via cURL happens **during HTTP requests** and artisan commands.
- `Console\Kernel` scheduled tasks are **all commented out**.

### Horizontal scaling

Session/file cache drivers, synchronous queues, local file uploads — **not horizontally scalable** without significant rework. Stateless API layer doesn't exist (API routes commented out).

---

## 6. Code Visibility & Maintainability

### Readability & naming

- Mixed French/English (`hestorique`, `PaiedSms`, `RepportController`, `CrosSelling`).
- Inconsistent casing (`is_admin ==2` without spaces).
- Direct `$_GET` usage in controllers alongside `$request`.

### Documentation

Default Laravel `readme.md` — **no project-specific setup, architecture, or deployment docs**.

### Dead code & TODOs

- `routes/api.php`: ~400 lines commented.
- `CaisseController-copie.php`, unreachable code in `CrossSellingController::Bienvenu`.
- `CustomerService::getSharedCustomer()` has unreachable code after early return.
- TODOs in `Helper.php`, `CrosSelling.php`.

### Logging & observability

Ad-hoc `Log::info()` scattered across services/commands. No structured logging, log levels, channels, or error tracking (Sentry, etc.). Payment callbacks log full request payloads.

### Environment config

`.env` is gitignored (good). Bad: **fallback secrets in `config/`** mean the app can run with committed credentials even without `.env`.

### Known bugs affecting maintainability trust

```php
// app/Http/Controllers/Web/CustomerController.php (lines 64–66)
switch (auth()->user()->is_admin) {
    case '1':
        return view('admin.customers.list', compact('clients', 'stores'));
```

`$clients` and `$stores` are **undefined** for admin/owner — this path will error at runtime.

---

## 7. Features & Functionality

### Core features present

| Feature | Implementation | Quality |
|---------|----------------|---------|
| Multi-role auth (admin/owner/employee) | Middleware | Works, coarse |
| Store management | CRUD + logo upload | Functional, insecure uploads |
| POS / invoicing | `InvoiceController`, `CaisseController` | Complex, IDOR risks |
| Customer CRM | Shared customers across stores | Buggy admin view |
| SMS/email campaigns | Services + artisan commands | Duplicated logic, secrets in code |
| CMI payment gateway | `CheckoutController` + hash verify | Partially correct |
| Calendar / RDV | FullCalendar + services | DOM XSS risk |
| Cross-selling | Multiple services/controllers | Duplicated, incomplete TODO |
| Reports / PDF | DomPDF exports | `dd()` breaks campaign report |
| Excel import/export | Maatwebsite Excel | No upload validation |

### Business logic correctness concerns

- Admin `resetCampaigns` / `payAll` bypass payment gateway.
- Campaign credit calculations have a TODO in `Helper.php` for "pay all" number generation.
- Scheduled campaigns disabled in `Kernel.php` — production behavior depends on manual cron/command invocation.
- Payment `success()` relies on `auth()->user()` during CMI callback — may fail if session expires during redirect.

### Error handling

Default `Handler.php` with no customization. `dd()` left in `InvoiceController` and `RepportController`. No graceful API error responses.

---

## 8. Dependencies & Tooling

### Backend (`composer.json`)

| Package | Version | Risk |
|---------|---------|------|
| `laravel/framework` | **5.7.\*** | EOL since Sept 2020 — unpatched CVEs |
| `php` | **^7.4.1** | EOL Nov 2022 |
| `tymon/jwt-auth` | ^1.0 | Installed but unused |
| `fzaninotto/faker` | ^1.4 | Abandoned (replaced by `fakerphp/faker`) |
| `axios` (npm) | **^0.18** | Known CVEs |

No static analysis (PHPStan/Psalm), no Pint/CS Fixer, no Larastan.

### Frontend (`package.json`)

Laravel Mix 4, Vue 2.5, Bootstrap 4, jQuery 3 — all dated. Built assets committed to `public/`.

### Dev experience

- `docker-compose.yml`: MySQL 8 only — no app container.
- No Makefile, no Sail, no dev scripts beyond default Mix.
- `.env.example` is generic Laravel — missing Sherin-specific vars (CMI, SMS, etc.).

---

## Final Scorecard

| Dimension | Score /10 | Key Finding |
|-----------|-----------|-------------|
| Architecture | **4** | Partial service layer; no policies/repos; fat controllers + services |
| Security | **2** | SQLi, hardcoded secrets, IDOR, phishing kit in repo, XSS |
| UI/UX | **5** | Functional Bootstrap admin UI; weak a11y, no empty states |
| Testing | **1** | 2 scaffold tests; zero business coverage; no CI |
| Scalability | **3** | N+1 queries, no indexes, sync queues, no caching |
| Visibility | **3** | No docs, dead code, secrets in logs, naming inconsistency |
| Features | **5** | Rich domain implemented but brittle with known bugs |
| Dependencies | **2** | Laravel 5.7 + PHP 7.4 EOL; abandoned packages |
| **Overall** | **3** | Production-risk legacy monolith needing security-first remediation |

---

## Top 5 Critical Issues (fix immediately)

1. **Remove `gb/` phishing kit from the repo and production servers; rotate all exposed reCAPTCHA/API keys.**
2. **Rotate and externalize all hardcoded credentials** — BulkSMS token, CMI store key, Mailgun secret — move to `.env` only, with no config fallbacks.
3. **Fix SQL injection** in `CampaignDetailController` — use parameter binding instead of string concatenation in `DB::raw()`.
4. **Close unauthenticated attack surface** — remove `/clear`, lock down campaign/debug routes (`/cross-selling`, `/rdv`, etc.), require auth on all sensitive endpoints.
5. **Implement resource-level authorization** — Policies + store ownership checks on invoices, customers, payments, stores, RDV; remove GET-based delete routes.

---

## Top 5 Improvements (fix soon)

1. **Upgrade platform** — Laravel 5.7 → 10/11 LTS, PHP 8.2+, update npm deps (axios, Mix/Vite).
2. **Add database indexes** on `store_id`, `customer_id`, `invoice_date`, `phone`, and foreign keys with constraints.
3. **Queue SMS/email/payment side effects** — create Job classes, implement `ShouldQueue` on listeners, enable scheduler in `Kernel.php`.
4. **Establish a test baseline** — Feature tests for auth, invoice CRUD, payment callback, campaign creation; add GitHub Actions CI.
5. **Input validation layer** — Form Requests for all write endpoints; strip `is_admin`/`points`/`id` from `$fillable`; replace `$request->all()` with validated subsets.

---

## What was done well (don't overlook the wins)

- **Clear role separation** via middleware (`admin`, `owner`, `employer`, `active`) gives a understandable access model.
- **Service extraction started** for the hardest domains (invoices, campaigns, checkout) — a reasonable foundation to build on.
- **CMI payment hash verification** in `CheckoutController::success()` and `callBack()` follows the gateway's integrity model.
- **Event-driven payment side effects** (activate account, send SMS/email) is the right architectural direction — it just needs queuing.
- **i18n hooks** (`trans()`, `LanguageSwitcher` middleware) support bilingual operation.
- **HTTPS forcing in production** via `AppServiceProvider` is a good baseline.
- **Excel import/export and PDF generation** show the app handles real operational workflows, not just CRUD scaffolding.

---

## What I would do differently as a senior dev

1. **Security-first sprint before features** — This codebase should not receive new features until secrets are rotated, public routes are closed, and IDOR is fixed. Treat it as incident response, not refactoring.

2. **Upgrade on a strangler path** — Don't big-bang rewrite. Stand up Laravel 11 on PHP 8.2, port models/migrations first, then services with tests, then routes/views. Keep CMI and SMS behind interfaces (`SmsGateway`, `PaymentGateway`) from day one.

3. **Authorization as a first-class layer** — Replace `is_admin == 2` checks with Policies (`InvoicePolicy::view`, `CustomerPolicy::update`) and always scope queries: `Invoice::where('store_id', auth()->user()->store_id)`.

4. **Make the test suite the safety net for upgrade** — Before touching Laravel version, write characterization tests for payment flow, invoice creation, and campaign filtering. Even 20 focused feature tests would change the risk profile dramatically.

5. **Operationalize campaigns properly** — Move SMS to queued jobs with rate limiting, retries, and dead-letter logging. Enable the scheduler. Never log API tokens.

6. **Delete aggressively** — Remove `gb/`, `CaisseController-copie.php`, commented `api.php` routes, duplicate login templates, and `dd()` calls. Dead code is a security and cognitive liability.

7. **Database discipline** — Add indexes and foreign keys in a dedicated migration. The current schema will not scale past modest data volumes.

8. **Single layout system** — Consolidate 5+ layout variants into one configurable layout. Reduce Blade duplication by 40%+.

---

## Bottom line

Sherin is a **feature-rich POS/campaign platform** with evidence of real-world use, but it is operating at **significant security and maintainability risk** on an **unsupported framework stack**. The highest ROI path is a **security remediation sprint**, then **framework upgrade with tests**, then **performance hardening** (indexes, queues, caching).
