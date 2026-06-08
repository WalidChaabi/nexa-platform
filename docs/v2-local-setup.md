# V2 local development setup

## Prerequisites

- PHP 8.2+ (8.3+ recommended for API; CI uses 8.3)
- Composer 2
- Node.js 20+
- Docker (optional, for MySQL)

## Monorepo layout

```
legacy/v1/          # Laravel 5.7 (maintenance)
apps/sherin-api/    # Laravel API
apps/sherin-web/    # Next.js
```

## MySQL (shared dev)

From repository root:

```bash
docker compose up -d
```

Default: database `sherin_test`, user `me` / password `ok`, port `3306`.

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
DB_DATABASE=sherin_test
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
