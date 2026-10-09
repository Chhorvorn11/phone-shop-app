<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId("booking_id")->constrained()->cascadeOnDelete();
            $t->unsignedInteger("amount");
            $t->string("method")->default("cash"); // cash, card, transfer
            $t->string("status")->default("paid"); // paid, refunded
            $t->string("reference")->nullable();
            $t->timestamp("paid_at")->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
