# Runbook: V1 Deployment

Maintenance deployments for the Laravel 5.7 production application (`main` branch).

---

## Prerequisites

- PHP 7.4+
- Composer 2
- MySQL 8
- Node 14+ (only if rebuilding frontend assets)

---

## Pre-deploy checklist

- [ ] PR merged and CI green
- [ ] `.env.example` reviewed for new variables
- [ ] Migrations reviewed (if any)
- [ ] Rollback tag noted: `git tag` previous release
- [ ] Secret rotation completed if security PR

---

## Deploy steps

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force          # only if migrations included
php artisan sherin:migrate-pos-tokens   # first deploy with POS hash column; see pos-token-migration.md
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache               # optional on 5.7
```

Restart PHP-FPM / queue workers if applicable.

---

## Post-deploy smoke test

1. Guest homepage loads (`/`)
2. Login as owner, employer, admin
3. Create or view invoice (employer)
4. Campaign list loads (owner)
5. Payment history (if payment PR)

---

## Rollback

```bash
git checkout v1.x.y-previous
composer install --no-dev
php artisan config:cache
```

Restore DB backup only if migration was destructive.

---

## Cron (when scheduler enabled)

```cron
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

Timezone: `Africa/Casablanca` for campaign commands.
