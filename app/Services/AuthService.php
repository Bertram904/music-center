<?php

namespace App\Services;

use App\Actions\RegisterAction;
use App\Exceptions\Auth\AccountLockedException;
use App\Exceptions\Auth\LoginFailedException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService extends BaseService
{
    /**
     * @param RegisterAction $registerAction
     */
    public function __construct(protected RegisterAction $registerAction) {}

    public function login(array $credentials): array
    {
        //1. Attempt to generate a token using the API guard
        if (! $token = $this->guard()->attempt($credentials)) {
            throw new LoginFailedException();
        }

        //2. Retrieve the authenticated user instance
        $user = $this->guard()->user();

        //3. Business Rule
        if (! $user->is_active) {
            $this->guard()->logout();
            throw new AccountLockedException();
        }

        return $this->respondWithToken($token);
    }

    /**
     * Handle the registration process.
     *
     * This method delegates the data persistence logic to the RegisterAction
     * to ensure database atomicity (Transaction safety).
     *
     * @param array $data The validated registration data.
     * @return User The newly created user instance.
     */
    public function register(array $data): User
    {
        return $this->registerAction->execute($data);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        //Dam bao token bi vo hieu hoa
        $this->guard()->logout(true);
    }

    /**
     * Format the token response structure
     *
     * @param string $token
     * @return array
     */

    /**
     * @return User
     */
    public function getProfile(): User
    {
        $user = $this->guard()->user();

        //Eager load roles and extended profile data
        return $user->load('roles', 'profile');
    }

    /**
     * Refresh the current token.
     *
     * This method invalidates the old token and returns a new one
     * with a fresh expiration time. Essential for long-lived sessions.
     *
     * @return array The formatted token response.
     */
    public function refresh(): array
    {
        // Refresh the token and get the new string
        $newToken = $this->guard()->refresh();

        return $this->respondWithToken($newToken);
    }

    /**
     * @param string $token
     * @return array
     */
    protected function respondWithToken(string $token): array
    {
        $user = $this->guard()->user();
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => $user->load('roles'),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ];
    }

    /**
     * Get the API Guard Instance
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }
}
