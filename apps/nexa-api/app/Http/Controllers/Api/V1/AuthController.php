<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = $request->user();

        if (! $user->isActive()) {
            Auth::logout();

            return response()->json([
                'message' => 'Your account is inactive.',
            ], 403);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->safe()->only(['firstname', 'lastname', 'phone']));

        return response()->json([
            'user' => new UserResource($user->fresh()),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update([
            'password' => $request->string('password')->toString(),
        ]);

        return response()->json([
            'message' => 'Password updated.',
        ]);
    }
}
