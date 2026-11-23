<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Image;
use App\Services\ImageStorageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * Muestra una colección concreta con todas sus imágenes y metadatos.
     */
    public function show(Collection $collection): View
    {
        $collection->load([
            'images' => fn ($query) => $query->orderBy('order')->orderBy('id'),
            'collectionRole',
        ]);

        $images = $collection->images;

        return view('collections.show', [
            'collection' => $collection,
            'images' => $images,
            'firstImage' => $images->first(),
        ]);
    }

    /**
     * Busca una colección aleatoria y lleva hacia ella evitando ORDER BY RANDOM() en BD.
     */
    public function random(): RedirectResponse
    {
        /** @var array<int, int|string> $ids */
        $ids = Cache::remember('collections.valid_ids', 600, static fn (): array => Collection::query()
            ->whereHas('images')
            ->pluck('id')
            ->all());

        if ($ids === []) {
            return redirect()->route('home');
        }

        $randomId = Arr::random($ids);

        return redirect()->route('collections.show', $randomId);
    }

    /**
     * Eliminamos una colección junto con las imágenes que la componen.
     */
    public function delete(Request $request, Collection $collection, ImageStorageService $imageStorage): RedirectResponse
    {
        $user = auth()->user();

        if (! $user || $user->cannot('delete', $collection) || ! $collection->id) {
            abort(404);
        }

        $collectionId = (int) $collection->id;
        $paths = [];

        DB::transaction(function () use ($collection, &$paths): void {
            $paths = $collection->images()
                ->get(['image', 'thumbnail'])
                ->flatMap(static fn (Model $img): array => $img instanceof Image ? [$img->image, $img->thumbnail] : [])
                ->all();

            $collection->images()->delete();
            $collection->delete();
        });

        $imageStorage->deletePaths($paths);
        $imageStorage->deleteCollectionDirectory($collectionId);
        Cache::forget('collections.valid_ids');

        return redirect()->route('home');
    }

    /**
     * Elimina una imagen de una colección.
     */
    public function deleteImage(
        Request $request,
        Collection $collection,
        Image $image,
        ImageStorageService $imageStorage
    ): JsonResponse|RedirectResponse {
        $user = auth()->user();

        if (! $user || $user->cannot('delete', $collection) || ! $collection->id || ! $image->id || (int) $image->collection_id !== (int) $collection->id) {
            abort(404);
        }

        $paths = [$image->image, $image->thumbnail];

        DB::transaction(function () use ($image): void {
            $image->delete();
        });

        $imageStorage->deletePaths($paths);
        Cache::forget('collections.valid_ids');

        if ($request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Image successfully removed from collection',
            ]);
        }

        return redirect()->to($collection->url);
    }

    /*****************    AJAX  **************/

    public function ajaxGetHomeCards(Request $request, int $page = 1, ?string $search = null): JsonResponse
    {
        $safePage = max(1, min($page, 10000));
        $safeSearch = is_string($search) ? trim(Str::limit($search, 100, '')) : null;
        if ($safeSearch === '') {
            $safeSearch = null;
        }

        $collections = Collection::getQueryCollection($safeSearch);

        $collections = $collections->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(8, ['*'], 'page', $safePage);

        $html = '';

        $collections->each(function ($ele) use (&$html) {
            $html .= \view('components._collection_block', [
                'collection' => $ele,
            ])->render();
        });

        return response()->json([
            'success' => true,
            'html' => $html,
            'total' => $collections->total(),
            'page' => $collections->currentPage(),
            'hasMorePages' => $collections->hasMorePages(),
            'lastItem' => $collections->lastItem(),
        ]);
    }
}
