<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiCollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_requests_are_rejected(): void
    {
        $response = $this->getJson('/api/v1/user');
        $response->assertStatus(401);

        $response = $this->postJson('/api/v1/collections/add', []);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_user_profile(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/user');
        $response->assertStatus(200);
        $response->assertJson(['id' => $user->id, 'email' => $user->email]);
    }

    public function test_can_add_collection_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'batch_id' => 'api-batch-101',
            'title' => 'API Generated Landscape',
            'prompt' => 'Futuristic city under aurora',
            'tags' => ['cyberpunk', 'neon', 'city'],
            'ai' => 'Stable Diffusion',
            'model' => 'SDXL',
        ];

        $response = $this->postJson('/api/v1/collections/add', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertDatabaseHas('collections', [
            'batch_id' => 'api-batch-101',
            'title' => 'API Generated Landscape',
        ]);
    }

    public function test_can_update_collection_with_image_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $collection = Collection::create([
            'batch_id' => 'api-batch-102',
            'title' => 'Collection For Image Upload',
            'prompt' => 'Mountain sunrise',
        ]);

        // 1x1 transparent png
        $base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $payload = [
            'image' => $base64,
            'order' => 1,
        ];

        $response = $this->postJson("/api/v1/collections/update/{$collection->id}", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $image = Image::where('collection_id', $collection->id)->first();
        $this->assertNotNull($image);
        $this->assertTrue(file_exists(storage_path('app/public/' . $image->thumbnail)));

        // Clean up test image
        $image->safeDelete();
    }

    public function test_can_update_video_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $collection = Collection::create([
            'batch_id' => 'api-batch-103',
            'title' => 'Video Collection',
            'prompt' => 'Video preview',
        ]);

        $payload = [
            'batch_id' => 'api-batch-103',
            'url_youtube' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ];

        $response = $this->postJson('/api/v1/collections/video/update', $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('collections', [
            'batch_id' => 'api-batch-103',
            'url_youtube' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }

    public function test_fallback_route_returns_json_404(): void
    {
        $response = $this->getJson('/api/v1/non-existent-endpoint');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Page Not Found']);
    }
}
