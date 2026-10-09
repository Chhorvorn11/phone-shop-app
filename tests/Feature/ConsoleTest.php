<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesUsers;
use Tests\TestCase;

class ConsoleTest extends TestCase
{
    use RefreshDatabase, MakesUsers;

    public function test_make_admin_promotes_an_existing_user(): void
    {
        $user = $this->customer();
        $this->artisan('store:make-admin', ['email' => $user->email])->assertExitCode(0);
        $this->assertSame('admin', $user->fresh()->role);
    }

    public function test_make_admin_fails_for_unknown_email(): void
    {
        $this->artisan('store:make-admin', ['email' => 'nobody@example.com'])->assertExitCode(1);
    }
}
