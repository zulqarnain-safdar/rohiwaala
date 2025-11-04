<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Farmer;
use App\Models\CattleFarm;
use Illuminate\Support\Facades\Hash;

class FarmerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farmers = [
            [
                'user' => [
                    'name' => 'Abdul Waheed',
                    'mobile' => '+923001234567',
                    'role' => 'farmer',
                    'password' => Hash::make('password123'),
                ],
                'farmer' => [
                    'name' => 'Abdul Waheed',
                    'mobile' => '+923001234567',
                    'date_of_birth' => '1985-03-15',
                    'status' => 'active',
                ],
                'farms' => [
                    [
                        'name' => 'Green Pastures Farm',
                        'father_name' => 'Muhammad Hassan',
                        'latitude' => '31.5204',
                        'longitude' => '74.3587',
                        'number_of_cows' => 25,
                        'number_of_calves' => 8,
                        'number_of_bulls' => 2,
                        'number_of_goats' => 15,
                    ],
                    [
                        'name' => 'Meadow View Farm',
                        'father_name' => 'Muhammad Hassan',
                        'latitude' => '31.5304',
                        'longitude' => '74.3687',
                        'number_of_cows' => 20,
                        'number_of_calves' => 5,
                        'number_of_bulls' => 1,
                        'number_of_goats' => 10,
                    ],
                ],
            ],
            [
                'user' => [
                    'name' => 'Fatima Khan',
                    'mobile' => '+923001234568',
                    'role' => 'farmer',
                    'password' => Hash::make('password123'),
                ],
                'farmer' => [
                    'name' => 'Fatima Khan',
                    'mobile' => '+923001234568',
                    'date_of_birth' => '1990-07-22',
                    'status' => 'active',
                ],
                'farms' => [
                    [
                        'name' => 'Sunrise Dairy Farm',
                        'father_name' => 'Ahmad Khan',
                        'latitude' => '31.5404',
                        'longitude' => '74.3787',
                        'number_of_cows' => 30,
                        'number_of_calves' => 12,
                        'number_of_bulls' => 3,
                        'number_of_goats' => 20,
                    ],
                ],
            ],
            [
                'user' => [
                    'name' => 'Muhammad Ali',
                    'mobile' => '+923001234569',
                    'role' => 'farmer',
                    'password' => Hash::make('password123'),
                ],
                'farmer' => [
                    'name' => 'Muhammad Ali',
                    'mobile' => '+923001234569',
                    'date_of_birth' => '1978-11-08',
                    'status' => 'active',
                ],
                'farms' => [
                    [
                        'name' => 'Valley Ranch',
                        'father_name' => 'Hassan Ali',
                        'latitude' => '31.5504',
                        'longitude' => '74.3887',
                        'number_of_cows' => 15,
                        'number_of_calves' => 6,
                        'number_of_bulls' => 1,
                        'number_of_goats' => 8,
                    ],
                ],
            ],
        ];

        foreach ($farmers as $farmerData) {
            // Create user
            $user = User::create($farmerData['user']);
            
            // Create farmer profile
            $farmer = Farmer::create(array_merge($farmerData['farmer'], ['user_id' => $user->id]));
            
            // Create cattle farms
            foreach ($farmerData['farms'] as $farmData) {
                CattleFarm::create(array_merge($farmData, ['farmer_id' => $farmer->id]));
            }
        }
    }
}