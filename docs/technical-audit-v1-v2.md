# Technical Audit — V1 Legacy vs V2 Platform

**Repository:** `nexa-platform`  
**V1 path:** `legacy/v1/`  
**V2 paths:** `apps/nexa-api/`, `apps/nexa-web/`  
**Audit date:** June 9, 2026  
**V2 reference commit:** `7431639` on `v2/main`  
**Related docs:** [V1 technical audit](./technical-audit-report.md), [V2 rebuild plan](./improvement/v2-platform-rebuild.md)

This document compares the production legacy application (V1) with the API-first rebuild (V2): architecture, feature parity, API surface, UI/UX, security, data migration, DevOps, and recommended next steps.

---

## Executive summary

| Dimension | V1 (`legacy/v1/`) | V2 (`apps/nexa-api/` + `apps/nexa-web/`) |
|-----------|-------------------|------------------------------------------|
| **Architecture** | Laravel monolith + Blade | Laravel 12 JSON API + Next.js 16 SPA |
| **Stack** | Laravel 5.7, PHP 7.4 (EOL) | Laravel 12, PHP 8.2+, React 19, Tailwind 4 |
| **Auth** | Session cookies + role middleware | Sanctum SPA + `active` + `role` middleware + Policies |
| **Public API** | None (commented `routes/api.php`) | REST under `/api/v1/*` |
| **UI** | ~200 Blade views, server-rendered tables | shadcn/ui, TanStack Table, Dialog/Sheet forms |
| **Dashboard** | Static or controller-driven Blade charts | Role KPI cards + 7-day Recharts trends |
| **Business breadth** | Full production feature set | Core retail flows; several V1-only modules not ported |
| **Ops** | `php artisan serve`, V1 CI | Docker Compose, V2 CI (Pint, PHPUnit, Playwright) |
| **Brand** | Historical Sherin references in legacy views | Nexa rebrand (`nexa-api`, `nexa-web`, assets) |

**Conclusion:** V2 is the correct long-term foundation (security, maintainability, API-first, modern UX). V1 remains broader in production features (employees, restaurant tables, SMS admin tooling, caisse, imports). The main V2 gaps are **V1-only business domains**, **staging parity verification**, and **uniform modern list UX** across every page.

---

## 1. Architecture

### V1 — monolith

```
Browser → Laravel 5.7 → Blade views + form POSTs → MySQL
```

- All behaviour exposed via **web routes** (`legacy/v1/routes/web.php`).
- Role prefixes: `admin`, `owner`, `employe` (employer).
- Partial service layer; fat controllers; almost no Form Requests or Policies.
- Queues often `sync` in production configs.
- No mobile or third-party API.

### V2 — API + SPA

```
Browser → Next.js 16 (nexa-web) → Laravel 12 API (nexa-api) → MySQL
```

- API prefix: `api/v1` (`apps/nexa-api/bootstrap/app.php`).
- Auth: Sanctum stateful SPA (`POST /auth/login`, cookie session).
- Authorization: Policies + `StoreAccess` for store-scoped data.
- Form Requests on write endpoints; API Resources for stable JSON shapes.
- Docker: MySQL + API + web with bind mounts and hot reload (`docker-compose.yml`).
- ADR: [002-nextjs-api-first-ui](./adr/002-nextjs-api-first-ui.md).

### Auth comparison

| | V1 | V2 |
|--|----|----|
| Login | `POST /login` (web, throttle 5/min) | `POST /api/v1/auth/login` (throttle 6/min) |
| Session | Laravel session | Sanctum + `SANCTUM_STATEFUL_DOMAINS` |
| Roles | `is_admin` flag + middleware | `UserRole` enum + `role:admin` middleware |
| Logout | Web redirect | `POST /api/v1/auth/logout` |

---

## 2. Feature parity matrix

**Legend:** ✅ Full (API + UI in repo) · 🟡 Partial · ❌ Missing

### Admin

