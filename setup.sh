#!/bin/sh
composer install
[ -f database/database.sqlite ] || touch database/database.sqlite
php artisan config:clear
php artisan migrate:fresh --seed --force
php artisan storage:link || true
echo "Open http://127.0.0.1:8000  (admin@example.com or user@example.com, password: password)"
php artisan serve
