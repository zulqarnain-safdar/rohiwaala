<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breed;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $breeds = [
            [
                'name' => 'Holstein',
                'description' => 'Holstein cattle are the most common dairy cattle breed, known for their high milk production.',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Jersey',
                'description' => 'Jersey cattle are a small breed known for their high butterfat content in milk.',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Guernsey',
                'description' => 'Guernsey cattle are known for their golden milk and docile temperament.',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Ayrshire',
                'description' => 'Ayrshire cattle are known for their hardiness and ability to adapt to various climates.',
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Brown Swiss',
                'description' => 'Brown Swiss cattle are known for their longevity and high milk production.',
                'image' => null,
                'is_active' => true,
            ],
        ];

        foreach ($breeds as $breed) {
            Breed::create($breed);
        }
    }
}