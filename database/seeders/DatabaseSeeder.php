<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            BreedSeeder::class,
            BullSemenSeeder::class,
            FarmerSeeder::class,
            TechnicianSeeder::class,
            OrderSeeder::class,
            JobSeeder::class,
            PaymentSeeder::class,
            ReviewSeeder::class,
            MessageSeeder::class,
            NotificationSeeder::class,
            CowTypeSeeder::class,
        ]);
    }
}
