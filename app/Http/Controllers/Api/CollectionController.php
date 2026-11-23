<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CollectionAddRequest;
use App\Http\Requests\Api\CollectionUpdateRequest;
use App\Http\Requests\Api\CollectionVideoUpdateRequest;
use App\Models\Collection;
use App\Models\Image;
use App\Services\ImageStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class CollectionController extends Controller
{
    public function add(CollectionAddRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $seeds = $validated['seeds'] ?? null;
        unset($validated['seeds']);

        $collection = Collection::updateOrCreate(
            ['batch_id' => $validated['batch_id']],
            $validated
        );

        Cache::put('collection-image-update-' . $collection->id, $seeds, 3600);

        return response()->json([
            'success' => true,
            'data' => [
                'collection_id' => $collection->id,
                'url' => $collection->url,
            ],
        ]);
    }

    public function update(
        CollectionUpdateRequest $request,
        Collection $collection,
        ImageStorageService $imageStorage
    ): JsonResponse {
        $validated = $request->validated();
        $imageBase64 = (string) $request->input('image');
        unset($validated['image']);

        $paths = $imageStorage->storeFromBase64((int) $collection->id, $imageBase64);

        try {
            $image = Image::create(array_merge($validated, $paths));
        } catch (Throwable $e) {
            $imageStorage->deletePaths([$paths['image'], $paths['thumbnail']]);

            Log::error('Failed to persist uploaded collection image metadata', [
                'collection_id' => $collection->id,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save image.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'image' => [
                    'id' => $image->id,
                    'url_thumbnail' => $image->urlThumbnail,
                    'url_image' => $image->urlImage,
                ],
            ],
        ]);
    }

    public function videoUpdate(CollectionVideoUpdateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Collection::where('batch_id', $validated['batch_id'])->update([
            'url_youtube' => $validated['url_youtube'],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
