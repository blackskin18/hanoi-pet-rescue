<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Google_Client;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $userService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->userService = $userService;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $tokenId = request('tokenId');

        $client = new Google_Client(['client_id' => '904910057330-6gbn36bltbl6qq89ddvpm5j0lhb6nu4q.apps.googleusercontent.com']);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($tokenId);
        Log::info($payload);
        if ($payload) {
            $user = $this->userService->verifyUser($payload['email'], $payload['sub']);
            if (!$user) {
                return $this->responseForbidden();
            }

            $token = auth()->login($user);
            return $this->respondWithToken($token);
        } else {
            return $this->responseForbidden();
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'name' => auth()->user()->name,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
