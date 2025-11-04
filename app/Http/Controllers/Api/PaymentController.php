<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Farmer;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Payment::with(['order', 'farmer', 'technician']);

        if ($user->role === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            if ($farmer) {
                $query->where('farmer_id', $farmer->id);
            }
        } elseif ($user->role === 'technician') {
            $technician = Technician::where('user_id', $user->id)->first();
            if ($technician) {
                $query->where('technician_id', $technician->id);
            }
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'payments' => $payments
        ]);
    }

    public function show(Request $request, Payment $payment)
    {
        $user = $request->user();
        
        // Check authorization
        if ($user->role === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            if (!$farmer || $payment->farmer_id !== $farmer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        } elseif ($user->role === 'technician') {
            $technician = Technician::where('user_id', $user->id)->first();
            if (!$technician || $payment->technician_id !== $technician->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }

        $payment->load(['order', 'farmer', 'technician']);

        return response()->json([
            'success' => true,
            'payment' => $payment
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_payment',
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if ($user->role !== 'farmer') {
            return response()->json([
                'success' => false,
                'message' => 'Only farmers can create payments'
            ], 403);
        }

        $farmer = Farmer::where('user_id', $user->id)->first();
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $order = Order::with('jobs.technician')->find($request->order_id);
        if (!$order || $order->farmer_id !== $farmer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or access denied'
            ], 404);
        }

        $technician = $order->jobs->first()?->technician;
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'No technician assigned to this order'
            ], 400);
        }

        // Calculate platform fee (10% of amount)
        $platformFee = $request->amount * 0.10;
        $technicianAmount = $request->amount - $platformFee;

        $payment = Payment::create([
            'order_id' => $request->order_id,
            'farmer_id' => $farmer->id,
            'technician_id' => $technician->id,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'platform_fee' => $platformFee,
            'technician_amount' => $technicianAmount,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'payment' => $payment->load(['order', 'farmer', 'technician'])
        ], 201);
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only admins can update payment status'
            ], 403);
        }

        $updateData = [
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        if ($request->status === 'completed') {
            $updateData['paid_at'] = now();
        }

        $payment->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully',
            'payment' => $payment->fresh()
        ]);
    }

    public function analytics(Request $request)
    {
        $user = $request->user();
        $query = Payment::query();

        if ($user->role === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            if ($farmer) {
                $query->where('farmer_id', $farmer->id);
            }
        } elseif ($user->role === 'technician') {
            $technician = Technician::where('user_id', $user->id)->first();
            if ($technician) {
                $query->where('technician_id', $technician->id);
            }
        }

        $totalPayments = $query->count();
        $totalAmount = $query->sum('amount');
        $completedPayments = $query->where('status', 'completed')->count();
        $pendingPayments = $query->where('status', 'pending')->count();
        $failedPayments = $query->where('status', 'failed')->count();

        return response()->json([
            'success' => true,
            'analytics' => [
                'total_payments' => $totalPayments,
                'total_amount' => $totalAmount,
                'completed_payments' => $completedPayments,
                'pending_payments' => $pendingPayments,
                'failed_payments' => $failedPayments,
            ]
        ]);
    }
}