<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Farmer;
use App\Models\Technician;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::with(['farmer', 'jobs.technician'])->get();
        $farmers = Farmer::all();
        $technicians = Technician::where('status', 'active')->get();

        if ($orders->isEmpty() || $farmers->isEmpty() || $technicians->isEmpty()) {
            $this->command->warn('No orders, farmers, or active technicians found. Please run other seeders first.');
            return;
        }

        $payments = [
            // Completed payments
            [
                'order_id' => $orders->first()->id,
                'farmer_id' => $orders->first()->farmer_id,
                'technician_id' => $orders->first()?->jobs?->first()?->technician_id ?? $technicians->first()->id,
                'payment_method' => 'cash',
                'transaction_id' => 'TXN-' . str_pad(1, 8, '0', STR_PAD_LEFT),
                'amount' => 2500.00,
                'platform_fee' => 250.00,
                'technician_amount' => 2250.00,
                'status' => 'completed',
                'notes' => 'Payment received in cash after successful AI service',
                'paid_at' => Carbon::now()->subDays(5),
            ],
            [
                'order_id' => $orders->skip(1)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(1)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(1)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(1)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TXN-' . str_pad(2, 8, '0', STR_PAD_LEFT),
                'amount' => 2200.00,
                'platform_fee' => 220.00,
                'technician_amount' => 1980.00,
                'status' => 'completed',
                'notes' => 'Bank transfer payment for AI service',
                'paid_at' => Carbon::now()->subDays(8),
            ],
            [
                'order_id' => $orders->skip(2)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(2)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(2)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(2)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'mobile_payment',
                'transaction_id' => 'TXN-' . str_pad(3, 8, '0', STR_PAD_LEFT),
                'amount' => 3000.00,
                'platform_fee' => 300.00,
                'technician_amount' => 2700.00,
                'status' => 'completed',
                'notes' => 'Mobile payment via JazzCash',
                'paid_at' => Carbon::now()->subDays(12),
            ],
            [
                'order_id' => $orders->skip(3)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(3)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(3)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(3)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'cash',
                'transaction_id' => 'TXN-' . str_pad(4, 8, '0', STR_PAD_LEFT),
                'amount' => 2800.00,
                'platform_fee' => 280.00,
                'technician_amount' => 2520.00,
                'status' => 'completed',
                'notes' => 'Cash payment for premium AI service',
                'paid_at' => Carbon::now()->subDays(15),
            ],
            [
                'order_id' => $orders->skip(4)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(4)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(4)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(4)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TXN-' . str_pad(5, 8, '0', STR_PAD_LEFT),
                'amount' => 2400.00,
                'platform_fee' => 240.00,
                'technician_amount' => 2160.00,
                'status' => 'completed',
                'notes' => 'Bank transfer for AI service',
                'paid_at' => Carbon::now()->subDays(20),
            ],
            // Pending payments
            [
                'order_id' => $orders->skip(5)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(5)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(5)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(5)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'cash',
                'transaction_id' => null,
                'amount' => 2600.00,
                'platform_fee' => 260.00,
                'technician_amount' => 2340.00,
                'status' => 'pending',
                'notes' => 'Payment pending - service in progress',
                'paid_at' => null,
            ],
            [
                'order_id' => $orders->skip(6)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(6)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(6)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(6)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'mobile_payment',
                'transaction_id' => null,
                'amount' => 2700.00,
                'platform_fee' => 270.00,
                'technician_amount' => 2430.00,
                'status' => 'pending',
                'notes' => 'Mobile payment pending confirmation',
                'paid_at' => null,
            ],
            // Failed payments
            [
                'order_id' => $orders->skip(7)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(7)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(7)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(7)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TXN-' . str_pad(8, 8, '0', STR_PAD_LEFT),
                'amount' => 2300.00,
                'platform_fee' => 230.00,
                'technician_amount' => 2070.00,
                'status' => 'failed',
                'notes' => 'Bank transfer failed - insufficient funds',
                'paid_at' => null,
            ],
            // Refunded payments
            [
                'order_id' => $orders->skip(8)->first()->id ?? $orders->first()->id,
                'farmer_id' => $orders->skip(8)->first()->farmer_id ?? $orders->first()->farmer_id,
                'technician_id' => $orders->skip(8)->first()?->jobs?->first()?->technician_id ?? $technicians->skip(8)->first()->id ?? $technicians->first()->id,
                'payment_method' => 'mobile_payment',
                'transaction_id' => 'TXN-' . str_pad(9, 8, '0', STR_PAD_LEFT),
                'amount' => 2000.00,
                'platform_fee' => 200.00,
                'technician_amount' => 1800.00,
                'status' => 'refunded',
                'notes' => 'Payment refunded due to service cancellation',
                'paid_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }

        $this->command->info('Payment seeder completed successfully!');
    }
}