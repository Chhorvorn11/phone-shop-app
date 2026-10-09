<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $t) {
            $t->id();
            $t->foreignId("phone_id")->constrained()->cascadeOnDelete();
            $t->foreignId("user_id")->nullable()->constrained()->nullOnDelete();
            $t->string("type"); // initial, purchase, booking, cancel, adjust
            $t->integer("quantity"); // signed: negative = stock went down
            $t->string("reason")->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
