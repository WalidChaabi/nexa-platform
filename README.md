# Nexa Platform

[![V2 CI](https://github.com/WalidChaabi/nexa-platform/actions/workflows/v2-ci.yml/badge.svg)](https://github.com/WalidChaabi/nexa-platform/actions/workflows/v2-ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com/)
[![Next.js](https://img.shields.io/badge/Next.js-16-black.svg)](https://nextjs.org/)

Multi-store retail and POS platform — campaigns, payments, reporting, and team management for business owners and store staff.

**Author:** [Walid Chaabi](https://github.com/WalidChaabi)

---

## Project focus

This portfolio project demonstrates a Laravel backend connected to a Next.js interface for multi-store retail operations.

- **Business workflows:** products, customers, POS invoices, campaigns and reporting.
- **Backend engineering:** REST endpoints, Sanctum authentication, policies and request validation.
- **Frontend engineering:** separate workspaces for platform administrators, business owners and store staff.
- **Modernization:** an API-first V2 alongside a legacy Laravel reference.

## Technical decisions

- Keep the API and web app separate so business rules and authorization live in the backend.
- Use role-based workspaces to make each user's responsibilities explicit.
- Retain the legacy reference separately from active development to support domain comparisons.
- Combine backend feature tests with browser tests; see the commands below and the linked CI workflow for verification.

## Suggested review path

Start with the V2 architecture below, then inspect the API policies and feature tests, followed by the web workspaces. Use the local setup guide to explore the interface. Release hardening remains in progress; the status section links the checklist.

## Highlights

- **API-first architecture** — Laravel 12 REST API + Next.js 16 web app (Sanctum auth, policies, form requests)
- **Role-based workspaces** — Platform admin, business owner, and store employer portals
- **Core retail flows** — POS invoices, product catalog, customers, campaigns, cross-selling, calendar
- **Quality gates** — PHPUnit feature tests, Playwright E2E, Laravel Pint, ESLint, Docker dev stack
- **Trilingual UI** — English, French, and Arabic (RTL)

> **Note:** `legacy/v1/` is a reference Laravel 5.7 monolith kept for domain parity only. Active development lives in `apps/nexa-api` and `apps/nexa-web`.

---

## Architecture

| Track | Path | Stack | Status |
|-------|------|-------|--------|
| **V2** (current) | `apps/nexa-api`, `apps/nexa-web` | Laravel 12 + Next.js 16 | Active development |
| **V1** (legacy) | `legacy/v1` | Laravel 5.7 monolith | Reference only |

```
nexa-platform/
├── apps/
│   ├── nexa-api/          # Laravel 12 REST API
│   └── nexa-web/          # Next.js 16 web app
├── legacy/v1/             # Legacy reference (not actively developed)
├── docs/                  # Audits, ADRs, runbooks, GA plan
├── infra/                 # Docker Compose
└── docker-compose.yml
```

---

## Quick start (V2)

**Prerequisites:** Docker, or PHP 8.2+, Composer 2, Node.js 20+, and MySQL 8.

```bash
git clone https://github.com/WalidChaabi/nexa-platform.git
cd nexa-platform
docker compose up -d
```

| Service | URL |
|---------|-----|
| Web UI | http://localhost:3000 |
| API health | http://localhost:8000/api/v1/health |
| MySQL | `localhost:3306` — database `nexa_test`, user `me` / password `ok` |

Full setup without Docker: [docs/v2-local-setup.md](docs/v2-local-setup.md).

---

## Tech stack

| Layer | Technologies |
|-------|----------------|
| API | Laravel 12, PHP 8.2+, Sanctum, Policies, MySQL |
| Web | Next.js 16, React 19, Tailwind 4, shadcn/ui, TanStack Table, Recharts |
| Testing | PHPUnit, Playwright |
| Ops | Docker Compose, GitHub Actions |

---

## Documentation

| Topic | Location |
|-------|----------|
| V2 local development | [docs/v2-local-setup.md](docs/v2-local-setup.md) |
| Product audit & roadmap | [docs/nexa-product-audit-and-roadmap.md](docs/nexa-product-audit-and-roadmap.md) |
| v1.0 GA implementation plan | [docs/improvement/nexa-v1-ga-implementation-plan.md](docs/improvement/nexa-v1-ga-implementation-plan.md) |
| V1 vs V2 comparison | [docs/technical-audit-v1-v2.md](docs/technical-audit-v1-v2.md) |
| ADR: API-first UI | [docs/adr/002-nextjs-api-first-ui.md](docs/adr/002-nextjs-api-first-ui.md) |

Per-app READMEs: [nexa-api](apps/nexa-api/README.md) · [nexa-web](apps/nexa-web/README.md)

---

## Development

```bash
# API tests + style
cd apps/nexa-api && composer test && vendor/bin/pint --test

# Web lint + build
cd apps/nexa-web && npm run lint && npm run build

# E2E (API + web must be running)
cd apps/nexa-web && npm run test:e2e
```

CI workflow: [.github/workflows/v2-ci.yml](.github/workflows/v2-ci.yml)

---

## Status

Portfolio showcase project — V2 is feature-rich with ongoing v1.0 GA hardening (production Docker, subscriptions, expanded E2E). See the [GA implementation plan](docs/improvement/nexa-v1-ga-implementation-plan.md) for the release checklist.

---

## License

MIT — see [LICENSE](LICENSE).
