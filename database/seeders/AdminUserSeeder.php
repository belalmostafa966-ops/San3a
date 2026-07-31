<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => '01000000000'], // الرقم المعتمد للـ Admin
            [
                'name' => 'System Admin',
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );
    }
}