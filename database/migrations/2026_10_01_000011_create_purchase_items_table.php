<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId("purchase_id")->constrained()->cascadeOnDelete();
            $t->foreignId("phone_id")->constrained()->cascadeOnDelete();
            $t->unsignedInteger("quantity");
            $t->unsignedInteger("unit_cost")->default(0);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
