<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'approve_users']);
        Permission::create(['name' => 'manage_tests']);
        Permission::create(['name' => 'take_tests']);
        Permission::create(['name' => 'view_results']);

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo(['manage_tests']);

        $student = Role::create(['name' => 'student']);
        $student->givePermissionTo(['take_tests', 'view_results']);

        // Create default super admin
        $admin = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ]);

        $admin->assignRole('super_admin');
    }
}
