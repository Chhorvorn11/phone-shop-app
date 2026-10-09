<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Flagship' => 'flagship', 'Mid-range' => 'mid-range', 'Budget' => 'budget'] as $name => $slug) {
            $c = Category::firstOrCreate(['name' => $name]);
            if (! $c->image) $c->update(['image' => SeedImage::put('categories', $slug)]);
        }
    }
}
