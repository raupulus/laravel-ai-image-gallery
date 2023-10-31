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
        $search = $request->get('search');

        $collections = Collection::getQueryCollection($search);

        return view('home', [
            'collections' => $collections->orderByDesc('created_at')->paginate(24),
            'search' => $search,
        ]);
    }
}
