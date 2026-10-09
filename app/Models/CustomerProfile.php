<?php
namespace App\Models;

use App\Models\Concerns\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    use HasFactory, HasPicture;

    protected $fillable = ['user_id', 'phone_number', 'address', 'city', 'avatar'];

    public function pictureColumn(): string { return 'avatar'; }
    public function user() { return $this->belongsTo(User::class); }
}
