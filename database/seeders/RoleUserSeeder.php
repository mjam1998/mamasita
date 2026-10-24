<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['super-admin', 'user', 'mama'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }

        $user = User::firstOrCreate(
            ['mobile' => '09123456789'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'national_code' => '6000000006',
                'password' => Hash::make('1234'),
                'is_active' => true,
            ]
        );

        $user->syncRoles($roles);
    }
}
