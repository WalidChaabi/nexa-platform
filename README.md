# Nexa Platform

Monorepo for **Nexa** — a multi-store retail and POS platform with campaigns, payments, and customer management.

| Track | Path | Stack | Status |
|-------|------|-------|--------|
| **V2** (current build) | `apps/nexa-api`, `apps/nexa-web` | Laravel 12 API + Next.js 16 web | Active development |
| **V1** (legacy) | `legacy/v1` | Laravel 5.7 monolith | Maintenance-only until V2 cutover |

## Quick start (V2)

**Prerequisites:** Docker, or PHP 8.2+, Composer 2, Node.js 20+, and MySQL 8.

From the repository root:

```bash
docker compose up -d
```

| Service | URL |
|---------|-----|
| Web UI | http://localhost:3000 |
| API health | http://localhost:8000/api/v1/health |
| MySQL | `localhost:3306` — database `nexa_test`, user `me` / password `ok` |

Full setup (migrations, seeding, local PHP/Node without Docker): [docs/v2-local-setup.md](docs/v2-local-setup.md).

## Repository layout

```
nexa-platform/
├── apps/
│   ├── nexa-api/     # Nexa API (Laravel)
│   └── nexa-web/     # Nexa Web (Next.js)
├── legacy/v1/          # Legacy production app (Laravel 5.7)
├── docs/               # Setup guides, ADRs, runbooks, improvement program
└── docker-compose.yml  # Local V2 stack (MySQL + API + web)
```

## Documentation

| Topic | Location |
|-------|----------|
| V2 local development | [docs/v2-local-setup.md](docs/v2-local-setup.md) |
| V1 deployment | [docs/runbooks/deployment-v1.md](docs/runbooks/deployment-v1.md) |
| Improvement program (V1 + V2 strategy) | [docs/improvement/README.md](docs/improvement/README.md) |
| Technical audit | [docs/technical-audit-report.md](docs/technical-audit-report.md) |

## Per-app READMEs

- [Nexa API](apps/nexa-api/README.md)
- [Nexa Web](apps/nexa-web/README.md)
- [Nexa V1 (legacy)](legacy/v1/README.md)
