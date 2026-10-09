<?php
namespace App\Models;

use App\Models\Concerns\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, HasPicture;

    protected $fillable = ['title', 'subtitle', 'link_url', 'image', 'sort_order', 'active'];
    protected $casts = ['active' => 'boolean'];
}
