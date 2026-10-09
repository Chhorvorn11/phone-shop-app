<?php
namespace Tests\Concerns;

use App\Models\User;

trait MakesUsers
{
    protected function admin(): User
    {
        return tap(User::factory()->create(), fn ($u) => $u->forceFill(['role' => 'admin'])->save());
    }

    protected function customer(): User
    {
        return User::factory()->create()->fresh(); // fresh() loads the default role from the database
    }
}
