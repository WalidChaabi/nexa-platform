#!/bin/sh
set -e

cd /var/www/html

composer install --no-interaction --prefer-dist

if [ ! -f .env ]; then
  cp .env.example .env
fi

# Keep bind-mounted .env aligned with compose environment variables.
for key in APP_NAME DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
  val=$(printenv "$key" || true)
  if [ -n "$val" ] && grep -q "^${key}=" .env 2>/dev/null; then
    sed -i "s|^${key}=.*|${key}=${val}|" .env
  fi
done

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
  php artisan key:generate --force
fi

echo "Waiting for database..."
until php -r "
  try {
    new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD')
    );
    exit(0);
  } catch (Exception \$e) {
    exit(1);
  }
"; do
  sleep 2
done

php artisan migrate --force

if [ "$(php artisan tinker --execute='echo App\Models\User::count();')" = "0" ]; then
  echo "Seeding demo data..."
  php artisan db:seed --force
fi

exec php artisan serve --host=0.0.0.0 --port=8000
