<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CollectionAddRequest;
use App\Http\Requests\Api\CollectionUpdateRequest;
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

        // TODO -> Recibimos un array de "seeds[]" que debemos guardar en caché de archivos.
        // Al añadir imágenes, vamos a recuperar el seed asociado a esta imagen

        $collection = Collection::create($validated);

        Cache::remember('collection-image-update-' . $collection->id, 3600, function () use ($seeds) {
            return $seeds;
        });

        return response()->json([
            //'request_data' => $request->all(),
            //'request_data_validated' => $validated,
            'success' => true,
            'data' => [
                'collection_id' => $collection->id,
            ]
            //'seeds' => $seeds,

        ]);
    }

    public function update(CollectionUpdateRequest $request, Collection $collection)
    {
        $validated = $request->validated();

        $image = Image::create($validated);

        return response()->json([
            'success' => true,
            //'request_data' => $request->all(),
            'request_data_validated' => $validated,
            'data' => [
                'image' => [
                    'id' => $image->id,
                    'thumbnail' => $image->urlThumbnail,
                    'image' => $image->urlImage
                ]
            ]
            //'collection' => $collection,
        ]);
    }
}
