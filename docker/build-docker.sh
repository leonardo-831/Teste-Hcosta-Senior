#!/bin/bash

cd "$(dirname "$0")/.."

if [ ! -f .env ]; then
  cp .env.example .env
fi

sed -i '/^UID=/d' .env
sed -i '/^GID=/d' .env

echo "UID=$(id -u)" >> .env
echo "GID=$(id -g)" >> .env

docker compose up -d --build

if [ ! -d "vendor" ]; then
  docker compose exec app composer install --no-dev --optimize-autoloader
fi

if ! grep -q '^APP_KEY=base64:' .env; then
  docker compose exec app php artisan key:generate
fi

if grep -q '^JWT_SECRET=' .env && ! grep -q '^JWT_SECRET=base64:' .env; then
  docker compose exec app php artisan jwt:secret
fi