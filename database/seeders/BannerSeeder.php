<?php
namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        if (Banner::count()) return;
        foreach ([
            ['New arrivals', 'Fresh stock of flagship phones this week', 'new-arrivals', 1],
            ['Student deal', 'Use code WELCOME10 when you book', 'student-deal', 2],
            ['Accessories', 'Cases, chargers and earbuds', 'accessories-sale', 3],
        ] as [$title, $sub, $slug, $order]) {
            Banner::create(['title' => $title, 'subtitle' => $sub, 'image' => SeedImage::put('banners', $slug), 'sort_order' => $order, 'active' => true]);
        }
    }
}
