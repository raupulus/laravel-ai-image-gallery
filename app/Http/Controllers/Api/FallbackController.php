<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FallbackController extends Controller
{
    /**
     * Return JSON 404 for unknown /api/v1 routes.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(['message' => 'Page Not Found'], 404);
    }
}
