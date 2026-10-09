<?php
namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    public function run(): void
    {
        if (Accessory::count()) return;
        foreach ([
            ['Clear Case', 'case', 9, 40, 'clear-case'], ['Leather Case', 'case', 19, 25, 'leather-case'],
            ['Fast Charger 30W', 'charger', 25, 30, 'fast-charger'], ['USB-C Cable', 'cable', 8, 60, 'usb-c-cable'],
            ['Wireless Earbuds', 'headphones', 59, 15, 'wireless-earbuds'], ['Screen Protector', 'protector', 7, 80, 'screen-protector'],
            ['Power Bank 10000', 'charger', 29, 18, 'power-bank'], ['Car Phone Holder', 'other', 12, 22, 'car-holder'],
        ] as [$n, $t, $p, $s, $slug]) {
            Accessory::create(['name' => $n, 'type' => $t, 'price' => $p, 'stock' => $s, 'description' => "$n for most phones.", 'image' => SeedImage::put('accessories', $slug)]);
        }
    }
}
