<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
           [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
           ],

            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'role' => 'superadmin',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Dr. John Doe',
                'email' => 'doctor@example.com',
                'role' => 'doctor',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Receptionist',
                'email' => 'receptionist@example.com',
                'role' => 'receptionist',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Accountant',
                'email' => 'accountant@example.com',
                'role' => 'accountant',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Patient',
                'email' => 'patient@example.com',
                'role' => 'patient',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Laboratorist',
                'email' => 'laboratorist@example.com',
                'role' => 'laboratorist',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Pharmacist',
                'email' => 'pharmacist@example.com',
                'role' => 'pharmacist',
                'password' => Hash::make('123456'),
            ],
            [
                'name' => 'Nurse',
                'email' => 'nurse@example.com',
                'role' => 'nurse',
                'password' => Hash::make('123456'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
