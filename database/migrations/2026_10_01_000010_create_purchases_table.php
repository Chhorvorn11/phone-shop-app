<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $t) {
            $t->id();
            $t->foreignId("supplier_id")->constrained()->cascadeOnDelete();
            $t->string("reference")->nullable();
            $t->date("purchased_at");
            $t->unsignedInteger("total_cost")->default(0);
            $t->string("note")->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
