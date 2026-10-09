@echo off
echo === Phone Store setup ===
call composer install
if not exist database\database.sqlite type nul > database\database.sqlite
call php artisan config:clear
call php artisan migrate:fresh --seed --force
call php artisan storage:link
echo.
echo Done. Open http://127.0.0.1:8000  (admin@example.com or user@example.com, password: password)
php artisan serve
