<?php

namespace Tests\Unit;

use App\Models\Collection;
use Illuminate\Support\Str;
use Tests\TestCase;

class CollectionModelTest extends TestCase
{
    public function test_aspect_ratio_accessors_evaluate_dimensions(): void
    {
        $landscape = new Collection(['size' => '1920x1080']);
        $this->assertTrue($landscape->isHorizontal);
        $this->assertFalse($landscape->isVertical);

        $portrait = new Collection(['size' => '768x1344']);
        $this->assertFalse($portrait->isHorizontal);
        $this->assertTrue($portrait->isVertical);
    }

    public function test_tags_array_and_title_resume_accessors(): void
    {
        $longTitle = str_repeat('A', 75);
        $collection = new Collection([
            'title' => $longTitle,
            'tags' => 'cyberpunk,neon,aurora',
        ]);

        $this->assertSame(['cyberpunk', 'neon', 'aurora'], $collection->tagsArray);
        $this->assertSame(Str::limit($longTitle, 50), $collection->titleResume);

        $emptyTags = new Collection(['tags' => null]);
        $this->assertSame([], $emptyTags->tagsArray);
    }

    public function test_youtube_embed_url_normalizes_valid_urls_and_rejects_invalid(): void
    {
        $watch = new Collection(['url_youtube' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $watch->youtube_embed_url);

        $short = new Collection(['url_youtube' => 'https://youtu.be/dQw4w9WgXcQ']);
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $short->youtube_embed_url);

        $unsafe = new Collection(['url_youtube' => 'https://evil.example.com/watch?v=dQw4w9WgXcQ']);
        $this->assertNull($unsafe->youtube_embed_url);
    }
}
