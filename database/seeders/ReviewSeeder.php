<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Job;
use App\Models\Farmer;
use App\Models\Technician;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = Job::with(['order.farmer', 'technician'])->where('status', 'completed')->get();
        $farmers = Farmer::all();
        $technicians = Technician::where('status', 'active')->get();

        if ($jobs->isEmpty() || $farmers->isEmpty() || $technicians->isEmpty()) {
            $this->command->warn('No completed jobs, farmers, or active technicians found. Please run other seeders first.');
            return;
        }

        $reviews = [
            [
                'job_id' => $jobs->first()->id,
                'farmer_id' => $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->first()->technician_id,
                'rating' => 5,
                'comment' => 'Excellent service! The technician was very professional and punctual. The AI procedure was completed successfully and my cow is now pregnant. Highly recommended!',
                'rating_breakdown' => [
                    'service_quality' => 5,
                    'punctuality' => 5,
                    'professionalism' => 5,
                    'communication' => 4,
                    'equipment_quality' => 5
                ],
                'is_verified' => true,
            ],
            [
                'job_id' => $jobs->skip(1)->first()->id ?? $jobs->first()->id,
                'farmer_id' => $jobs->skip(1)->first()->order->farmer_id ?? $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->skip(1)->first()->technician_id ?? $jobs->first()->technician_id,
                'rating' => 4,
                'comment' => 'Very good service overall. The technician was knowledgeable and the procedure went smoothly. My cow responded well to the treatment.',
                'rating_breakdown' => [
                    'service_quality' => 4,
                    'punctuality' => 4,
                    'professionalism' => 5,
                    'communication' => 4,
                    'equipment_quality' => 4
                ],
                'is_verified' => true,
            ],
            [
                'job_id' => $jobs->skip(2)->first()->id ?? $jobs->first()->id,
                'farmer_id' => $jobs->skip(2)->first()->order->farmer_id ?? $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->skip(2)->first()->technician_id ?? $jobs->first()->technician_id,
                'rating' => 5,
                'comment' => 'Outstanding work! The technician used high-quality equipment and provided excellent guidance throughout the process. My cow delivered a healthy calf.',
                'rating_breakdown' => [
                    'service_quality' => 5,
                    'punctuality' => 5,
                    'professionalism' => 5,
                    'communication' => 5,
                    'equipment_quality' => 5
                ],
                'is_verified' => true,
            ],
            [
                'job_id' => $jobs->skip(3)->first()->id ?? $jobs->first()->id,
                'farmer_id' => $jobs->skip(3)->first()->order->farmer_id ?? $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->skip(3)->first()->technician_id ?? $jobs->first()->technician_id,
                'rating' => 3,
                'comment' => 'Service was okay but there were some delays. The technician was knowledgeable but communication could have been better.',
                'rating_breakdown' => [
                    'service_quality' => 3,
                    'punctuality' => 2,
                    'professionalism' => 4,
                    'communication' => 3,
                    'equipment_quality' => 4
                ],
                'is_verified' => true,
            ],
            [
                'job_id' => $jobs->skip(4)->first()->id ?? $jobs->first()->id,
                'farmer_id' => $jobs->skip(4)->first()->order->farmer_id ?? $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->skip(4)->first()->technician_id ?? $jobs->first()->technician_id,
                'rating' => 4,
                'comment' => 'Good service overall. The technician was professional and the AI procedure was successful. Would use again.',
                'rating_breakdown' => [
                    'service_quality' => 4,
                    'punctuality' => 4,
                    'professionalism' => 4,
                    'communication' => 4,
                    'equipment_quality' => 4
                ],
                'is_verified' => true,
            ],
            [
                'job_id' => $jobs->skip(5)->first()->id ?? $jobs->first()->id,
                'farmer_id' => $jobs->skip(5)->first()->order->farmer_id ?? $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->skip(5)->first()->technician_id ?? $jobs->first()->technician_id,
                'rating' => 5,
                'comment' => 'Exceptional service! The technician was very experienced and provided valuable advice. The procedure was completed with great care.',
                'rating_breakdown' => [
                    'service_quality' => 5,
                    'punctuality' => 5,
                    'professionalism' => 5,
                    'communication' => 5,
                    'equipment_quality' => 5
                ],
                'is_verified' => true,
            ],
            [
                'job_id' => $jobs->skip(6)->first()->id ?? $jobs->first()->id,
                'farmer_id' => $jobs->skip(6)->first()->order->farmer_id ?? $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->skip(6)->first()->technician_id ?? $jobs->first()->technician_id,
                'rating' => 2,
                'comment' => 'Disappointed with the service. The technician was late and the equipment seemed outdated. The procedure was not successful.',
                'rating_breakdown' => [
                    'service_quality' => 2,
                    'punctuality' => 1,
                    'professionalism' => 3,
                    'communication' => 2,
                    'equipment_quality' => 2
                ],
                'is_verified' => true,
            ],
            [
                'job_id' => $jobs->skip(7)->first()->id ?? $jobs->first()->id,
                'farmer_id' => $jobs->skip(7)->first()->order->farmer_id ?? $jobs->first()->order->farmer_id,
                'technician_id' => $jobs->skip(7)->first()->technician_id ?? $jobs->first()->technician_id,
                'rating' => 4,
                'comment' => 'Good service with room for improvement. The technician was knowledgeable but could have been more communicative.',
                'rating_breakdown' => [
                    'service_quality' => 4,
                    'punctuality' => 4,
                    'professionalism' => 4,
                    'communication' => 3,
                    'equipment_quality' => 4
                ],
                'is_verified' => true,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }

        $this->command->info('Review seeder completed successfully!');
    }
}