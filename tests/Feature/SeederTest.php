<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Storage::fake('public'); // seeded pictures go to a fake disk
    }

    public function test_database_seeder_fills_every_table(): void
    {
        $this->seed();

        foreach (['users', 'categories', 'suppliers', 'coupons', 'phones', 'phone_specs', 'customer_profiles', 'bookings',
                  'payments', 'purchases', 'purchase_items', 'stock_movements', 'reviews', 'wishlists', 'contact_messages', 'accessories', 'banners'] as $table) {
            $this->assertGreaterThan(0, DB::table($table)->count(), "$table is empty after seeding");
        }
        $this->assertSame(1, DB::table('users')->where('role', 'admin')->count());
        $this->assertNotNull(DB::table('phones')->value('image'));
        $this->assertNotNull(DB::table('accessories')->value('image'));
    }

    public function test_seeding_twice_does_not_duplicate_data(): void
    {
        $this->seed();
        $counts = DB::table('phones')->count() + DB::table('bookings')->count() + DB::table('categories')->count();
        $this->seed();
        $this->assertSame($counts, DB::table('phones')->count() + DB::table('bookings')->count() + DB::table('categories')->count());
    }
}
