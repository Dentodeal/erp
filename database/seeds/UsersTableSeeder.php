<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Super Admin','guard_name'=>'api']);
        $user = \App\User::create([
            'name' => 'Shabeeb Ali O',
            'active' => true,
            'email' => 'shabeeboali@gmail.com',
            'password' => Hash::make('kinassery'),
        ]);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $user->assignRole($role);
        $permissions = Permission::all();
        $permission_ids = [];
        foreach($permissions as $permission)
        {
            $permission_ids[] = $permission->id;
        }
        $role->syncPermissions($permission_ids);

        Role::create(['name' => 'Admin','guard_name'=>'api']);
        Role::create(['name' => 'Sales','guard_name'=>'api']);
        Role::create(['name' => 'Warehouse','guard_name'=>'api']);
        Role::create(['name' => 'Accounts','guard_name'=>'api']);
    }
}