| Feature | V1 | V2 | Notes |
|---------|----|----|-------|
| Dashboard / KPIs / charts | Blade dashboard | 🟡 | `GET /dashboard/kpis`; KPI cards + Recharts (`dashboard-kpis.tsx`, `dashboard-charts.tsx`) |
| Owners CRUD + activate | ✅ | ✅ | DataTable + Dialog (`admin/owners`) |
| Activities CRUD | ✅ | ✅ | DataTable + Dialog (`admin/activities`) |
| Stores + feature toggles | ✅ many POST toggles | 🟡 | API CRUD `/stores`; UI `admin/stores` (list; toggles not in V2 API) |
| Customers | ✅ | 🟡 | API `/customers`; UI `admin/customers` |
| Products + categories | ✅ | 🟡 | API `/products`, `/categories`; UI `admin/products` |
| Cross-selling rules | ✅ | 🟡 | API `/cross-products`; UI `admin/cross-selling` |
| Campaigns (SMS/email) | ✅ bulk ops | 🟡 | API campaign-details; UI `admin/campaigns`; no `reset`/`payAll`/`credit` |
| Payments / history | ✅ | 🟡 | API `/payments`; UI `admin/payments` |
| Employees | ✅ | ❌ | No V2 route |
| Templates | ✅ | ❌ | |
| Manual SMS send | ✅ | ❌ | |
| Reports | Campaign/SMS/charge Blade | 🟡 | `/reports/sales|customers|products` + export |
| Settings | Account/inbox | 🟡 | Locale/theme (`admin/settings`) |
| Product Excel import | ✅ | ❌ | Rebuild plan W5 still open |

### Owner

| Feature | V1 | V2 | Notes |
|---------|----|----|-------|
| Dashboard + charts | `/chart-data`, reports | 🟡 | KPIs + 7-day charts |
| Stores | ✅ | 🟡 | DataTable + Dialog edit; create via `/owner/stores/new` |
| Products + import | ✅ | 🟡 | API + `owner/products`; no import API |
| Customers | ✅ | 🟡 | API + `owner/customers` |
| Invoices / returns | ✅ | 🟡 | API list/show/print/cancel; `owner/invoices` pages |
| Payments / CMI | ✅ | 🟡 | Checkout API + `owner/payments`, checkout/return pages |
| Campaigns | ✅ extensive | 🟡 | DataTable + create Dialog (`owner/campaigns`) |
| Cross-selling | ✅ | 🟡 | DataTable + create Dialog (`owner/cross-selling`) |
| Calendar / RDV | ✅ | 🟡 | API `/rdvs`; `owner/calendar` + `AppointmentsPanel` |
| Employees / objectives | ✅ | ❌ | |
| Caisse / initial fonds | ✅ | ❌ | |
| Epingles (pins) | Full CRUD | 🟡 | `GET /epingles` only |
| Reports | Blade campaign reports | 🟡 | `owner/reports` unified reports |

### Employer (POS)

| Feature | V1 | V2 | Notes |
|---------|----|----|-------|
| Dashboard | Blade | 🟡 | KPIs + quick actions |
| Invoice create (POS) | ✅ core path | 🟡 | `/employer/invoices/create` + `/pos/catalog` API |
| Invoice list / print | ✅ | 🟡 | DataTable pattern on `employer/invoices` |
| Customers | ✅ | ✅ | DataTable + Dialog |
| Products / categories | ✅ | ✅ | Dual DataTables + Dialogs |
| Calendar | ✅ | 🟡 | `employer/calendar` + shared panel |
| Restaurant tables | ✅ `employe/table/*` | ❌ | |
| Caisse / invoice returns | ✅ | ❌ | API has cancel only |

### Cross-cutting

| Feature | V1 | V2 |
|---------|----|----|
| Guest marketing site | ✅ `GuestController` | ❌ |
| CMI payments | Blade checkout + callback | API `POST /payments/cmi/callback` |
| Scheduled jobs | `birthday:campaign` cron | Console commands + DB queue in Docker |
| i18n | `resources/lang` | `lib/i18n/messages/{en,fr,ar}.ts` |
| Real-time (Pusher) | ✅ dependency | ❌ not implemented |

### V2 web routes in repository

All nav targets in `apps/nexa-web/lib/nav-config.ts` have corresponding `page.tsx` files in git. Recent UX upgrades (commit `7431639`) applied **DataTable + Dialog + safe row actions** to:

- `admin/owners`, `admin/activities`
- `employer/customers`, `employer/products`
- `owner/stores`, `owner/campaigns`, `owner/cross-selling`
- Shared `components/calendar/appointments-panel.tsx`
- `employer/invoices` (DataTable row actions)

Other list pages may still use earlier table patterns until migrated to the same convention (see `.cursor/rules/nexa-web-ui.mdc`).

---

## 3. API surface

### V1

- `legacy/v1/routes/api.php` — routes commented out; no live API.
- Business logic only via web controllers under `legacy/v1/app/Http/Controllers/Web/`.

**Example V1 web routes (representative):**

