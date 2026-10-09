<?php
namespace App\Models;

use App\Models\Concerns\HasPicture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory, HasPicture;

    protected $fillable = ['category_id', 'brand', 'name', 'storage', 'price', 'stock', 'description', 'image'];

    public function category() { return $this->belongsTo(Category::class); }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function specs() { return $this->hasMany(PhoneSpec::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function movements() { return $this->hasMany(StockMovement::class); }

    /** Write one line in the stock log. */
    public function logMovement(int $qty, string $type, ?string $reason = null, ?int $userId = null): void
    {
        StockMovement::create(['phone_id' => $this->id, 'user_id' => $userId ?? auth()->id(), 'type' => $type, 'quantity' => $qty, 'reason' => $reason]);
    }

    /** Change stock and log it. Use a negative number to take units out. */
    public function adjustStock(int $delta, string $type, ?string $reason = null, ?int $userId = null): void
    {
        $this->increment('stock', $delta);
        $this->logMovement($delta, $type, $reason, $userId);
    }
}
