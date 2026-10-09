<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Phone;
use Illuminate\Database\Seeder;

class PhoneSeeder extends Seeder
{
    public function run(): void
    {
        if (Phone::count()) return;
        foreach ([
            ['Flagship', 'Apple', 'iPhone 15', '128 GB', 799, 12, 'iphone-15'],
            ['Flagship', 'Samsung', 'Galaxy S24', '256 GB', 749, 8, 'galaxy-s24'],
            ['Flagship', 'OnePlus', '12', '256 GB', 699, 6, 'oneplus-12'],
            ['Mid-range', 'Google', 'Pixel 8', '128 GB', 599, 3, 'pixel-8'],
            ['Mid-range', 'Oppo', 'Reno 11', '256 GB', 399, 14, 'reno-11'],
            ['Budget', 'Xiaomi', 'Redmi Note 13', '128 GB', 229, 25, 'redmi-note-13'],
            ['Budget', 'Samsung', 'Galaxy A15', '128 GB', 179, 30, 'galaxy-a15'],
            ['Budget', 'Nokia', 'G42', '128 GB', 199, 2, 'nokia-g42'],
        ] as [$c, $b, $n, $s, $p, $st, $slug]) {
            Phone::create([
                'category_id' => Category::where('name', $c)->value('id'), 'brand' => $b, 'name' => $n,
                'storage' => $s, 'price' => $p, 'stock' => $st, 'image' => SeedImage::put('phones', $slug),
            ]);
        }
    }
}
