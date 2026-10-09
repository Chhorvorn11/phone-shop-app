<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'phone_id', 'quantity', 'unit_cost'];

    public function purchase() { return $this->belongsTo(Purchase::class); }
    public function phone() { return $this->belongsTo(Phone::class); }
}
