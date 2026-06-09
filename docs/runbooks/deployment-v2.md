# Runbook: V2 Deployment

Production and staging deployments for the Nexa V2 stack (`v2/main` branch):

- `apps/nexa-api` — Laravel 11 API
- `apps/nexa-web` — Next.js 16 web UI

---

## Prerequisites

- PHP 8.3+, Composer 2
- Node 20+ (web build)
- MySQL 8
- Redis (recommended for queues and cache in production)
- CMI credentials for payment checkout (staging vs production)

---

## Pre-deploy checklist

- [ ] PR merged and `v2-ci` workflow green
- [ ] `apps/nexa-api/.env.example` reviewed for new variables
- [ ] Migrations reviewed (`apps/nexa-api/database/migrations/`)
- [ ] Scheduler + queue worker plan confirmed (see below)
- [ ] Rollback tag noted

---

## API deploy steps

```bash
cd apps/nexa-api
git pull origin v2/main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

Restart PHP-FPM (or reload the API container) after deploy.

---

## Web deploy steps

```bash
cd apps/nexa-web
git pull origin v2/main
npm ci
npm run build
```

Restart the Next.js process (or redeploy the web container). Set `NEXT_PUBLIC_API_URL` to the public API origin.

### Web Docker image (production)

`apps/nexa-web/Dockerfile` is a multi-stage build (`next build` + standalone `node server.js`). Local dev still uses `Dockerfile.dev` via root `docker-compose.yml`.

```bash
# From repo root — production-like stack (no bind mounts)
export NEXT_PUBLIC_API_URL=https://api.example.com
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build

# Or build/push only the web image
docker build \
  --build-arg NEXT_PUBLIC_API_URL=https://api.example.com \
  -t nexa-web:latest \
  ./apps/nexa-web
```

Runtime env for the web container:

| Variable | Purpose |
|----------|---------|
| `NEXT_PUBLIC_API_URL` | Browser-facing API origin (baked at build time for client bundles; set again at runtime for server fetches if needed) |
| `API_INTERNAL_URL` | Server-side API origin inside Docker network (e.g. `http://api:8000` for middleware / RSC) |

---

## Scheduler (required for campaigns)

V2 registers three Nexa commands in `apps/nexa-api/routes/console.php`:

| Command | Schedule | Purpose |
|---------|----------|---------|
| `nexa:birthday-campaign` | Daily 12:00 | Create unpaid birthday SMS billing rows |
| `nexa:cross-selling` | Daily 09:00 | Queue cross-selling SMS for past purchases |
| `nexa:rendez-vous` | Hourly | SMS reminders for appointments in the next 30 minutes |

All schedules use timezone **`Africa/Casablanca`**.

**Cron on the API host** (one entry — Laravel dispatches due tasks):

```cron
* * * * * cd /path/to/nexa-platform/apps/nexa-api && php artisan schedule:run >> /var/log/nexa-schedule.log 2>&1
```

Verify after deploy:

```bash
php artisan schedule:list
php artisan nexa:birthday-campaign
php artisan nexa:cross-selling
php artisan nexa:rendez-vous
```

---

## Queue worker (required for SMS jobs)

Campaign SMS is dispatched via `SendCampaignSmsJob`. Without a worker, jobs sit in the queue.

**`.env` (production):**

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Supervisor example** (`/etc/supervisor/conf.d/nexa-queue.conf`):

```ini
[program:nexa-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/nexa-platform/apps/nexa-api/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/nexa-queue.log
```

After config change: `supervisorctl reread && supervisorctl update && supervisorctl start nexa-queue:*`

**Docker Compose (dev):** the `nexa_api` service entrypoint can run `queue:work` in a sidecar or override — ensure one container processes the queue when testing SMS locally.

---

## Post-deploy smoke test

1. API health: `GET /up` or `GET /api/v1/auth/user` (authenticated)
2. Web login: admin, owner, employer — correct dashboard redirect
3. Employer: create invoice, print receipt
4. Owner: list campaigns, toggle active
5. Owner: payment checkout preview (staging CMI)
6. Scheduler: `php artisan schedule:list` shows three `nexa:*` tasks

---

## Rollback

```bash
git checkout <previous-tag>
# API
composer install --no-dev && php artisan migrate --force && php artisan config:cache
# Web
npm ci && npm run build
```

Restore DB backup only if a migration was destructive.

---

## Related docs

- [V2 local setup](../v2-local-setup.md)
- [V1 deployment](./deployment-v1.md) (legacy maintenance)
- [POS token migration](./pos-token-migration.md)
