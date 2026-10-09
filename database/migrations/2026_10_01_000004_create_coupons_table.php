<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $t) {
            $t->id();
            $t->string("code")->unique();
            $t->unsignedTinyInteger("percent");
            $t->date("expires_at")->nullable();
            $t->unsignedInteger("max_uses")->nullable();
            $t->unsignedInteger("used_count")->default(0);
            $t->boolean("active")->default(true);
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
