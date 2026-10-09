<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesUsers;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, MakesUsers;

    public function test_guest_is_sent_to_login_from_my_bookings(): void
    {
        $this->get('/my-bookings')->assertRedirect('/login');
    }

    public function test_registering_creates_a_normal_user_and_signs_in(): void
    {
        $this->post('/register', [
            'name' => 'Sok', 'email' => 'sok@example.com',
            'password' => 'secret123', 'password_confirmation' => 'secret123',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertSame('user', User::where('email', 'sok@example.com')->first()->role);
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = $this->customer();
        $this->post('/login', ['email' => $user->email, 'password' => 'nope'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_sign_in_and_out(): void
    {
        $user = $this->customer(); // factory password is "password"
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}
