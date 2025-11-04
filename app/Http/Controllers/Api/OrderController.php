<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Farmer;
use App\Models\CattleFarm;
use App\Models\BullSemen;
use App\Models\Job;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['farmer.user', 'cattleFarm', 'orderItems.bullSemen.breed', 'jobs.technician.user']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('farmer_id')) {
            $query->where('farmer_id', $request->farmer_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cattle_farm_id' => 'required|exists:cattle_farms,id',
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

        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $cattleFarm = CattleFarm::where('id', $request->cattle_farm_id)
            ->where('farmer_id', $farmer->id)
            ->first();

        if (!$cattleFarm) {
            return response()->json([
                'success' => false,
                'message' => 'Cattle farm not found or access denied'
            ], 404);
        }

        DB::beginTransaction();
        try {
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
                'cattle_farm_id' => $request->cattle_farm_id,
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
                $nearestTechnician = $this->findNearestTechnician($cattleFarm->latitude, $cattleFarm->longitude);
                $technicianId = $nearestTechnician ? $nearestTechnician->id : null;
            }

            if ($technicianId) {
                // Create job assignment
                Job::create([
                    'order_id' => $order->id,
                    'technician_id' => $technicianId,
                    'status' => 'pending',
                    'amount_paid' => $order->total_amount,
                    'progress_status' => 'pending',
                ]);

                $order->update(['status' => 'assigned']);
            }

            DB::commit();

            $order->load(['cattleFarm', 'orderItems.bullSemen.breed', 'jobs.technician.user']);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        $order->load(['farmer.user', 'cattleFarm', 'orderItems.bullSemen.breed', 'jobs.technician.user']);

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->only(['status', 'notes']));

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully',
            'order' => $order->fresh()
        ]);
    }

    public function destroy(Order $order)
    {
        if ($order->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete completed order'
            ], 400);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully'
        ]);
    }

    private function findNearestTechnician($latitude, $longitude)
    {
        // Simple distance calculation (in a real app, you'd use a proper geospatial query)
        $technicians = Technician::where('status', 'active')
            ->where('availability', 'online')
            ->get();

        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($technicians as $technician) {
            $distance = $this->calculateDistance(
                $latitude, $longitude,
                $technician->clinic_latitude, $technician->clinic_longitude
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $technician;
            }
        }

        return $nearest;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}