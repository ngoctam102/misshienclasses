<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@misshien.com',
            'password' => Hash::make('admin123'),
            'is_approved' => true,
            'approved_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

        // Gán quyền trực tiếp cho super admin
        $permissions = Permission::all();
        $superAdmin->givePermissionTo($permissions);

        // Tạo một số học viên test
        $students = [
            [
                'name' => 'Học viên 1',
                'email' => 'student1@example.com',
                'password' => Hash::make('student123'),
                'is_approved' => true,
                'approved_at' => now(),
            ],
            [
                'name' => 'Học viên 2',
                'email' => 'student2@example.com',
                'password' => Hash::make('student123'),
                'is_approved' => false,
                'approved_at' => null,
                'approved_by' => null,
            ],
            [
                'name' => 'Học viên 3',
                'email' => 'student3@example.com',
                'password' => Hash::make('student123'),
                'is_approved' => true,
                'approved_at' => now(),
            ],
        ];

        foreach ($students as $student) {
            $user = User::create($student);
            $user->assignRole('student');
        }
    }
}
