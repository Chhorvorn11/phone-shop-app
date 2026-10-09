<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId("user_id")->constrained()->cascadeOnDelete();
            $t->foreignId("phone_id")->constrained()->cascadeOnDelete();
            $t->foreignId("coupon_id")->nullable()->constrained()->nullOnDelete();
            $t->unsignedInteger("quantity")->default(1);
            $t->date("pickup_date");
            $t->string("status")->default("pending"); // pending, confirmed, completed, cancelled
            $t->string("note")->nullable();
            $t->unsignedInteger("discount_amount")->default(0);
            $t->unsignedInteger("total_amount")->default(0);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
