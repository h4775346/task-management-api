<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Authenticate a user and return a JWT token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = app('tymon.jwt.auth')->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => app('tymon.jwt.auth')->factory()->getTTL() * 60,
            'refresh_expires_in' => config('jwt.refresh_ttl') * 60,
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = app('tymon.jwt.auth')->parseToken()->refresh();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => app('tymon.jwt.auth')->factory()->getTTL() * 60,
            'refresh_expires_in' => config('jwt.refresh_ttl') * 60,
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            app('tymon.jwt.auth')->invalidate(app('tymon.jwt.auth')->getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not invalidate token'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse|UserResource
     */
    public function me()
    {
        /** @var User $user */
        $user = auth('api')->user();

        // Load roles for the user
        $user->load('roles');

        return new UserResource($user);
    }
}
