<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_existent_collection_renders_custom_404_page(): void
    {
        $response = $this->get('/collections/show/999999');
        $response->assertStatus(404);
        $response->assertSee('Page or collection not found');
        $response->assertSee('Back to Gallery');
        $response->assertSee('noindex, nofollow', false);
    }

    public function test_factories_create_collection_with_images_cleanly(): void
    {
        $collection = Collection::factory()->create();
        $image = Image::factory()->for($collection)->create();

        $this->assertDatabaseHas('collections', ['id' => $collection->id]);
        $this->assertDatabaseHas('images', ['id' => $image->id, 'collection_id' => $collection->id]);
    }
}
