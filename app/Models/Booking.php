<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'phone_id', 'coupon_id', 'quantity', 'pickup_date', 'status', 'note', 'discount_amount', 'total_amount'];
    protected $casts = ['pickup_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
    public function phone() { return $this->belongsTo(Phone::class); }
    public function coupon() { return $this->belongsTo(Coupon::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function profile() { return $this->hasOne(CustomerProfile::class, 'user_id', 'user_id'); }

    public function paidAmount(): int
    {
        return (int) $this->payments()->where('status', 'paid')->sum('amount');
    }

    /** Completed and cancelled bookings are final. Cancelling returns the units to stock. */
    public function changeStatus(string $to): void
    {
        if (in_array($this->status, ['completed', 'cancelled']) || $this->status === $to) return;

        DB::transaction(function () use ($to) {
            if ($to === 'cancelled') {
                $this->phone->adjustStock($this->quantity, 'cancel', "Booking #{$this->id} cancelled");
            }
            $this->update(['status' => $to]);
        });
    }
}
