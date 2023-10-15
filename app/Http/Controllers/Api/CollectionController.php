<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CollectionAddRequest;
use App\Http\Requests\Api\CollectionUpdateRequest;
use App\Http\Requests\Api\CollectionVideoUpdateRequest;
use App\Models\Collection;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CollectionController extends Controller
{

    public function add(CollectionAddRequest $request)
    {
        $validated = $request->validated();
        $seeds = isset($validated['seeds']) ? $validated['seeds'] : null;

        if ($seeds) {
            unset($validated['seeds']);
        }

        $collection = Collection::updateOrCreate(
            [
                'batch_id' => $validated['batch_id']
            ],
            $validated
        );

        Cache::remember('collection-image-update-' . $collection->id, 3600, function () use ($seeds) {
            return $seeds;
        });

        return response()->json([
            //'request_data' => $request->all(),
            //'request_data_validated' => $validated,
            'success' => true,
            'data' => [
                'collection_id' => $collection->id,
                'url' => $collection->url
            ]
            //'seeds' => $seeds,

        ]);
    }

    public function update(CollectionUpdateRequest $request, Collection $collection)
    {
        $validated = $request->validated();

        $imageBase64 = $request->get('image');

        unset($validated['image']);

        $image = Image::create($validated);

        $image->storeImageFromBase64($imageBase64);

        /*
        try {
            $image->storeImageFromBase64($imageBase64);
        } catch (\Exception $e) {
            $image->delete();

            return response()->json([
                'success' => false,
            ]);
        }
        */

        return response()->json([
            'success' => true,
            //'request_data' => $request->all(),
            'request_data_validated' => $validated,
            'data' => [
                'image' => [
                    'id' => $image->id,
                    'url_thumbnail' => $image->urlThumbnail,
                    'url_image' => $image->urlImage
                ]
            ]
            //'collection' => $collection,
        ]);
    }

    public function videoUpdate(CollectionVideoUpdateRequest $request)
    {
        $validated = $request->validated();

        Collection::where('batch_id', $validated['batch_id'])->update([
            'youtube_url' => $validated['url_youtube'],
        ]);

        return response()->json([
            'success' => true
        ]);

    }
}
