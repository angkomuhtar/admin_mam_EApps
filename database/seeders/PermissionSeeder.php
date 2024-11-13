<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Permission::create(['name'=> 'role_permission']);
        $role = Role::create(['name'=> 'developer']);

        $role->syncPermission(['role_permission']);

        $user = User::find(1);
        $user->assignRole('developer');
    }
}
