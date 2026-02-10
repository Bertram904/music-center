<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Exceptions\Auth\AccountLockedException;
use App\Exceptions\Auth\LoginFailedException;
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

        return $this->respondSuccess($data, 'Login successful!');
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return $this->respondCreated(['user' => $user], 'Registration successful!');
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->getProfile();
        return $this->respondSuccess($user, 'Profile successful!');
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->respondSuccess(null, 'Logout successful!');
    }
}
