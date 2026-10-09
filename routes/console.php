<?php
use App\Models\Phone;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// php artisan store:make-admin someone@example.com
Artisan::command('store:make-admin {email}', function (string $email) {
    $user = User::where('email', $email)->first();
    if (! $user) {
        $this->error("No user with email {$email}. Register that account first.");
        return 1;
    }
    $user->forceFill(['role' => 'admin'])->save();
    $this->info("{$email} is now an admin.");
})->purpose('Give a user the admin role');

// php artisan store:low-stock --under=5
Artisan::command('store:low-stock {--under=5}', function () {
    $rows = Phone::where('stock', '<', (int) $this->option('under'))->orderBy('stock')->get();
    if ($rows->isEmpty()) {
        $this->info('Nothing is low on stock.');
        return;
    }
    $this->table(['Brand', 'Model', 'Stock'], $rows->map(fn ($p) => [$p->brand, $p->name, $p->stock])->all());
})->purpose('List phones that are low on stock');
