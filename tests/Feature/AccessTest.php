<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesUsers;
use Tests\TestCase;

class AccessTest extends TestCase
{
    use RefreshDatabase, MakesUsers;

    private array $adminPages = ['/admin', '/admin/stock', '/admin/categories', '/admin/bookings', '/admin/reports', '/admin/phones/create',
        '/admin/suppliers', '/admin/purchases', '/admin/purchases/create', '/admin/payments', '/admin/coupons', '/admin/messages', '/admin/movements',
        '/admin/accessories', '/admin/accessories/create', '/admin/banners', '/admin/banners/create'];

    public function test_guests_are_sent_to_login_for_admin_pages(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_normal_users_get_403_on_every_admin_page(): void
    {
        foreach ($this->adminPages as $url) {
            $this->actingAs($this->customer())->get($url)->assertForbidden();
        }
    }

    public function test_normal_users_cannot_create_phones(): void
    {
        $this->actingAs($this->customer())->post('/admin/phones', ['brand' => 'X'])->assertForbidden();
    }

    public function test_admin_can_open_every_admin_page(): void
    {
        foreach ($this->adminPages as $url) {
            $this->actingAs($this->admin())->get($url)->assertOk();
        }
    }

    public function test_shop_is_public(): void
    {
        $this->get('/')->assertOk();
    }
}
