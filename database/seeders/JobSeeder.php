<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\Order;
use App\Models\Technician;
use Carbon\Carbon;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all orders and technicians
        $orders = Order::all();
        $technicians = Technician::where('status', 'active')->get();

        if ($orders->isEmpty() || $technicians->isEmpty()) {
            $this->command->warn('No orders or active technicians found. Please run OrderSeeder and TechnicianSeeder first.');
            return;
        }

        $jobs = [
            // Completed Jobs
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'completed',
                'progress_status' => 'administered',
                'estimated_visit_at' => Carbon::now()->subDays(5)->setTime(10, 0),
                'actual_visit_at' => Carbon::now()->subDays(5)->setTime(10, 30),
                'visit_latitude' => '31.5204',
                'visit_longitude' => '74.3587',
                'technician_notes' => 'Successfully administered AI. Cow was in proper heat cycle. Used high-quality semen straw.',
                'farmer_notes' => 'Excellent service. Technician was punctual and professional.',
                'rating' => 5,
                'amount_paid' => 2500.00,
                'semen_straw_code' => 'SS-2024-001',
                'semen_straw_image' => 'semen_straw_001.jpg',
                'cow_status' => 'pregnant',
                'delivery_notes' => 'Cow is showing signs of pregnancy. Regular monitoring recommended.',
                'calf_image' => null,
            ],
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'completed',
                'progress_status' => 'administered',
                'estimated_visit_at' => Carbon::now()->subDays(8)->setTime(14, 0),
                'actual_visit_at' => Carbon::now()->subDays(8)->setTime(14, 15),
                'visit_latitude' => '24.8607',
                'visit_longitude' => '67.0011',
                'technician_notes' => 'AI procedure completed successfully. Cow responded well to treatment.',
                'farmer_notes' => 'Very satisfied with the service quality.',
                'rating' => 4,
                'amount_paid' => 2200.00,
                'semen_straw_code' => 'SS-2024-002',
                'semen_straw_image' => 'semen_straw_002.jpg',
                'cow_status' => 'pregnant',
                'delivery_notes' => 'Pregnancy confirmed. Expected delivery in 9 months.',
                'calf_image' => null,
            ],
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'completed',
                'progress_status' => 'administered',
                'estimated_visit_at' => Carbon::now()->subDays(12)->setTime(9, 0),
                'actual_visit_at' => Carbon::now()->subDays(12)->setTime(9, 45),
                'visit_latitude' => '33.6844',
                'visit_longitude' => '73.0479',
                'technician_notes' => 'Successful AI administration. Used premium breed semen.',
                'farmer_notes' => 'Outstanding service. Highly recommend this technician.',
                'rating' => 5,
                'amount_paid' => 3000.00,
                'semen_straw_code' => 'SS-2024-003',
                'semen_straw_image' => 'semen_straw_003.jpg',
                'cow_status' => 'delivered',
                'delivery_notes' => 'Healthy calf delivered successfully. Both mother and calf are doing well.',
                'calf_image' => 'calf_001.jpg',
            ],

            // In Progress Jobs
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'accepted',
                'progress_status' => 'accepted',
                'estimated_visit_at' => Carbon::now()->addDays(2)->setTime(11, 0),
                'actual_visit_at' => null,
                'visit_latitude' => '31.4504',
                'visit_longitude' => '73.1350',
                'technician_notes' => 'Job accepted. Will visit farm tomorrow morning.',
                'farmer_notes' => 'Looking forward to the service.',
                'rating' => null,
                'amount_paid' => null,
                'semen_straw_code' => null,
                'semen_straw_image' => null,
                'cow_status' => null,
                'delivery_notes' => null,
                'calf_image' => null,
            ],
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'accepted',
                'progress_status' => 'accepted',
                'estimated_visit_at' => Carbon::now()->addDays(1)->setTime(15, 30),
                'actual_visit_at' => null,
                'visit_latitude' => '30.1575',
                'visit_longitude' => '71.5249',
                'technician_notes' => 'Accepted job. Will prepare necessary equipment.',
                'farmer_notes' => 'Please bring high-quality semen straw.',
                'rating' => null,
                'amount_paid' => null,
                'semen_straw_code' => null,
                'semen_straw_image' => null,
                'cow_status' => null,
                'delivery_notes' => null,
                'calf_image' => null,
            ],

            // Pending Jobs
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'pending',
                'progress_status' => 'pending',
                'estimated_visit_at' => Carbon::now()->addDays(3)->setTime(10, 0),
                'actual_visit_at' => null,
                'visit_latitude' => '34.0151',
                'visit_longitude' => '71.5249',
                'technician_notes' => null,
                'farmer_notes' => 'Urgent AI service needed for my dairy cow.',
                'rating' => null,
                'amount_paid' => null,
                'semen_straw_code' => null,
                'semen_straw_image' => null,
                'cow_status' => null,
                'delivery_notes' => null,
                'calf_image' => null,
            ],
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'pending',
                'progress_status' => 'pending',
                'estimated_visit_at' => Carbon::now()->addDays(5)->setTime(16, 0),
                'actual_visit_at' => null,
                'visit_latitude' => '32.4945',
                'visit_longitude' => '74.5229',
                'technician_notes' => null,
                'farmer_notes' => 'Need AI service for multiple cows in my farm.',
                'rating' => null,
                'amount_paid' => null,
                'semen_straw_code' => null,
                'semen_straw_image' => null,
                'cow_status' => null,
                'delivery_notes' => null,
                'calf_image' => null,
            ],

            // Failed Jobs
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'failed',
                'progress_status' => 'failed_no_heat',
                'estimated_visit_at' => Carbon::now()->subDays(3)->setTime(9, 0),
                'actual_visit_at' => Carbon::now()->subDays(3)->setTime(9, 30),
                'visit_latitude' => '25.3960',
                'visit_longitude' => '68.3578',
                'technician_notes' => 'Cow was not in proper heat cycle. Recommended to wait for next cycle.',
                'farmer_notes' => 'Disappointed but understand the situation.',
                'rating' => 2,
                'amount_paid' => 500.00,
                'semen_straw_code' => null,
                'semen_straw_image' => null,
                'cow_status' => null,
                'delivery_notes' => 'Rescheduled for next heat cycle.',
                'calf_image' => null,
            ],

            // Rejected Jobs
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'rejected',
                'progress_status' => 'rejected',
                'estimated_visit_at' => Carbon::now()->addDays(4)->setTime(13, 0),
                'actual_visit_at' => null,
                'visit_latitude' => '30.1798',
                'visit_longitude' => '71.4920',
                'technician_notes' => 'Unable to accept due to scheduling conflict. Recommended another technician.',
                'farmer_notes' => 'Looking for alternative technician.',
                'rating' => null,
                'amount_paid' => null,
                'semen_straw_code' => null,
                'semen_straw_image' => null,
                'cow_status' => null,
                'delivery_notes' => null,
                'calf_image' => null,
            ],

            // More Completed Jobs for Statistics
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'completed',
                'progress_status' => 'administered',
                'estimated_visit_at' => Carbon::now()->subDays(15)->setTime(8, 0),
                'actual_visit_at' => Carbon::now()->subDays(15)->setTime(8, 20),
                'visit_latitude' => '31.5204',
                'visit_longitude' => '74.3587',
                'technician_notes' => 'Successful AI procedure. Used imported semen straw.',
                'farmer_notes' => 'Excellent work. Very professional.',
                'rating' => 5,
                'amount_paid' => 2800.00,
                'semen_straw_code' => 'SS-2024-004',
                'semen_straw_image' => 'semen_straw_004.jpg',
                'cow_status' => 'pregnant',
                'delivery_notes' => 'Pregnancy progressing well. Regular checkups recommended.',
                'calf_image' => null,
            ],
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'completed',
                'progress_status' => 'administered',
                'estimated_visit_at' => Carbon::now()->subDays(20)->setTime(12, 0),
                'actual_visit_at' => Carbon::now()->subDays(20)->setTime(12, 15),
                'visit_latitude' => '24.8607',
                'visit_longitude' => '67.0011',
                'technician_notes' => 'AI completed successfully. Cow in good health.',
                'farmer_notes' => 'Satisfied with the service quality.',
                'rating' => 4,
                'amount_paid' => 2400.00,
                'semen_straw_code' => 'SS-2024-005',
                'semen_straw_image' => 'semen_straw_005.jpg',
                'cow_status' => 'miscarried',
                'delivery_notes' => 'Unfortunately, pregnancy did not continue. Will try again next cycle.',
                'calf_image' => null,
            ],
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'completed',
                'progress_status' => 'administered',
                'estimated_visit_at' => Carbon::now()->subDays(25)->setTime(10, 30),
                'actual_visit_at' => Carbon::now()->subDays(25)->setTime(10, 45),
                'visit_latitude' => '33.6844',
                'visit_longitude' => '73.0479',
                'technician_notes' => 'Successful AI administration. High-quality semen used.',
                'farmer_notes' => 'Great service. Will definitely use again.',
                'rating' => 5,
                'amount_paid' => 2600.00,
                'semen_straw_code' => 'SS-2024-006',
                'semen_straw_image' => 'semen_straw_006.jpg',
                'cow_status' => 'delivered',
                'delivery_notes' => 'Healthy male calf delivered. Both doing well.',
                'calf_image' => 'calf_002.jpg',
            ],
            [
                'order_id' => $orders->random()->id,
                'technician_id' => $technicians->random()->id,
                'status' => 'completed',
                'progress_status' => 'administered',
                'estimated_visit_at' => Carbon::now()->subDays(30)->setTime(14, 0),
                'actual_visit_at' => Carbon::now()->subDays(30)->setTime(14, 30),
                'visit_latitude' => '31.4504',
                'visit_longitude' => '73.1350',
                'technician_notes' => 'AI procedure completed. Excellent results expected.',
                'farmer_notes' => 'Very professional and knowledgeable technician.',
                'rating' => 5,
                'amount_paid' => 2700.00,
                'semen_straw_code' => 'SS-2024-007',
                'semen_straw_image' => 'semen_straw_007.jpg',
                'cow_status' => 'pregnant',
                'delivery_notes' => 'Pregnancy confirmed. Regular monitoring in progress.',
                'calf_image' => null,
            ],
        ];

        foreach ($jobs as $jobData) {
            Job::create($jobData);
        }

        $this->command->info('Job seeder completed successfully!');
    }
}

