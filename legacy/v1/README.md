# Sherin V1 (legacy)

Laravel **5.7** production application. Maintenance-only until V2 cutover.

## Run locally

```bash
cd legacy/v1
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

PHPUnit targets PHP **7.4** in CI. Local PHP 8.x may fail on legacy tests.

## Deploy

This path is the current production line until V2 replaces it. See `docs/runbooks/deployment-v1.md`.
