<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return authenticated API user profile with safe fields.
     */
    public function show(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
