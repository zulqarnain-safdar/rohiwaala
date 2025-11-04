<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Farmer;
use App\Models\CattleFarm;
use App\Models\BullSemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GuestOrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_info.name' => 'required|string|max:255',
            'customer_info.mobile' => 'required|string|max:20',
            'customer_info.email' => 'nullable|email|max:255',
            'customer_info.date_of_birth' => 'nullable|date',
            'farm_info.name' => 'required|string|max:255',
            'farm_info.father_name' => 'nullable|string|max:255',
            'farm_info.latitude' => 'required|numeric',
            'farm_info.longitude' => 'required|numeric',
            'farm_info.number_of_cows' => 'required|integer|min:0',
            'farm_info.number_of_calves' => 'required|integer|min:0',
            'farm_info.number_of_bulls' => 'required|integer|min:0',
            'farm_info.number_of_goats' => 'required|integer|min:0',
            'technician_id' => 'nullable|exists:technicians,id',
            'order_items' => 'required|array|min:1',
            'order_items.*.bull_semen_id' => 'required|exists:bull_semen,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'order_items.*.cow_type' => 'nullable|string',
            'order_items.*.cow_age' => 'nullable|integer|min:1',
            'order_items.*.heat_started_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create or find user
            $user = User::where('mobile', $request->customer_info['mobile'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $request->customer_info['name'],
                    'mobile' => $request->customer_info['mobile'],
                    'email' => $request->customer_info['email'],
                    'date_of_birth' => $request->customer_info['date_of_birth'],
                    'password' => bcrypt('guest_password_' . time()), // Temporary password
                    'role' => 'farmer'
                ]);
            }

            // Create or find farmer
            $farmer = Farmer::where('user_id', $user->id)->first();
            
            if (!$farmer) {
                $farmer = Farmer::create([
                    'user_id' => $user->id,
                    'name' => $request->customer_info['name'],
                    'mobile' => $request->customer_info['mobile'],
                    'email' => $request->customer_info['email'],
                    'date_of_birth' => $request->customer_info['date_of_birth']
                ]);
            }

            // Create cattle farm
            $cattleFarm = CattleFarm::create([
                'farmer_id' => $farmer->id,
                'name' => $request->farm_info['name'],
                'father_name' => $request->farm_info['father_name'],
                'latitude' => $request->farm_info['latitude'],
                'longitude' => $request->farm_info['longitude'],
                'number_of_cows' => $request->farm_info['number_of_cows'],
                'number_of_calves' => $request->farm_info['number_of_calves'],
                'number_of_bulls' => $request->farm_info['number_of_bulls'],
                'number_of_goats' => $request->farm_info['number_of_goats']
            ]);

            // Calculate total amount
            $totalAmount = 0;
            $orderItems = [];

            foreach ($request->order_items as $item) {
                $semen = BullSemen::find($item['bull_semen_id']);
                $itemTotal = $semen->price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'bull_semen_id' => $item['bull_semen_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $semen->price,
                    'total_price' => $itemTotal,
                    'cow_type' => !empty($item['cow_type']) ? $item['cow_type'] : null,
                    'cow_age' => !empty($item['cow_age']) ? $item['cow_age'] : null,
                    'heat_started_at' => $item['heat_started_at'],
                ];
            }

            // Create order
            $order = Order::create([
                'farmer_id' => $farmer->id,
                'cattle_farm_id' => $cattleFarm->id,
                'order_number' => 'ORD-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'visit_fee' => 50.00, // Default visit fee
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            // Use provided technician or find nearest available technician
            $technicianId = $request->technician_id;
            
            if (!$technicianId) {
                // Find nearest technician logic (simplified)
                $technicianId = $this->findNearestTechnician($cattleFarm->latitude, $cattleFarm->longitude);
            }

            if ($technicianId) {
                $order->update(['technician_id' => $technicianId]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order->load(['orderItems.bullSemen', 'cattleFarm', 'technician'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function findNearestTechnician($latitude, $longitude)
    {
        // Simplified nearest technician logic
        // In a real implementation, you would use spatial queries
        $technicians = \App\Models\Technician::where('is_active', true)->get();
        
        if ($technicians->isEmpty()) {
            return null;
        }

        // For now, return the first available technician
        return $technicians->first()->id;
    }
}
