<?php

namespace App\Http\Controllers\Api;

use App\Facades\Token;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $token = Token::user(Auth::user())
            ->getNew(100);

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
        Token::user(Auth::guard('api')->user())
            ->delete();

        return response()->json(['message' => 'Token has been cleaned']);
    }
}
