<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IndexController extends Controller
{

    /**
     * Página principal.
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
         $collections = Collection::select([
            'collections.*',
            DB::raw('images.collection_id')
        ])
            ->leftJoin('images', 'collections.id', '=', 'images.collection_id')
            ->whereNotNull('images.collection_id')
            ->whereNotNull('title')
            ->groupBy('images.collection_id')
            ->groupBy('collections.id');

        $search = $request->get('search');

        if ($search) {

            $collections->where(function ($q) use ($search) {
                return $q->orWhere('title', 'ILIKE', '%' . $search . '%')
                    ->orWhere('tags', 'ILIKE', '%' . $search . '%');
            });
        }

        $search = $request->get('search');

        if ($search) {

            $collections->where(function ($q) use ($search) {
                return $q->orWhere('title', 'ILIKE', '%' . $search . '%')
                    ->orWhere('tags', 'ILIKE', '%' . $search . '%');
            });
        }

        return view('home', [
            'collections' => $collections->orderByDesc('created_at')->paginate(24),
            'search' => $search,
        ]);
    }
}
