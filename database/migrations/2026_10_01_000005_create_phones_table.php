<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phones', function (Blueprint $t) {
            $t->id();
            $t->foreignId("category_id")->nullable()->constrained()->nullOnDelete();
            $t->string("brand");
            $t->string("name");
            $t->string("storage");
            $t->unsignedInteger("price");
            $t->unsignedInteger("stock")->default(0);
            $t->text("description")->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
