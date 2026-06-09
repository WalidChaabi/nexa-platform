# Nexa API (V2)

Laravel API for Nexa. All business logic, integrations, and background jobs live here.

**Stack:** Laravel 12, PHP 8.2+, Sanctum, MySQL 8.

## Run locally

### With Docker (recommended)

From the repository root:

```bash
docker compose up -d
```

API: http://localhost:8000/api/v1/health

### Without Docker

```bash
cd apps/nexa-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

Configure `.env` for your database. With Docker MySQL only (`docker compose up -d database`):

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexa_test
DB_USERNAME=me
DB_PASSWORD=ok
FRONTEND_URL=http://localhost:3000
```

## Demo data

After seeding, demo accounts are available:

| Role | Email |
|------|-------|
| Admin | `admin@nexa.test` |
| Owner | `owner@nexa.test` |
| Employer | `employer@nexa.test` |

Password for all demo accounts: **`password`**

## Tests

```bash
php artisan test
```

CI runs on PRs touching `apps/nexa-api/**` (PHP 8.3, SQLite).

## Configuration

Nexa-specific settings: `config/nexa.php` (TVA rate, SMS/email pricing, etc.).

## Related

- Web UI: [apps/nexa-web](../nexa-web/README.md)
- Full stack setup: [docs/v2-local-setup.md](../../docs/v2-local-setup.md)
