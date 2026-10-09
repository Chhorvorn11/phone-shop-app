<?php
namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Picture
{
    /** Validation rules for an image upload field (JPG, PNG or WEBP, up to 2 MB). */
    public static function rules(bool $required = false): array
    {
        return [$required ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
    }

    /**
     * Save a new upload (deleting the old file), or remove the picture when "remove_<field>" is ticked.
     * Returns the path to store in the database column.
     */
    public static function handle(Request $r, string $field, string $dir, ?string $current): ?string
    {
        if ($r->hasFile($field)) {
            if ($current) Storage::disk('public')->delete($current);
            return $r->file($field)->store($dir, 'public');
        }
        if ($r->boolean('remove_'.$field)) {
            if ($current) Storage::disk('public')->delete($current);
            return null;
        }
        return $current;
    }
}
