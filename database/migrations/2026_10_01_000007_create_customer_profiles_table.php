<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_profiles', function (Blueprint $t) {
            $t->id();
            $t->foreignId("user_id")->unique()->constrained()->cascadeOnDelete();
            $t->string("phone_number")->nullable();
            $t->string("address")->nullable();
            $t->string("city")->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
