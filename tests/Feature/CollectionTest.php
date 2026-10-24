<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Image;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $userRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::firstOrCreate(['slug' => 'admin'], [
            'name' => 'Admin',
            'show_name' => 'Admin',
            'description' => 'Site Admin'
        ]);

        $this->userRole = Role::firstOrCreate(['slug' => 'user'], [
            'name' => 'User',
            'show_name' => 'User',
            'description' => 'Standard User'
        ]);
    }

    public function test_home_page_displays_collections(): void
    {
        $collection = Collection::create([
            'batch_id' => 'batch-test-1',
            'title' => 'Sample Collection',
            'prompt' => 'A wonderful scenic test',
        ]);

        Image::create([
            'collection_id' => $collection->id,
            'thumbnail' => 'collections/1/thumb.webp',
            'image' => 'collections/1/main.webp',
            'order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sample Collection');
    }

    public function test_collection_show_page_renders_successfully(): void
    {
        $collection = Collection::create([
            'batch_id' => 'batch-test-2',
            'title' => 'Art Gallery Show',
            'prompt' => 'An abstract painting',
        ]);

        $image = Image::create([
            'collection_id' => $collection->id,
            'thumbnail' => 'collections/2/thumb.webp',
            'image' => 'collections/2/main.webp',
            'order' => 1,
        ]);

        $response = $this->get('/collections/show/' . $collection->id);
        $response->assertStatus(200);
        $response->assertSee('Art Gallery Show');
    }

    public function test_random_collection_redirects_to_collection_show(): void
    {
        $collection = Collection::create([
            'batch_id' => 'batch-test-3',
            'title' => 'Random Target',
            'prompt' => 'A starry night sky',
        ]);

        $response = $this->get('/collections/random');
        $response->assertRedirect('/collections/show/' . $collection->id);
    }

    public function test_non_admin_cannot_delete_collection(): void
    {
        $user = User::factory()->create(['role_id' => $this->userRole->id]);
        $collection = Collection::create([
            'batch_id' => 'batch-test-4',
            'title' => 'Protected Collection',
            'prompt' => 'Castle on a hill',
        ]);

        $response = $this->actingAs($user)->post('/collections/delete/' . $collection->id);
        $response->assertStatus(404);
        $this->assertDatabaseHas('collections', ['id' => $collection->id]);
    }

    public function test_admin_can_delete_collection(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $collection = Collection::create([
            'batch_id' => 'batch-test-5',
            'title' => 'Deletable Collection',
            'prompt' => 'ToDelete',
        ]);

        $response = $this->actingAs($admin)->post('/collections/delete/' . $collection->id);
        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('collections', ['id' => $collection->id]);
    }

    public function test_admin_can_delete_image_with_json_response(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $collection = Collection::create([
            'batch_id' => 'batch-test-6',
            'title' => 'Collection with Image',
            'prompt' => 'Cyberpunk street',
        ]);

        $image = Image::create([
            'collection_id' => $collection->id,
            'thumbnail' => 'collections/test/thumb.webp',
            'image' => 'collections/test/main.webp',
            'order' => 1,
        ]);

        $response = $this->actingAs($admin)
            ->postJson("/collections/{$collection->id}/image/{$image->id}/delete");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Image successfully removed from collection'
        ]);
        $this->assertDatabaseMissing('images', ['id' => $image->id]);
    }

    public function test_ajax_get_home_cards_returns_json(): void
    {
        $collection = Collection::create([
            'batch_id' => 'batch-ajax',
            'title' => 'Ajax Collection',
            'prompt' => 'Sunset over ocean',
        ]);

        Image::create([
            'collection_id' => $collection->id,
            'thumbnail' => 'collections/ajax/thumb.webp',
            'image' => 'collections/ajax/main.webp',
            'order' => 1,
        ]);

        $response = $this->getJson('/collections/ajax/get/cards/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'html',
            'total',
            'page',
            'hasMorePages',
            'lastItem'
        ]);
    }

    public function test_static_pages_render_or_redirect(): void
    {
        $response = $this->get('/page/about');
        $response->assertStatus(200);
        $response->assertSee('About');

        $response = $this->get('/page/cookies');
        $response->assertStatus(200);

        $response = $this->get('/page/invalid-page');
        $response->assertRedirect(route('home'));
    }

    public function test_twitter_webhook_returns_success(): void
    {
        $response = $this->postJson('/webhooks/twitter');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
