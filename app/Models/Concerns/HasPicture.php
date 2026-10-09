<?php
namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/** Adds pictureUrl() and removes the file from disk when the record is deleted. */
trait HasPicture
{
    protected static function bootHasPicture(): void
    {
        static::deleting(fn ($model) => $model->deletePicture());
    }

    public function pictureColumn(): string
    {
        return 'image';
    }

    public function pictureUrl(): ?string
    {
        $path = $this->{$this->pictureColumn()};
        return $path ? asset('storage/'.$path) : null;
    }

    public function deletePicture(): void
    {
        $path = $this->{$this->pictureColumn()};
        if ($path) Storage::disk('public')->delete($path);
    }
}
