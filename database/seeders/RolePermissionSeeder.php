<?php

namespace Database\Seeders;

use App\Constants\PermissionConstants;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // reset cache spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $matrix = PermissionConstants::getPermissionsByRole();

        foreach ($matrix as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'api']);
            $permissionModels = [];

            foreach ($permissions as $permissionName) {
                $permissionModels[] = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'api']);
            }
            $role->syncPermissions($permissionModels);
        }
        echo "Roles and Permissions have been created\n";
    }
}
