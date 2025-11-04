<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Farmer;
use App\Models\CattleFarm;
use App\Models\BullSemen;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $farmers = Farmer::with('cattleFarms')->get();
        $semenProducts = BullSemen::all();

        if ($farmers->isEmpty() || $semenProducts->isEmpty()) {
            $this->command->warn('No farmers or semen products found. Please run other seeders first.');
            return;
        }

        $orders = [
            [
                'farmer_id' => $farmers[0]->id,
                'cattle_farm_id' => $farmers[0]->cattleFarms[0]->id,
                'order_number' => 'ORD-001',
                'status' => 'completed',
                'total_amount' => 300.00,
                'visit_fee' => 50.00,
                'notes' => 'Urgent order for heat cycle',
                'items' => [
                    [
                        'bull_semen_id' => $semenProducts[0]->id,
                        'quantity' => 2,
                        'unit_price' => 150.00,
                        'total_price' => 300.00,
                        'cow_type' => 'Holstein',
                        'cow_age' => 3,
                        'heat_started_at' => now()->subHours(6),
                    ],
                ],
            ],
            [
                'farmer_id' => $farmers[0]->id,
                'cattle_farm_id' => $farmers[0]->cattleFarms[1]->id,
                'order_number' => 'ORD-002',
                'status' => 'pending',
                'total_amount' => 250.00,
                'visit_fee' => 50.00,
                'notes' => 'Regular insemination order',
                'items' => [
                    [
                        'bull_semen_id' => $semenProducts[1]->id,
                        'quantity' => 1,
                        'unit_price' => 175.00,
                        'total_price' => 175.00,
                        'cow_type' => 'Jersey',
                        'cow_age' => 4,
                        'heat_started_at' => now()->subHours(2),
                    ],
                    [
                        'bull_semen_id' => $semenProducts[2]->id,
                        'quantity' => 1,
                        'unit_price' => 75.00,
                        'total_price' => 75.00,
                        'cow_type' => 'Holstein',
                        'cow_age' => 2,
                        'heat_started_at' => now()->subHours(1),
                    ],
                ],
            ],
            [
                'farmer_id' => $farmers[1]->id,
                'cattle_farm_id' => $farmers[1]->cattleFarms[0]->id,
                'order_number' => 'ORD-003',
                'status' => 'assigned',
                'total_amount' => 400.00,
                'visit_fee' => 50.00,
                'notes' => 'Multiple cows in heat',
                'items' => [
                    [
                        'bull_semen_id' => $semenProducts[3]->id,
                        'quantity' => 2,
                        'unit_price' => 200.00,
                        'total_price' => 400.00,
                        'cow_type' => 'Holstein',
                        'cow_age' => 5,
                        'heat_started_at' => now()->subHours(4),
                    ],
                ],
            ],
            [
                'farmer_id' => $farmers[2]->id,
                'cattle_farm_id' => $farmers[2]->cattleFarms[0]->id,
                'order_number' => 'ORD-004',
                'status' => 'in_progress',
                'total_amount' => 180.00,
                'visit_fee' => 50.00,
                'notes' => 'Small farm order',
                'items' => [
                    [
                        'bull_semen_id' => $semenProducts[4]->id,
                        'quantity' => 1,
                        'unit_price' => 130.00,
                        'total_price' => 130.00,
                        'cow_type' => 'Guernsey',
                        'cow_age' => 3,
                        'heat_started_at' => now()->subHours(3),
                    ],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);

            // Create order
            $order = Order::create($orderData);

            // Create order items
            foreach ($items as $itemData) {
                OrderItem::create(array_merge($itemData, ['order_id' => $order->id]));
            }
        }
    }
}