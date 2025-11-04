<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BullSemen;
use App\Models\Technician;

class AssignMultipleTechniciansToSemenSeeder extends Seeder
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

        // Clear existing relationships
        \DB::table('bull_semen_technician')->truncate();

        // Assign multiple technicians to each semen product
        foreach ($semenProducts as $semen) {
            // Randomly select 2-4 technicians for each semen product
            $numTechnicians = rand(2, min(4, $technicians->count()));
            $selectedTechnicians = $technicians->random($numTechnicians);
            
            foreach ($selectedTechnicians as $technician) {
                $semen->technicians()->attach($technician->id);
            }
        }

        $this->command->info('Successfully assigned multiple technicians to semen products.');
    }
}