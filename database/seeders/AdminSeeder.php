<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Admin User',
                'mobile' => '+923001234500',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ],
            [
                'name' => 'System Administrator',
                'mobile' => '+923001234501',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }
    }
}