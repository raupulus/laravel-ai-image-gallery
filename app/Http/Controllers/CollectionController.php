<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return redirect()->route('collections.show', $collection->id);
    }

    /**
     * Eliminamos una colección junto con las imágenes que la componen.
     *
     * @param Request $request
     * @param Collection $collection
     * @return RedirectResponse
     */
    public function delete(Request $request, Collection $collection): RedirectResponse
    {
        $user = auth()->user();

        if (!$user?->isAdmin || !$collection->id) {
            abort(404);
        }

        $images = $collection->images;

        foreach ($images as $image) {
            $image->safeDelete();
        }

        $collection->delete();

        return redirect()->route('home');
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
