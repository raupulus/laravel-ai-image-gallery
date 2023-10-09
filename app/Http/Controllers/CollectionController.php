<?php

namespace App\Http\Controllers;

use App\Models\Collection;
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
}
