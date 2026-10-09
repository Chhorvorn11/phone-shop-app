<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;

/** Copies a sample picture from database/seeders/images into the public disk. */
class SeedImage
{
    public static function put(string $dir, string $name): ?string
    {
        $src = database_path("seeders/images/$name.svg");
        if (! is_file($src)) return null;
        $path = "$dir/seed-$name.svg";
        Storage::disk('public')->put($path, file_get_contents($src));
        return $path;
    }
}
