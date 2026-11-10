<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthTokenController extends Controller
{
    use ApiResponse;

    // POST /api/v1/auth/login
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return $this->problemResponse(
                title: 'Unauthorized Login Failed',
                detail: 'Kredensial email atau password yang dimasukkan tidak cocok.',
                status: 401,
                errorSlug: 'invalid-credentials'
            );
        }

        // Hapus token lama & terbitkan Bearer Token baru
        $user->tokens()->delete();
        $token = $user->createToken('pustakawan-api-token')->plainTextToken;

        return $this->successResponse(
            data: [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token_type' => 'Bearer',
                'access_token' => $token,
            ],
            message: 'Otentikasi berhasil. Gunakan access_token ini sebagai Bearer Token.'
        );
    }

    // GET /api/v1/auth/me
    public function me(Request $request): JsonResponse
    {
        return $this->successResponse(
            data: $request->user(),
            message: 'Profil pengguna pemilik token berhasil diambil.'
        );
    }

    // POST /api/v1/auth/logout
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(
            data: null,
            message: 'Token berhasil dicabut (logout sukses).'
        );
    }
}