| Area | Examples |
|------|----------|
| Admin owners | `GET admin/owners`, `POST admin/owners/store` |
| Employer POS | `GET employe/invoices/create`, `POST employe/invoices/store` |
| Owner billing | `POST owner/process`, `POST owner/callback` |
| Campaigns | `PUT admin/campaign/activate`, `POST admin/campaign/reset` |

### V2 — `/api/v1` map

Source: `apps/nexa-api/routes/api.php`

| Group | Endpoints |
|-------|-----------|
| Health | `GET /health` |
| Auth | `POST /auth/login`, `logout`; `GET /auth/me` |
| Stores | CRUD `/stores` |
| Customers | CRUD `/customers` |
| Categories / products | CRUD `/categories`, `/products` |
| Campaigns | `GET /campaigns`; CRUD `/campaign-details`; `GET /campaign-historiques`, `/campaign-details/filter-defaults` |
| Cross-selling | CRUD `/cross-products`; `GET /sms-cross-selling` |
| Calendar | CRUD `/rdvs`; `GET /epingles` |
| Dashboard | `GET /dashboard/kpis` (includes `charts` time series) |
| Reports | `GET /reports/{sales,customers,products}`; `GET /reports/{type}/export` |
| Payments | `preview`, `checkout`, `index`, `show`; public `POST /payments/cmi/callback` |
| POS / invoices | `GET /pos/catalog`; invoice list/create/show/print/cancel |
| Admin only | CRUD `/owners` (+ activate/deactivate); CRUD `/activities` |

### V1 flows without V2 API equivalent

- Employee (employer) admin CRUD
- Email templates and newsletter campaigns
- Admin manual SMS, campaign `reset` / `payAll` / `credit`
- Restaurant table management
- Product Excel import, quantity history endpoints
- Invoice update, partial payment, returns
- Owner objectives, caisse, initial fonds
- Guest / marketing pages

---

## 4. UI/UX differences

| Aspect | V1 | V2 |
|--------|----|----|
| Rendering | Server-side Blade | Client React (`"use client"` pages) |
| Layouts | Multiple legacy masters (`layouts/admin`, `employe`, …) | Unified dashboard shell + sidebar |
| Tables | HTML tables in Blade | TanStack Table + shadcn `Table` |
| Row actions | Inline buttons/dropdowns in Blade | `DataTableRowActionsMenu` (avoids table + Base UI crashes) |
| Create / edit | Full-page forms, inline cards | Dialog (create) / Sheet (complex edit) |
| Dashboard | Static or jQuery charts | KPI card grid + Recharts (sales, customers, appointments; admin owner donut) |
| Icons | Mixed legacy assets | `lucide-react` |
| Theme | Fixed admin themes | Light / dark / system |
| i18n | Session locale | Typed EN / FR / AR message files |
| Invoice print | Blade print views | Shared `invoice-print-view.tsx` + print routes |

---

## 5. Security and maintainability

| Area | V1 (see [limitations](./improvement/v1-known-limitations.md)) | V2 |
|------|---------------------------------------------------------------|-----|
| Framework | Laravel 5.7 / PHP 7.4 EOL | Laravel 12, PHP 8.2+ |
| Authorization | Middleware + flags; empty policy layer | Policies per resource; `StoreAccess` |
| Mass assignment / IDOR | Patched on V1 branches historically | Store-scoped queries + feature tests |
| API | N/A | Throttled login; JSON errors; CORS/Sanctum config |
| Schema | Weak FKs on hot paths | V2 migrations with FKs and indexes |
| Queues | Often synchronous | `QUEUE_CONNECTION=database` in Docker |
| XSS | `SafeHtml` on some outputs | API Resources; React escaping |
| CI | V1 PHPUnit (`v1-ci.yml`) | Pint, API tests, ESLint, Next build, Playwright (`v2-ci.yml`) |

For a deep V1-only security review, see [technical-audit-report.md](./technical-audit-report.md) and [v1-security-and-maintenance.md](./improvement/v1-security-and-maintenance.md).

---

## 6. Data model and migration

| Item | V1 | V2 |
|------|----|----|
| Migrations | `legacy/v1/database/migrations/` (~34) | `apps/nexa-api/database/migrations/` (ported schema, `2026_06_09_*`) |
| Strategy | Production schema | Shared MySQL during cutover; `migrate:fresh` + compare to V1 dump |
| Seed | Legacy seeders | `NexaDemoSeeder` + reference seeders |
| POS tokens | Legacy column | Migration runbook: [pos-token-migration.md](./runbooks/pos-token-migration.md) |

