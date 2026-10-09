<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Order matters: parents before the tables that point to them. */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class, UserSeeder::class, SupplierSeeder::class, CouponSeeder::class,
            PhoneSeeder::class, PhoneSpecSeeder::class, CustomerProfileSeeder::class,
            BookingSeeder::class, PaymentSeeder::class,
            PurchaseSeeder::class, StockMovementSeeder::class,
            ReviewSeeder::class, WishlistSeeder::class, ContactMessageSeeder::class,
            AccessorySeeder::class, BannerSeeder::class,
        ]);
    }
}
