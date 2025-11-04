<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use App\Models\Job;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $jobs = Job::with(['order.farmer', 'technician'])->get();

        if ($users->isEmpty() || $jobs->isEmpty()) {
            $this->command->warn('No users or jobs found. Please run other seeders first.');
            return;
        }

        $farmers = $users->where('role', 'farmer');
        $technicians = $users->where('role', 'technician');

        if ($farmers->isEmpty() || $technicians->isEmpty()) {
            $this->command->warn('No farmers or technicians found. Please run other seeders first.');
            return;
        }

        $messages = [
            // Farmer to Technician messages
            [
                'sender_id' => $farmers->first()->id,
                'receiver_id' => $technicians->first()->id,
                'job_id' => $jobs->first()->id,
                'message' => 'Hello, I have a cow that is in heat and needs AI service. When can you visit?',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subHours(2),
            ],
            [
                'sender_id' => $technicians->first()->id,
                'receiver_id' => $farmers->first()->id,
                'job_id' => $jobs->first()->id,
                'message' => 'Hello! I can visit tomorrow morning around 10 AM. Is that convenient for you?',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subHours(1),
            ],
            [
                'sender_id' => $farmers->first()->id,
                'receiver_id' => $technicians->first()->id,
                'job_id' => $jobs->first()->id,
                'message' => 'Yes, 10 AM works perfectly. My farm address is: 123 Farm Road, Lahore. I will send you the exact location.',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subMinutes(30),
            ],
            [
                'sender_id' => $technicians->first()->id,
                'receiver_id' => $farmers->first()->id,
                'job_id' => $jobs->first()->id,
                'message' => 'Perfect! I have the location. I will bring high-quality semen straw and all necessary equipment. See you tomorrow!',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => false,
            ],
            // More conversation
            [
                'sender_id' => $farmers->skip(1)->first()->id ?? $farmers->first()->id,
                'receiver_id' => $technicians->skip(1)->first()->id ?? $technicians->first()->id,
                'job_id' => $jobs->skip(1)->first()->id ?? $jobs->first()->id,
                'message' => 'Hi, I need urgent AI service for my dairy cow. She is in heat right now.',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subHours(4),
            ],
            [
                'sender_id' => $technicians->skip(1)->first()->id ?? $technicians->first()->id,
                'receiver_id' => $farmers->skip(1)->first()->id ?? $farmers->first()->id,
                'job_id' => $jobs->skip(1)->first()->id ?? $jobs->first()->id,
                'message' => 'I can come within 2 hours. Please share your location and I will be there as soon as possible.',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subHours(3),
            ],
            [
                'sender_id' => $farmers->skip(1)->first()->id ?? $farmers->first()->id,
                'receiver_id' => $technicians->skip(1)->first()->id ?? $technicians->first()->id,
                'job_id' => $jobs->skip(1)->first()->id ?? $jobs->first()->id,
                'message' => 'Thank you! Here is my location: https://maps.google.com/...',
                'message_type' => 'location',
                'attachment_url' => 'https://maps.google.com/location123',
                'is_read' => true,
                'read_at' => now()->subHours(2),
            ],
            // Post-service messages
            [
                'sender_id' => $technicians->first()->id,
                'receiver_id' => $farmers->first()->id,
                'job_id' => $jobs->first()->id,
                'message' => 'The AI procedure has been completed successfully. Your cow responded well to the treatment. Please monitor her for the next few days.',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subDays(1),
            ],
            [
                'sender_id' => $farmers->first()->id,
                'receiver_id' => $technicians->first()->id,
                'job_id' => $jobs->first()->id,
                'message' => 'Thank you for the excellent service! I will keep you updated on her progress.',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subDays(1),
            ],
            [
                'sender_id' => $farmers->first()->id,
                'receiver_id' => $technicians->first()->id,
                'job_id' => $jobs->first()->id,
                'message' => 'Great news! The pregnancy test came back positive. Thank you for your professional service!',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => false,
            ],
            // General messages
            [
                'sender_id' => $farmers->skip(2)->first()->id ?? $farmers->first()->id,
                'receiver_id' => $technicians->skip(2)->first()->id ?? $technicians->first()->id,
                'job_id' => null,
                'message' => 'Hello, I am looking for a reliable technician for regular AI services. Do you provide monthly packages?',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => true,
                'read_at' => now()->subDays(2),
            ],
            [
                'sender_id' => $technicians->skip(2)->first()->id ?? $technicians->first()->id,
                'receiver_id' => $farmers->skip(2)->first()->id ?? $farmers->first()->id,
                'job_id' => null,
                'message' => 'Yes, I do provide monthly packages for regular customers. I can offer you a 10% discount for monthly services. Would you like to discuss the details?',
                'message_type' => 'text',
                'attachment_url' => null,
                'is_read' => false,
            ],
        ];

        foreach ($messages as $message) {
            Message::create($message);
        }

        $this->command->info('Message seeder completed successfully!');
    }
}