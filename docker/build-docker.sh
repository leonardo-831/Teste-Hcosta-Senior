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
