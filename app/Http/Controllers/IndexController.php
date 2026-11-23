<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Página principal.
     */
    public function index(Request $request): View
    {
        $rawSearch = $request->query('search');
        $search = is_string($rawSearch) ? trim(Str::limit($rawSearch, 100, '')) : null;

        if ($search === '') {
            $search = null;
        }

        $collections = Collection::getQueryCollection($search);

        return view('home', [
            'collections' => $collections->orderByDesc('created_at')->paginate(24),
            'search' => $search,
        ]);
    }
}
