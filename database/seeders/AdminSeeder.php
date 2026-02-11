<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //1. reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //2. create basic roles
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $roleTeacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'api']);
        $roleStudent = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'api']);

        //3. create admin account
        $user = User::updateOrCreate([
           'username' => 'admin',
           'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'is_active' => true,
        ]);

        //4. create user profile
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone_number' => '123354353',
                'gender'       => 'Male',
                'dob'          => '1990-01-01',
                'bio'          => 'Admin system',
            ]
        );

        if (!$user->hasRole($roleAdmin)) {
            $user->assignRole($roleAdmin);
        }

        echo "created admin role \n";
    }
}
