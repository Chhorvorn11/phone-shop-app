<?php
namespace App\Models;

use App\Models\Concerns\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory, HasPicture;

    public const TYPES = ['case', 'charger', 'cable', 'headphones', 'protector', 'other'];

    protected $fillable = ['name', 'type', 'price', 'stock', 'description', 'image'];
}
