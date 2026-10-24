<?php

namespace App\Console\Commands;

use App\Http\Controllers\PageController;
use App\Models\Collection;
use Illuminate\Console\Command;
use XMLWriter;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--path= : Target file path for the sitemap}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate complete sitemap.xml including active pages and non-empty collections';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Starting sitemap generation...');

        $targetPath = $this->option('path') ?: public_path('sitemap.xml');

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument('1.0', 'UTF-8');

        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

        $urlCount = 0;
        $imageCount = 0;

        // 1. Home page
        $latestCollection = Collection::query()
            ->whereNotNull('title')
            ->where('title', '!=', '')
            ->whereHas('images')
            ->latest('updated_at')
            ->first();

        $homeLastmod = $latestCollection?->updated_at?->toAtomString() ?? now()->toAtomString();

        $xml->startElement('url');
        $xml->writeElement('loc', route('home'));
        $xml->writeElement('lastmod', $homeLastmod);
        $xml->writeElement('changefreq', 'daily');
        $xml->writeElement('priority', '1.0');
        $xml->endElement();
        $urlCount++;

        // 2. Static pages (only active, non-empty pages)
        $activePages = PageController::getActivePages();
        foreach ($activePages as $slug => $page) {
            $xml->startElement('url');
            $xml->writeElement('loc', route('page.show', $slug));
            $xml->writeElement('changefreq', 'monthly');
            $xml->writeElement('priority', '0.6');
            $xml->endElement();
            $urlCount++;
        }

        // 3. Collections (strictly non-empty: must have images and valid title)
        $collections = Collection::query()
            ->whereNotNull('title')
            ->where('title', '!=', '')
            ->whereHas('images')
            ->with(['images' => function ($query) {
                $query->orderBy('order')->orderBy('id');
            }])
            ->orderByDesc('updated_at')
            ->get();

        foreach ($collections as $collection) {
            $xml->startElement('url');
            $xml->writeElement('loc', route('collections.show', $collection->id));

            $lastmod = ($collection->updated_at ?? $collection->created_at ?? now())->toAtomString();
            $xml->writeElement('lastmod', $lastmod);
            $xml->writeElement('changefreq', 'weekly');
            $xml->writeElement('priority', '0.8');

            foreach ($collection->images as $image) {
                if (empty($image->image) && empty($image->url_image)) {
                    continue;
                }

                $imageUrl = $image->url_image;
                if ($imageUrl) {
                    $xml->startElement('image:image');
                    $xml->writeElement('image:loc', $imageUrl);

                    if (!empty($collection->title)) {
                        $xml->writeElement('image:title', $collection->title);
                    }

                    if (!empty($collection->description)) {
                        $xml->writeElement('image:caption', $collection->description);
                    }

                    $xml->endElement(); // image:image
                    $imageCount++;
                }
            }

            $xml->endElement(); // url
            $urlCount++;
        }

        $xml->endElement(); // urlset
        $xml->endDocument();

        $content = $xml->outputMemory();

        $directory = dirname($targetPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($targetPath, $content);

        $this->info("Sitemap successfully generated at: {$targetPath}");
        $this->line("Indexed URLs: {$urlCount} (including {$collections->count()} collections, " . count($activePages) . " static pages, and home)");
        $this->line("Indexed Images: {$imageCount}");

        return Command::SUCCESS;
    }
}
