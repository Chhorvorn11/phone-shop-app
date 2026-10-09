<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->string('link_url')->nullable();
            $t->string('image')->nullable();
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
