<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Image;
use App\Models\Role;
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
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => false,
        ]);
        $response->assertJsonMissingPath('role_id');
    }

    public function test_non_admin_user_cannot_write_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['collections:write']);

        $this->postJson('/api/v1/collections/add', [
            'batch_id' => 'api-batch-denied',
            'title' => 'Unauthorized',
            'prompt' => 'Prompt',
        ])->assertStatus(403);
    }

    public function test_token_without_write_ability_cannot_write_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:read']);

        $this->postJson('/api/v1/collections/add', [
            'batch_id' => 'api-batch-scope-denied',
            'title' => 'Missing Scope',
            'prompt' => 'Prompt',
        ])->assertStatus(403);
    }

    public function test_can_add_collection_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:write']);

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
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:write']);

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
        $this->assertTrue(file_exists(storage_path('app/public/'.$image->thumbnail)));

        // Clean up test image
        $image->safeDelete();
    }

    public function test_can_update_video_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:write']);

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

    public function test_video_update_rejects_non_youtube_or_javascript_urls(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:write']);

        Collection::create([
            'batch_id' => 'api-batch-104',
            'title' => 'Video Validation Collection',
            'prompt' => 'Video preview',
        ]);

        $this->postJson('/api/v1/collections/video/update', [
            'batch_id' => 'api-batch-104',
            'url_youtube' => 'javascript:alert(1)',
        ])->assertStatus(422);

        $this->postJson('/api/v1/collections/video/update', [
            'batch_id' => 'api-batch-104',
            'url_youtube' => 'https://evil.example.com/embed/abc123',
        ])->assertStatus(422);
    }

    public function test_unauthenticated_api_request_without_accept_json_returns_json(): void
    {
        $response = $this->post('/api/v1/collections/add', []);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_update_collection_rejects_corrupt_or_non_image_base64(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:write']);

        $collection = Collection::create([
            'batch_id' => 'api-batch-corrupt-img',
            'title' => 'Corrupt Image Test',
            'prompt' => 'Test',
        ]);

        $this->postJson("/api/v1/collections/update/{$collection->id}", [
            'image' => 'not-valid-base64!!!',
            'order' => 1,
        ])->assertStatus(422)->assertJsonValidationErrors(['image']);

        $this->postJson("/api/v1/collections/update/{$collection->id}", [
            'image' => 'data:text/plain;base64,'.base64_encode('hello world'),
            'order' => 1,
        ])->assertStatus(422)->assertJsonValidationErrors(['image']);

        $this->assertDatabaseCount('images', 0);
    }

    public function test_add_collection_validates_seeds_and_preserves_null_numeric_params(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:write']);

        $this->postJson('/api/v1/collections/add', [
            'batch_id' => 'api-batch-invalid-seeds',
            'title' => 'Invalid Seeds',
            'prompt' => 'Test',
            'seeds' => ['not-an-int'],
        ])->assertStatus(422)->assertJsonValidationErrors(['seeds.0']);

        $response = $this->postJson('/api/v1/collections/add', [
            'batch_id' => 'api-batch-null-params',
            'title' => 'Null Params Collection',
            'prompt' => 'Mountain lake',
            'role' => 'photographer-landscape',
            'restore_faces' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('collections', [
            'batch_id' => 'api-batch-null-params',
            'steps' => null,
            'cfg_scale' => null,
            'denoising_strength' => null,
            'restore_faces' => true,
        ]);
        $this->assertDatabaseHas('collection_roles', ['slug' => 'photographer-landscape']);
    }

    public function test_update_collection_rejects_out_of_range_order(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin, ['collections:write']);

        $collection = Collection::create([
            'batch_id' => 'api-batch-order-bounds',
            'title' => 'Order Bounds Test',
            'prompt' => 'Test',
        ]);

        $this->postJson("/api/v1/collections/update/{$collection->id}", [
            'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            'order' => -1,
        ])->assertStatus(422)->assertJsonValidationErrors(['order']);
    }

    public function test_fallback_route_returns_json_404(): void
    {
        $response = $this->getJson('/api/v1/non-existent-endpoint');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Page Not Found']);
    }

    public function test_artisan_api_token_command_issues_scoped_token_only_to_admins(): void
    {
        $userRole = Role::firstOrCreate(['slug' => 'user'], [
            'name' => 'User',
            'show_name' => 'User',
            'description' => 'Standard User',
        ]);

        $normalUser = User::factory()->create(['role_id' => $userRole->id]);
        $admin = User::factory()->admin()->create();

        $this->artisan('api:token', ['email' => $normalUser->email])
            ->assertExitCode(1);

        $this->artisan('api:token', ['email' => $admin->email, '--name' => 'cli-test'])
            ->assertExitCode(0);

        $token = $admin->tokens()->where('name', 'cli-test')->first();
        $this->assertNotNull($token);
        $this->assertSame(['collections:write'], $token->abilities);
    }
}
