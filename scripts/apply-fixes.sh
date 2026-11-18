#!/bin/sh
# Instructor/after-lab helper: copies the reference fixes from ./solutions into the app,
# disables debug mode and re-runs the acceptance tests. Run from the project root on the HOST.
set -e

cp solutions/ProjectController.php  app/Http/Controllers/ProjectController.php
cp solutions/DocumentController.php app/Http/Controllers/DocumentController.php
cp solutions/TeamController.php     app/Http/Controllers/TeamController.php
cp solutions/ProjectPolicy.php      app/Policies/ProjectPolicy.php
cp solutions/StoreDocumentRequest.php app/Http/Requests/StoreDocumentRequest.php

# R05 configuration fix inside the running container
docker compose exec app sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env
docker compose exec app php artisan config:clear

docker compose exec app php artisan test --filter=SecurityAcceptanceTest
