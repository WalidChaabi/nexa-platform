#!/bin/sh
set -e

cd /var/www/html

composer install --no-interaction --prefer-dist

if [ ! -f .env ]; then
  cp .env.example .env
fi

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

exec php artisan serve --host=0.0.0.0 --port=8000
