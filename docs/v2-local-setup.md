# Nexa — V2 local development setup

## Prerequisites

- PHP 8.2+ (8.3+ recommended for API; CI uses 8.3)
- Composer 2
- Node.js 20+
- Docker (optional, for MySQL)

## Monorepo layout

```
legacy/v1/          # Laravel 5.7 (maintenance)
apps/sherin-api/    # Laravel API (Nexa)
apps/sherin-web/    # Next.js (Nexa)
```

## Full stack in Docker (recommended)

From repository root:

```bash
docker compose up -d
# or, with auto-rebuild when composer/package files change:
docker compose watch
```

| Service | URL |
|---------|-----|
| Web | http://localhost:3000 |
| API | http://localhost:8000/api/v1/health |
| MySQL | `localhost:3306` — db `nexa_test`, user `me` / password `ok` |

### Hot reload (no rebuild for code edits)

Source is **bind-mounted** into the `api` and `web` containers. After the stack is up once:

- **Web (Next.js):** save a file under `apps/sherin-web/` → dev server recompiles → refresh the browser.
- **API (Laravel):** save a file under `apps/sherin-api/` → next request uses the new code.

You only need to **rebuild images** when Dockerfiles or lockfiles change:

```bash
docker compose build api web
docker compose up -d
```

If Next.js stops picking up new routes (404 after adding pages), clear the dev cache volume once:

```bash
docker compose down
docker volume rm nexa-platform_web-next
docker compose up -d
```

On Windows, polling env vars are set in `docker-compose.yml` so file watching works through Docker bind mounts.

## MySQL only (optional)

```bash
docker compose up -d database
```

Default: database `nexa_test`, user `me` / password `ok`, port `3306`.

## API (`apps/sherin-api`)

```bash
cd apps/sherin-api
composer install
cp .env.example .env
```

Configure `.env`:

```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexa_test
DB_USERNAME=me
DB_PASSWORD=ok
FRONTEND_URL=http://localhost:3000
```

```bash
php artisan key:generate
php artisan serve
```

Verify: http://localhost:8000/api/v1/health

## Web (`apps/sherin-web`)

```bash
cd apps/sherin-web
npm install
cp .env.example .env.local
npm run dev
```

Verify: http://localhost:3000

## Legacy V1

```bash
cd legacy/v1
composer install
cp .env.example .env
php artisan serve --port=8080
```

Use a separate DB name or port if running V1 and V2 API together.
