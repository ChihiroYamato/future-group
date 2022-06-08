<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Base api authentication
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(Request $request) : JsonResponse
    {
        $request->validate([
            'email' => 'bail|required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new NotFoundHttpException('wrong password');
        }

        $token = $user->createToken('sanctum auth token');

        return response()->json([
            'status' => 'success',
            'result' => [
                'token' => $token->plainTextToken,
                'auth_type' => 'Bearer'
            ]
        ], 200);
    }
}
