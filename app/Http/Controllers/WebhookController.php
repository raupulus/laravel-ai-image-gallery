<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    /**
     * Legacy Twitter webhook acknowledgement endpoint.
     */
    public function twitter(): JsonResponse
    {
        return response()->json([
            'success' => true,
        ]);
    }
}
