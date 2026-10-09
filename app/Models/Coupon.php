<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'percent', 'expires_at', 'max_uses', 'used_count', 'active'];
    protected $casts = ["expires_at" => "date", "active" => "boolean"];

    public function bookings() { return $this->hasMany(Booking::class); }

    public function isUsable(): bool
    {
        return $this->active
            && (! $this->expires_at || $this->expires_at->copy()->endOfDay()->isFuture())
            && (! $this->max_uses || $this->used_count < $this->max_uses);
    }
}
