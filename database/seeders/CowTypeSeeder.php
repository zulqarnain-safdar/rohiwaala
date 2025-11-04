<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CowType;

class CowTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cowTypes = [
            [
                'name' => 'Heifer',
                'description' => 'A young female cow that has not yet given birth',
                'is_active' => true
            ],
            [
                'name' => 'Cow',
                'description' => 'A mature female cow that has given birth',
                'is_active' => true
            ],
            [
                'name' => 'Pregnant Cow',
                'description' => 'A cow that is currently pregnant',
                'is_active' => true
            ],
            [
                'name' => 'Lactating Cow',
                'description' => 'A cow that is currently producing milk',
                'is_active' => true
            ]
        ];

        foreach ($cowTypes as $cowType) {
            CowType::create($cowType);
        }
    }
}
