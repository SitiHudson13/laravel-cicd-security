#!/bin/sh
# Reset the database and seeded documents to the original training state (code is unchanged).
docker compose exec app php artisan migrate:fresh --seed --force
