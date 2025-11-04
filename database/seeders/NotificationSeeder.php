<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Order;
use App\Models\Job;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $orders = Order::all();
        $jobs = Job::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run other seeders first.');
            return;
        }

        $notifications = [
            // Order notifications
            [
                'user_id' => $users->where('role', 'farmer')->first()->id,
                'type' => 'order_created',
                'title' => 'Order Created Successfully',
                'message' => 'Your order #ORD-000001 has been created and is pending technician assignment.',
                'data' => ['order_id' => $orders->first()->id ?? 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'farmer')->first()->id,
                'type' => 'order_assigned',
                'title' => 'Technician Assigned',
                'message' => 'A technician has been assigned to your order. You will be notified when they accept the job.',
                'data' => ['order_id' => $orders->first()->id ?? 1, 'technician_id' => 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'technician')->first()->id,
                'type' => 'job_assigned',
                'title' => 'New Job Assigned',
                'message' => 'A new job has been assigned to you. Please review and accept if available.',
                'data' => ['job_id' => $jobs->first()->id ?? 1, 'order_id' => $orders->first()->id ?? 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'farmer')->first()->id,
                'type' => 'job_accepted',
                'title' => 'Job Accepted',
                'message' => 'The technician has accepted your job and will visit at the scheduled time.',
                'data' => ['job_id' => $jobs->first()->id ?? 1, 'technician_id' => 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'farmer')->first()->id,
                'type' => 'job_completed',
                'title' => 'Job Completed',
                'message' => 'Your AI service has been completed. Please confirm the service and provide feedback.',
                'data' => ['job_id' => $jobs->first()->id ?? 1, 'technician_id' => 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'admin')->first()->id,
                'type' => 'new_farmer_registration',
                'title' => 'New Farmer Registration',
                'message' => 'A new farmer has registered and requires approval.',
                'data' => ['farmer_id' => 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'admin')->first()->id,
                'type' => 'new_technician_registration',
                'title' => 'New Technician Registration',
                'message' => 'A new technician has registered and requires approval.',
                'data' => ['technician_id' => 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'technician')->first()->id,
                'type' => 'payment_received',
                'title' => 'Payment Received',
                'message' => 'You have received a payment of Rs. 2,500 for job completion.',
                'data' => ['amount' => 2500, 'job_id' => $jobs->first()->id ?? 1],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'farmer')->first()->id,
                'type' => 'pregnancy_confirmed',
                'title' => 'Pregnancy Confirmed',
                'message' => 'Great news! Your cow\'s pregnancy has been confirmed. Regular monitoring is recommended.',
                'data' => ['job_id' => $jobs->first()->id ?? 1, 'cow_status' => 'pregnant'],
                'is_read' => false,
            ],
            [
                'user_id' => $users->where('role', 'farmer')->first()->id,
                'type' => 'delivery_reminder',
                'title' => 'Delivery Reminder',
                'message' => 'Your cow is approaching the delivery date. Please prepare for the upcoming birth.',
                'data' => ['job_id' => $jobs->first()->id ?? 1, 'expected_delivery' => '2024-06-15'],
                'is_read' => false,
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Notification seeder completed successfully!');
    }
}