**V1 tables without clear V2 API coverage:** `tables`, `table_products`, `objectives`, `templates`, `initial_fonds`, `fonds`, `email_campaigns`, `product_hestorique`, `product_quantites`, `invoices_paiements` (partial payment flows).

**Acceptance:** Formal schema parity sign-off (V1 production dump vs V2 `migrate:fresh`) is documented in the rebuild plan but should be completed on staging before cutover.

---

## 7. DevOps and branches

| Item | V1 | V2 |
|------|----|----|
| Local dev | `cd legacy/v1 && php artisan serve` | `docker compose up` — API `:8000`, web `:3000`, MySQL `:3306` |
| Hot reload | Manual | Bind mounts + `docker compose watch` on lockfiles |
| CI workflow | `.github/workflows/v1-ci.yml` | `.github/workflows/v2-ci.yml` |
| Deploy runbook | [deployment-v1.md](./runbooks/deployment-v1.md) | [deployment-v2.md](./runbooks/deployment-v2.md) |
| Integration branch | `main` (V1 production history) | `v2/main` |
| npm in Docker | N/A | After `package.json` changes: `docker compose run --rm web npm ci` |

---

## 8. Recent V2 deliverables (commit `7431639`)

The following landed on `v2/main` and should be included in parity testing:

1. **Rebrand:** `sherin-api` / `sherin-web` → `nexa-api` / `nexa-web`; Nexa assets in legacy V1 views.
2. **Dashboard:** `DashboardService` KPIs + 7-day chart series; Recharts UI.
3. **List UX:** DataTable + Dialog pattern on key admin/owner/employer pages.
4. **Row actions:** Shared `data-table-row-actions-menu.tsx` pattern.
5. **Admin settings:** `/admin/settings` (locale/theme); nav fix (Settings no longer points to dashboard).
6. **Dependency:** `recharts` added to `nexa-web` (refresh Docker `node_modules` volume after pull).

---

## 9. Recommended next steps

> **Implementation plan:** See [v2-critical-high-priority-plan.md](./improvement/v2-critical-high-priority-plan.md) for phased tasks, test matrix, PR order, and sign-off checklist.

### Critical

1. **Staging parity pass** — Production DB copy; walk admin / owner / employer flows against [v2-platform-rebuild.md](./improvement/v2-platform-rebuild.md) checklist.
2. **Employer money path** — Invoice create → list → print → cancel on staging with CMI sandbox.
3. **Uniform list UX** — Migrate remaining list pages to DataTable + Dialog (admin stores/customers/products/payments, owner customers/products/invoices, etc.).

### High priority

4. **Product Excel import** — Last major catalog parity item (W5 in rebuild plan).
5. **Scheduler parity** — Birthday, cross-selling, RDV reminders on cron + queue workers in staging/prod.
6. **Production config** — Sanctum domains, CORS, `FRONTEND_URL`, CMI callback URLs ([deployment-v2.md](./runbooks/deployment-v2.md)).

### Product decisions

7. **V1-only scope** — Employees, restaurant tables, caisse/fonds: port, defer, or officially drop for V2 launch.
8. **Campaign admin ops** — Whether V2 needs `reset` / `payAll` / `credit` or a simplified billing model.

### Sign-off criteria

- [ ] All nav items load without error for each role
- [ ] Employer can complete a sale end-to-end
- [ ] Owner can pay subscription via CMI on staging
- [ ] Reports export matches V1 expectations for sales/customers/products
- [ ] Schema diff V1 vs V2 reviewed on staging data
- [ ] WCAG spot-check on primary flows (W6-4 in rebuild plan)

---

## 10. References

| Document | Purpose |
|----------|---------|
| [technical-audit-report.md](./technical-audit-report.md) | Deep V1 security and code-quality audit |
| [improvement/v2-platform-rebuild.md](./improvement/v2-platform-rebuild.md) | Week-by-week rebuild checklist |
| [improvement/v1-known-limitations.md](./improvement/v1-known-limitations.md) | V1 known limitations (L-01–L-09) |
| [v2-local-setup.md](./v2-local-setup.md) | Local Docker setup |
| [improvement/v2-github-execution-plan.md](./improvement/v2-github-execution-plan.md) | PR stacking and branch strategy |

---

*This audit reflects repository state at commit `7431639` on branch `v2/main`. Update the reference commit when major parity milestones land.*
