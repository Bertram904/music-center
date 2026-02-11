<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{
   protected AuthService $authService;

    /**
     * @param AuthService $authService
     */
   public function __construct(AuthService $authService) {
       $this->authService = $authService;
   }
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return $this->respondSuccess($data,
            'Login successful!');
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return $this->respondCreated(['user' => $user],
            'Registration successful!');
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->getProfile();
        return $this->respondSuccess($user,
            'Profile successful!');
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->respondSuccess(null,
            'Logout successful!');
    }

    /**
     * This method invalidates the old tokens and returns a new one
     * with a fresh expiration time. This is essential for maintaining long-lived sessions
     * without forcing the user re-login
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $data = $this->authService->refresh();

        return $this->respondSuccess($data,
        'Token refreshed successfully!');
    }
}
