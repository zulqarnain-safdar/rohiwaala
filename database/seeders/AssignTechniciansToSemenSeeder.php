<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BullSemen;
use App\Models\Technician;

class AssignTechniciansToSemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all technicians
        $technicians = Technician::all();
        
        if ($technicians->isEmpty()) {
            $this->command->warn('No technicians found. Please run the technician seeder first.');
            return;
        }

        // Get all semen products
        $semenProducts = BullSemen::all();
        
        if ($semenProducts->isEmpty()) {
            $this->command->warn('No semen products found. Please run the semen seeder first.');
            return;
        }

        // Assign technicians to semen products
        foreach ($semenProducts as $index => $semen) {
            // Assign technician in round-robin fashion
            $technician = $technicians[$index % $technicians->count()];
            // Use the many-to-many relationship with quantity
            $semen->technicians()->attach($technician->id, ['quantity' => rand(5, 20)]);
        }

        $this->command->info('Successfully assigned technicians to semen products.');
    }
}