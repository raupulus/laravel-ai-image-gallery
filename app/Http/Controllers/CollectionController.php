<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Image;
use App\Services\ImageStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CollectionController extends Controller
{


    /**
     * Muestra una colección concreta con todas sus imágenes y metadatos.
     *
     * @param Collection $collection
     *
     * @return View
     */
    public function show(Collection $collection): View
    {

        // TODO: Crear sistema de notificaciones y/o búsqueda para controlar entrar a sitios que no existan

        $images = $collection->images;

        return view('collections.show', [
            'collection' => $collection,
            'images' => $images,
            'firstImage' => $images->first()
        ]);
    }

    /**
     * Busca una colección aleatoria y lleva hacia ella.
     *
     * @return RedirectResponse
     */
    public function random(): RedirectResponse
    {
        $collection = Collection::inRandomOrder()->select('id')->first();

        if (!$collection) {
            return redirect()->route('home');
        }

        return redirect()->route('collections.show', $collection->id);
    }

    /**
     * Eliminamos una colección junto con las imágenes que la componen.
     *
     * @param Request $request
     * @param Collection $collection
     * @return RedirectResponse
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
                ->flatMap(static fn (Image $img): array => [$img->image, $img->thumbnail])
                ->all();

            $collection->images()->delete();
            $collection->delete();
        });

        $imageStorage->deletePaths($paths);
        $imageStorage->deleteCollectionDirectory($collectionId);

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

        if ($request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Image successfully removed from collection',
            ]);
        }

        return redirect()->to($collection->url);
    }



    /*****************    AJAX  **************/

    public function ajaxGetHomeCards(Request $request, int $page = 1, string|null $search = null): JsonResponse
    {
        $collections = Collection::getQueryCollection($search);

        $collections = $collections->orderByDesc('created_at')
            ->paginate(8, null, null, $page);

        $html = '';

        $collections->each(function ($ele) use (&$html)  {
            $html .= \view('components._collection_block', [
                'collection' => $ele
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
