# Phone Store (Laravel 12)

A complete Laravel project: phone shop with admin and customer roles, bookings, stock, suppliers, coupons,
payments, reviews, wishlist, accessories, banners and picture uploads. 17 database tables, each with a
migration, model, factory and seeder.

## Run it (Windows)

Needs PHP 8.2 or newer (with `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`) and Composer.

1. Unzip, then in VS Code use File > Open Folder and pick this `phone-store` folder.
   You should see `artisan` and `composer.json` in the Explorer.
2. Open a terminal in that folder and run:

        setup.bat

   That installs the packages (`vendor` is created by Composer), builds the database, links the pictures and starts the server.
3. Open http://127.0.0.1:8000

Or run the steps yourself:

    composer install
    php artisan migrate:fresh --seed
    php artisan storage:link
    php artisan serve

Logins (password `password`): `admin@example.com` (admin) and `user@example.com` (customer).

The database is the SQLite file `database/database.sqlite`. To use MySQL instead, edit the `DB_` lines in `.env`.
`php artisan migrate:fresh --seed` wipes the database and loads the demo data again.

## Roles

| Role  | Can do |
|-------|--------|
| Guest | Browse and search phones and accessories, read reviews, send a contact message |
| User  | Everything a guest can, plus book phones (with coupons), cancel pending bookings, review phones, keep a wishlist, edit a profile and picture |
| Admin | Dashboard, daily bookings, payments, stock (phone CRUD with pictures and specs), stock log, supplier purchases, suppliers, categories, coupons, accessories, banners, customer messages, reports |

## Tables

users, customer_profiles, categories, phones, phone_specs, suppliers, purchases, purchase_items, stock_movements,
coupons, bookings, payments, reviews, wishlists, contact_messages, accessories, banners.

## Rules the app enforces

- Placing a booking takes the units out of stock and writes a stock log line. Cancelling returns them.
- Completed and cancelled bookings cannot change again.
- Receiving a supplier purchase adds stock and writes a stock log line.
- A coupon must be active, not expired, and under its max uses.
- Pictures must be JPG, PNG or WEBP, up to 2 MB. Replacing, removing or deleting also deletes the old file.

## Commands

    php artisan store:make-admin someone@example.com
    php artisan store:low-stock --under=5
    php artisan test

Tests use an in-memory SQLite database, so your real data is safe.
