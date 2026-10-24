<?php

namespace Tests\Feature;

use App\Models\Collection;
use App\Models\Image;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SitemapCommandTest extends TestCase
{
    use RefreshDatabase;

    private string $testSitemapPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testSitemapPath = storage_path('app/test_sitemap.xml');
    }

    protected function tearDown(): void
    {
        if (File::exists($this->testSitemapPath)) {
            File::delete($this->testSitemapPath);
        }
        parent::tearDown();
    }

    public function test_sitemap_command_generates_valid_xml_file(): void
    {
        $this->artisan("sitemap:generate --path={$this->testSitemapPath}")
            ->assertSuccessful();

        $this->assertFileExists($this->testSitemapPath);

        $content = File::get($this->testSitemapPath);
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', $content);
        $this->assertStringContainsString('xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"', $content);
        $this->assertStringContainsString(route('home'), $content);
        $this->assertStringContainsString(route('page.show', 'about'), $content);
        $this->assertStringContainsString(route('page.show', 'social'), $content);
    }

    public function test_sitemap_excludes_empty_pages(): void
    {
        $this->artisan("sitemap:generate --path={$this->testSitemapPath}")
            ->assertSuccessful();

        $content = File::get($this->testSitemapPath);
        $this->assertStringNotContainsString(route('page.show', 'cookies'), $content);
        $this->assertStringNotContainsString(route('page.show', 'rgpd'), $content);
    }

    public function test_sitemap_includes_collections_with_images_and_avoids_empty_collections(): void
    {
        // 1. Valid collection with images and title
        $validCollection = Collection::create([
            'batch_id' => 'batch-valid',
            'title' => 'Valid Nature Collection',
            'description' => 'Beautiful landscapes in 4K',
            'prompt' => 'Mountain sunrise',
        ]);

        $image = Image::create([
            'collection_id' => $validCollection->id,
            'thumbnail' => 'collections/valid/thumb.webp',
            'image' => 'collections/valid/main.webp',
            'order' => 1,
        ]);

        // 2. Empty collection without images
        $emptyCollection = Collection::create([
            'batch_id' => 'batch-empty',
            'title' => 'Empty Collection With No Images',
            'prompt' => 'Nothing here',
        ]);

        // 3. Collection with empty title
        $noTitleCollection = Collection::create([
            'batch_id' => 'batch-no-title',
            'title' => '',
            'prompt' => 'Empty title prompt',
        ]);
        Image::create([
            'collection_id' => $noTitleCollection->id,
            'thumbnail' => 'collections/notitle/thumb.webp',
            'image' => 'collections/notitle/main.webp',
            'order' => 1,
        ]);

        $this->artisan("sitemap:generate --path={$this->testSitemapPath}")
            ->assertSuccessful();

        $content = File::get($this->testSitemapPath);

        // Valid collection and its image metadata must be in sitemap
        $this->assertStringContainsString(route('collections.show', $validCollection->id), $content);
        $this->assertStringContainsString('<image:image>', $content);
        $this->assertStringContainsString($image->url_image, $content);
        $this->assertStringContainsString('Valid Nature Collection', $content);
        $this->assertStringContainsString('Beautiful landscapes in 4K', $content);

        // Empty collections must be excluded
        $this->assertStringNotContainsString(route('collections.show', $emptyCollection->id), $content);
        $this->assertStringNotContainsString(route('collections.show', $noTitleCollection->id), $content);
    }

    public function test_sitemap_generation_schedule_is_registered_at_03_42(): void
    {
        $schedule = app()->make(Schedule::class);

        $events = collect($schedule->events())->filter(function (Event $event) {
            return str_contains($event->command, 'sitemap:generate');
        });

        $this->assertNotEmpty($events, 'The sitemap:generate command is not registered in the schedule.');

        /** @var Event $sitemapEvent */
        $sitemapEvent = $events->first();

        // Cron expression for 03:42 daily is "42 3 * * *"
        $this->assertEquals('42 3 * * *', $sitemapEvent->expression);
    }
}
