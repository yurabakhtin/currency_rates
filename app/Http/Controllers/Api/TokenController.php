<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 403);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = Str::random(60);

        /** @var User $user */
        $user = Auth::user();
        $user->api_token = hash('sha256', $token);
        $user->save();

        return response()->json(['token' => $token]);
    }

    /**
     * Delete the authenticated user's API token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();
        $user->api_token = null;
        $user->save();

        return response()->json(['message' => 'Token has been cleaned']);
    }
}
