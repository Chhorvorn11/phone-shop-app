<?php
namespace App\Models;

use App\Models\Concerns\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasPicture;

    protected $fillable = ['name', 'description', 'image'];

    public function phones() { return $this->hasMany(Phone::class); }
}
