<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accessories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('type')->default('other'); // case, charger, cable, headphones, protector, other
            $t->unsignedInteger('price');
            $t->unsignedInteger('stock')->default(0);
            $t->text('description')->nullable();
            $t->string('image')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
