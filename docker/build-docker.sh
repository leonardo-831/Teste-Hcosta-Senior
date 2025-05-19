#!/bin/bash

cd "$(dirname "$0")/.."

if [ ! -f .env ]; then
  cp .env.example .env
fi

sed -i '/^UID=/d' .env
sed -i '/^GID=/d' .env

echo "" >> .env
echo "UID=$(id -u)" >> .env
echo "GID=$(id -g)" >> .env

mkdir -p storage/logs/supervisor
mkdir -p storage/logs/workers
chmod -R 755 storage/logs/supervisor storage/logs/workers
chown -R "$USER":"$USER" storage/logs/supervisor storage/logs/workers

docker compose up -d --build

if [ ! -d "vendor" ]; then
  docker compose exec app composer install --optimize-autoloader --no-interaction --no-progress
fi

if ! grep -q '^APP_KEY=base64:' .env; then
  docker compose exec app php artisan key:generate
fi

if grep -q '^JWT_SECRET=' .env && ! grep -q '^JWT_SECRET=base64:' .env; then
  docker compose exec app php artisan jwt:secret
fi

docker compose exec app php artisan migrate --seed --force