<?php
namespace Tests\Feature;

use App\Models\{Accessory, Banner, Category, Phone};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\MakesUsers;
use Tests\TestCase;

class PictureTest extends TestCase
{
    use RefreshDatabase, MakesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function jpg(string $name = 'p.jpg', int $kb = 100): UploadedFile
    {
        return UploadedFile::fake()->create($name, $kb, 'image/jpeg');
    }

    private function phoneData(): array
    {
        return ['brand' => 'Test', 'name' => 'Pic Phone', 'storage' => '64 GB', 'price' => 100, 'stock' => 2];
    }

    public function test_admin_can_upload_a_phone_picture(): void
    {
        $this->actingAs($this->admin())->post('/admin/phones', $this->phoneData() + ['image' => $this->jpg()])->assertRedirect('/admin/stock');

        $phone = Phone::where('name', 'Pic Phone')->firstOrFail();
        $this->assertNotNull($phone->image);
        Storage::disk('public')->assertExists($phone->image);
        $this->assertStringContainsString('/storage/phones/', $phone->pictureUrl());
    }

    public function test_pictures_are_served_even_without_the_storage_link(): void
    {
        Storage::disk('public')->put('phones/show.jpg', 'fake-image-bytes');

        $this->get('/storage/phones/show.jpg')->assertOk();
        $this->get('/storage/phones/missing.jpg')->assertNotFound();
    }

    public function test_replacing_a_picture_deletes_the_old_file(): void
    {
        Storage::disk('public')->put('phones/old.jpg', 'x');
        $phone = Phone::factory()->create(['image' => 'phones/old.jpg']);

        $this->actingAs($this->admin())->put("/admin/phones/{$phone->id}", $this->phoneData() + ['image' => $this->jpg('new.jpg')])->assertRedirect('/admin/stock');

        Storage::disk('public')->assertMissing('phones/old.jpg');
        Storage::disk('public')->assertExists($phone->fresh()->image);
    }

    public function test_remove_picture_checkbox_clears_the_picture(): void
    {
        Storage::disk('public')->put('phones/old.jpg', 'x');
        $phone = Phone::factory()->create(['image' => 'phones/old.jpg']);

        $this->actingAs($this->admin())->put("/admin/phones/{$phone->id}", $this->phoneData() + ['remove_image' => 1]);

        $this->assertNull($phone->fresh()->image);
        Storage::disk('public')->assertMissing('phones/old.jpg');
    }

    public function test_editing_without_a_new_file_keeps_the_picture(): void
    {
        Storage::disk('public')->put('phones/keep.jpg', 'x');
        $phone = Phone::factory()->create(['image' => 'phones/keep.jpg']);

        $this->actingAs($this->admin())->put("/admin/phones/{$phone->id}", $this->phoneData());

        $this->assertSame('phones/keep.jpg', $phone->fresh()->image);
        Storage::disk('public')->assertExists('phones/keep.jpg');
    }

    public function test_deleting_a_phone_deletes_its_picture_file(): void
    {
        Storage::disk('public')->put('phones/gone.jpg', 'x');
        $phone = Phone::factory()->create(['image' => 'phones/gone.jpg']);

        $this->actingAs($this->admin())->delete("/admin/phones/{$phone->id}");

        Storage::disk('public')->assertMissing('phones/gone.jpg');
    }

    public function test_only_images_up_to_2mb_are_accepted(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/phones', $this->phoneData() + ['image' => UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf')])->assertSessionHasErrors('image');
        $this->actingAs($admin)->post('/admin/phones', $this->phoneData() + ['image' => $this->jpg('big.jpg', 3000)])->assertSessionHasErrors('image');
        $this->assertDatabaseCount('phones', 0);
    }

    public function test_admin_can_add_edit_and_delete_an_accessory_with_a_picture(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/accessories', ['name' => 'Clear Case', 'type' => 'case', 'price' => 9, 'stock' => 20, 'image' => $this->jpg()])
            ->assertRedirect('/admin/accessories');
        $item = Accessory::where('name', 'Clear Case')->firstOrFail();
        Storage::disk('public')->assertExists($item->image);

        $this->actingAs($admin)->put("/admin/accessories/{$item->id}", ['name' => 'Tough Case', 'type' => 'case', 'price' => 12, 'stock' => 5])->assertRedirect();
        $this->assertDatabaseHas('accessories', ['id' => $item->id, 'name' => 'Tough Case', 'price' => 12]);

        $path = $item->fresh()->image;
        $this->actingAs($admin)->delete("/admin/accessories/{$item->id}")->assertRedirect();
        $this->assertDatabaseMissing('accessories', ['id' => $item->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_accessory_type_is_validated(): void
    {
        $this->actingAs($this->admin())->post('/admin/accessories', ['name' => 'X', 'type' => 'banana', 'price' => 1, 'stock' => 1])->assertSessionHasErrors('type');
    }

    public function test_public_accessories_page_lists_items_and_filters_by_type(): void
    {
        Accessory::factory()->create(['name' => 'Zed Charger', 'type' => 'charger']);
        Accessory::factory()->create(['name' => 'Yak Cable', 'type' => 'cable']);

        $this->get('/accessories')->assertOk()->assertSee('Zed Charger')->assertSee('Yak Cable');
        $this->get('/accessories?type=cable')->assertSee('Yak Cable')->assertDontSee('Zed Charger');
    }

    public function test_banner_needs_a_picture_and_only_active_ones_show_on_the_shop(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/banners', ['title' => 'No pic', 'active' => 1])->assertSessionHasErrors('image');

        $this->actingAs($admin)->post('/admin/banners', ['title' => 'Big Sale', 'active' => 1, 'image' => $this->jpg()])->assertRedirect('/admin/banners');
        Banner::factory()->create(['title' => 'Hidden Promo', 'active' => false]);

        $this->get('/')->assertSee('Big Sale')->assertDontSee('Hidden Promo');
    }

    public function test_banner_link_must_be_a_web_address(): void
    {
        $this->actingAs($this->admin())->post('/admin/banners', ['title' => 'X', 'link_url' => 'javascript:alert(1)', 'image' => $this->jpg()])->assertSessionHasErrors('link_url');
    }

    public function test_category_picture_upload(): void
    {
        $this->actingAs($this->admin())->post('/admin/categories', ['name' => 'Foldable', 'image' => $this->jpg()])->assertRedirect();
        $cat = Category::where('name', 'Foldable')->firstOrFail();
        Storage::disk('public')->assertExists($cat->image);
    }

    public function test_customer_can_upload_a_profile_picture(): void
    {
        $user = $this->customer();
        $this->actingAs($user)->put('/profile', ['city' => 'Battambang', 'avatar' => $this->jpg('me.jpg')])->assertRedirect();

        $this->assertDatabaseHas('customer_profiles', ['user_id' => $user->id, 'city' => 'Battambang']);
        Storage::disk('public')->assertExists(\App\Models\CustomerProfile::where('user_id', $user->id)->value('avatar'));
    }

    public function test_normal_users_cannot_upload_to_admin_pages(): void
    {
        $this->actingAs($this->customer())->post('/admin/accessories', ['name' => 'X', 'image' => $this->jpg()])->assertForbidden();
        $this->actingAs($this->customer())->post('/admin/banners', ['title' => 'X', 'image' => $this->jpg()])->assertForbidden();
    }
}
