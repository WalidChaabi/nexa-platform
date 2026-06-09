# Nexa V1 (legacy)

Laravel **5.7** production application. Maintenance-only until V2 cutover.

This is the original Nexa monolith (historically deployed as Sherin). No new features; security fixes and critical bugs only.

## Run locally

```bash
cd legacy/v1
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

PHPUnit targets PHP **7.4** in CI. Local PHP 8.x may fail on legacy tests.

Use a separate database name or port if running V1 alongside the V2 API (`apps/sherin-api`).

## Deploy

This path is the current production line until V2 replaces it. See [docs/runbooks/deployment-v1.md](../../docs/runbooks/deployment-v1.md).

## V2 replacement

Active development lives in:

- `apps/sherin-api` — Laravel API
- `apps/sherin-web` — Next.js web UI

See [docs/v2-local-setup.md](../../docs/v2-local-setup.md) and [docs/improvement/README.md](../../docs/improvement/README.md).
