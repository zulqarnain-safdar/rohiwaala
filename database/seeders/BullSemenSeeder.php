<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breed;
use App\Models\BullSemen;

class BullSemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holsteinBreed = Breed::where('name', 'Holstein')->first();
        $jerseyBreed = Breed::where('name', 'Jersey')->first();
        $guernseyBreed = Breed::where('name', 'Guernsey')->first();

        $semenProducts = [
            // Holstein Bulls
            [
                'breed_id' => $holsteinBreed->id,
                'name' => 'Holstein Champion #001',
                'description' => 'High milk production genetics with excellent conformation.',
                'price' => 150.00,
                'stock_quantity' => 50,
                'is_active' => true,
            ],
            [
                'breed_id' => $holsteinBreed->id,
                'name' => 'Holstein Elite #002',
                'description' => 'Superior milk yield and protein content genetics.',
                'price' => 175.00,
                'stock_quantity' => 30,
                'is_active' => true,
            ],
            [
                'breed_id' => $holsteinBreed->id,
                'name' => 'Holstein Premium #003',
                'description' => 'Top-tier genetics for commercial dairy operations.',
                'price' => 200.00,
                'stock_quantity' => 25,
                'is_active' => true,
            ],
            // Jersey Bulls
            [
                'breed_id' => $jerseyBreed->id,
                'name' => 'Jersey Master #101',
                'description' => 'High butterfat content and efficient feed conversion.',
                'price' => 120.00,
                'stock_quantity' => 40,
                'is_active' => true,
            ],
            [
                'breed_id' => $jerseyBreed->id,
                'name' => 'Jersey Superior #102',
                'description' => 'Excellent milk quality with high protein content.',
                'price' => 140.00,
                'stock_quantity' => 35,
                'is_active' => true,
            ],
            // Guernsey Bulls
            [
                'breed_id' => $guernseyBreed->id,
                'name' => 'Guernsey Gold #201',
                'description' => 'Golden milk production with excellent temperament.',
                'price' => 130.00,
                'stock_quantity' => 20,
                'is_active' => true,
            ],
            [
                'breed_id' => $guernseyBreed->id,
                'name' => 'Guernsey Classic #202',
                'description' => 'Traditional Guernsey genetics for small farms.',
                'price' => 110.00,
                'stock_quantity' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($semenProducts as $semen) {
            BullSemen::create($semen);
        }
    }
}