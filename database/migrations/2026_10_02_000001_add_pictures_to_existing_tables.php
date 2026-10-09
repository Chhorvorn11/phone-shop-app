<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private array $columns = ['phones' => 'image', 'categories' => 'image', 'customer_profiles' => 'avatar'];

    public function up(): void
    {
        foreach ($this->columns as $table => $col) {
            if (! Schema::hasColumn($table, $col)) {
                Schema::table($table, fn (Blueprint $t) => $t->string($col)->nullable());
            }
        }
    }
    public function down(): void
    {
        foreach ($this->columns as $table => $col) {
            if (Schema::hasColumn($table, $col)) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn($col));
            }
        }
    }
};
