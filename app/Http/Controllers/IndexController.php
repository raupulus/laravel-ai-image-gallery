<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    public function index(Request $request)
    {
        $collections = Collection::whereNotNull('title');


        //TODO: Hay que filtrar por los que no tienen imágenes. Ahora se hace en el frontend al mostrarlo
        // pero esto puede dejar elementos en blanco o páginas en blanco

        /*
         $collections = Collection::select([
            'collections.*',
            DB::raw('images.id')
        ])
            ->leftJoin('images', 'collections.id', '=', 'images.collection_id')
            ->whereNotNull('images.id')
            ->whereNotNull('title');
            //->groupBy('images.collection_id');
        /*

        $search = $request->get('search');

        if ($search) {

            $collections->where(function ($q) use ($search) {
                return $q->orWhere('title', 'ILIKE', '%' . $search . '%')
                    ->orWhere('tags', 'ILIKE', '%' . $search . '%');
            });
        }
         */

        $search = $request->get('search');

        if ($search) {

            $collections->where(function ($q) use ($search) {
                return $q->orWhere('title', 'ILIKE', '%' . $search . '%')
                    ->orWhere('tags', 'ILIKE', '%' . $search . '%');
            });
        }

        return view('home', [
            'collections' => $collections->orderByDesc('created_at')->paginate(8),
            'search' => $search,
        ]);
    }
}
