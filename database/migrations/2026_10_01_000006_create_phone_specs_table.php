<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phone_specs', function (Blueprint $t) {
            $t->id();
            $t->foreignId("phone_id")->constrained()->cascadeOnDelete();
            $t->string("label");
            $t->string("value");
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('phone_specs');
    }
};
