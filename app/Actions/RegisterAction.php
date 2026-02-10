<?php

namespace App\Actions;

use App\Constants\RoleConstants;
use App\Models\User;
use Exception;

class RegisterAction extends BaseAction
{
    /**
     * Execute the user registration process.
     *
     * @param array $data Validated input data
     * @return User The newly created user instance
     * @throws Exception If the transaction fails
     */
    public function execute(array $data): User
    {
        return $this->transaction(function () use ($data) {
            //1. Created the User record
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'is_active' => true,
            ]);
            //2. Assign default Student role using Spatie Permission
            $user->assignRole(RoleConstants::STUDENT);

            return $user;
        });
    }
}
