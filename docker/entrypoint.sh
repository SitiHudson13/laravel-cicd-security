#!/bin/sh
set -e
cd /var/www/html

# Configure environment on first start
if [ ! -f .env ] || ! grep -q "SecureProject" .env; then
    cp .env.lab .env
    php artisan key:generate --force
fi

mkdir -p database storage/app/private storage/framework/{cache,sessions,views} storage/logs
touch database/database.sqlite

# Rebuild the database and seed training data on every container start
php artisan migrate:fresh --seed --force

echo "------------------------------------------------------------"
echo " SecureProject training lab running on http://localhost:8000"
echo " Test accounts: see README.md (password: Lab-Passw0rd!)"
echo " WARNING: intentionally vulnerable - local training use only"
echo "------------------------------------------------------------"

exec php artisan serve --host=0.0.0.0 --port=8000
