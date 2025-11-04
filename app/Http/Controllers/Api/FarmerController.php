<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\CattleFarm;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BullSemen;
use App\Models\Breed;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FarmerController extends Controller
{
    public function profile(Request $request)
    {
        $farmer = Farmer::with('user', 'cattleFarms')->where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'farmer' => $farmer
        ]);
    }

    public function updateProfile(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'profile_picture' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $farmer->update($request->only(['name', 'date_of_birth', 'profile_picture']));
        $request->user()->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'farmer' => $farmer->fresh()
        ]);
    }

    public function farms(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $farms = $farmer->cattleFarms;

        return response()->json([
            'success' => true,
            'farms' => $farms
        ]);
    }

    public function createFarm(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'picture' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'number_of_cows' => 'required|integer|min:0',
            'number_of_calves' => 'required|integer|min:0',
            'number_of_bulls' => 'required|integer|min:0',
            'number_of_goats' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $farm = $farmer->cattleFarms()->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Farm created successfully',
            'farm' => $farm
        ], 201);
    }

    public function updateFarm(Request $request, CattleFarm $farm)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer || $farm->farmer_id !== $farmer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Farm not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'picture' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'number_of_cows' => 'required|integer|min:0',
            'number_of_calves' => 'required|integer|min:0',
            'number_of_bulls' => 'required|integer|min:0',
            'number_of_goats' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $farm->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Farm updated successfully',
            'farm' => $farm->fresh()
        ]);
    }

    public function deleteFarm(Request $request, CattleFarm $farm)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer || $farm->farmer_id !== $farmer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Farm not found or access denied'
            ], 404);
        }

        $farm->delete();

        return response()->json([
            'success' => true,
            'message' => 'Farm deleted successfully'
        ]);
    }

    public function orders(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $query = Order::with(['cattleFarm', 'orderItems.bullSemen.breed', 'jobs.technician.user', 'jobs.cowInseminationRecords.orderItem.bullSemen.breed'])
            ->where('farmer_id', $farmer->id);
        
        // Add farm filter
        if ($request->has('farm') && $request->get('farm')) {
            $query->where('cattle_farm_id', $request->get('farm'));
        }
        
        // Add technician filter
        if ($request->has('technician') && $request->get('technician')) {
            $query->whereHas('jobs', function($q) use ($request) {
                $q->where('technician_id', $request->get('technician'));
            });
        }
        
        // Add status filter
        if ($request->has('status') && $request->get('status')) {
            $query->where('status', $request->get('status'));
        }
        
        // Add date range filter
        if ($request->has('date_range') && $request->get('date_range')) {
            $dateRange = $request->get('date_range');
            $now = now();
            
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->where('created_at', '>=', $now->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
                case 'custom':
                    if ($request->has('from_date') && $request->has('to_date')) {
                        $query->whereBetween('created_at', [
                            $request->get('from_date') . ' 00:00:00',
                            $request->get('to_date') . ' 23:59:59'
                        ]);
                    }
                    break;
            }
        }
        
        $perPage = $request->get('per_page', 10); // Default to 10, but allow custom per_page
        
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function showOrder(Request $request, Order $order)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer || $order->farmer_id !== $farmer->id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or access denied'
            ], 404);
        }

        $order->load(['cattleFarm', 'orderItems.bullSemen.breed', 'jobs.technician.user', 'jobs.cowInseminationRecords.orderItem.bullSemen.breed']);

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    public function showJob(Request $request, $jobId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $job = Job::with(['order.cattleFarm', 'order.orderItems.bullSemen.breed', 'technician.user'])
            ->where('id', $jobId)
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'job' => $job
        ]);
    }

    public function confirmService(Request $request, $jobId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'service_completed' => 'required|in:true,false,1,0,"true","false"',
            'cow_in_heat' => 'nullable',
            'amount_paid' => 'nullable|numeric|min:0',
            'semen_straw_code' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'farmer_notes' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('service-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        // Convert string boolean values to actual booleans
        $serviceCompleted = filter_var($request->service_completed, FILTER_VALIDATE_BOOLEAN);
        
        // Handle cow_in_heat conversion with more flexibility
        $cowInHeat = null;
        if ($request->cow_in_heat !== null && $request->cow_in_heat !== '' && $request->cow_in_heat !== 'null') {
            $cowInHeatValue = strtolower(trim($request->cow_in_heat));
            if (in_array($cowInHeatValue, ['true', '1', 'yes'])) {
                $cowInHeat = true;
            } elseif (in_array($cowInHeatValue, ['false', '0', 'no'])) {
                $cowInHeat = false;
            } else {
                $cowInHeat = filter_var($request->cow_in_heat, FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Update job with farmer confirmation
        $job->update([
            'farmer_notes' => $request->farmer_notes,
            'rating' => $request->rating,
            'amount_paid' => $request->amount_paid,
            'semen_straw_code' => $request->semen_straw_code,
            'semen_straw_image' => !empty($photoPaths) ? implode(',', $photoPaths) : null,
        ]);

        // Update job status based on service completion
        if ($serviceCompleted) {
            if ($cowInHeat) {
                $job->update([
                    'status' => 'completed',
                    'progress_status' => 'administered',
                    'cow_status' => 'pregnant'
                ]);
                // Update order status to completed
                $job->order->update(['status' => 'completed']);
            } else {
                $job->update([
                    'status' => 'completed',
                    'progress_status' => 'failed_no_heat',
                    'cow_status' => null
                ]);
                // Update order status to completed (even though no heat, service was completed)
                $job->order->update(['status' => 'completed']);
            }
        } else {
            $job->update([
                'status' => 'failed',
                'progress_status' => 'rejected'
            ]);
            // Update order status to cancelled when service is not completed
            $job->order->update(['status' => 'cancelled']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service confirmation submitted successfully',
            'job' => $job->fresh()
        ]);
    }

    public function getJobs(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $jobs = Job::with(['order.cattleFarm', 'order.orderItems.bullSemen.breed', 'technician.user'])
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->whereIn('cow_status', ['pregnant', 'miscarried', 'delivered'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'jobs' => $jobs
        ]);
    }

    public function markMiscarriage(Request $request, $jobId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $job->update([
            'cow_status' => 'miscarried',
            'delivery_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as miscarried successfully',
            'job' => $job->fresh()
        ]);
    }

    public function markDelivery(Request $request, $jobId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle photo uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('calf-photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $job->update([
            'cow_status' => 'delivered',
            'delivery_notes' => $request->notes,
            'calf_image' => !empty($photoPaths) ? implode(',', $photoPaths) : $job->calf_image,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as delivered successfully',
            'job' => $job->fresh()
        ]);
    }

    public function markConception(Request $request, $jobId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $job->update([
            'cow_status' => 'conceived',
            'conception_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as conceived successfully',
            'job' => $job->fresh()
        ]);
    }

    public function markPregnancy(Request $request, $jobId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $job->update([
            'cow_status' => 'pregnant',
            'pregnancy_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as pregnant successfully',
            'job' => $job->fresh()
        ]);
    }

    public function getCowInseminationRecords(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $query = \App\Models\CowInseminationRecord::with([
            'job.order.cattleFarm',
            'job.order.orderItems.bullSemen.breed',
            'job.technician.user',
            'orderItem.bullSemen.breed'
        ])
        ->whereHas('job.order', function($query) use ($farmer) {
            $query->where('farmer_id', $farmer->id);
        });

        // Filter by specific job if provided
        if ($request->has('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }

    public function markCowConception(Request $request, $recordId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $record = \App\Models\CowInseminationRecord::with(['job.order'])
            ->where('id', $recordId)
            ->whereHas('job.order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Cow record not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $record->update([
            'pregnancy_status' => 'conceived',
            'conception_notes' => $request->notes,
            'conception_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as conceived successfully',
            'record' => $record->fresh()
        ]);
    }

    public function markCowPregnancy(Request $request, $recordId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $record = \App\Models\CowInseminationRecord::with(['job.order'])
            ->where('id', $recordId)
            ->whereHas('job.order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Cow record not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $record->update([
            'pregnancy_status' => 'pregnant',
            'pregnancy_notes' => $request->notes,
            'pregnancy_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as pregnant successfully',
            'record' => $record->fresh()
        ]);
    }

    public function markCowMiscarriage(Request $request, $recordId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $record = \App\Models\CowInseminationRecord::with(['job.order'])
            ->where('id', $recordId)
            ->whereHas('job.order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Cow record not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $record->update([
            'pregnancy_status' => 'miscarried',
            'delivery_notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as miscarried successfully',
            'record' => $record->fresh()
        ]);
    }

    public function markCowDelivery(Request $request, $recordId)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $record = \App\Models\CowInseminationRecord::with(['job.order'])
            ->where('id', $recordId)
            ->whereHas('job.order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Cow record not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle photo uploads
        $photoPaths = [];
        \Log::info('Request data:', [
            'all_data' => $request->all(),
            'has_files' => $request->hasFile('photos'),
            'files' => $request->file('photos'),
            'record_id' => $recordId,
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'request_headers' => $request->headers->all(),
            'input_data' => $request->input(),
            'file_data' => $request->file()
        ]);
        
        if ($request->hasFile('photos')) {
            \Log::info('Processing photo uploads:', [
                'photos_count' => count($request->file('photos')),
                'record_id' => $recordId
            ]);
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('calf-photos', 'public');
                $photoPaths[] = $path;
                \Log::info("Photo {$index} stored at: {$path}");
            }
        } else {
            \Log::info('No photos found in request');
        }

        $updateData = [
            'pregnancy_status' => 'delivered',
            'delivery_notes' => $request->notes,
            'calf_image' => !empty($photoPaths) ? implode(',', $photoPaths) : $record->calf_image,
            'delivery_date' => now(),
        ];
        
        \Log::info('Updating record with data:', $updateData);
        
        $record->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Marked as delivered successfully',
            'record' => $record->fresh()
        ]);
    }

    public function getPregnancyStats(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $stats = \App\Models\CowInseminationRecord::whereHas('job.order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->whereIn('pregnancy_status', ['pending', 'conceived', 'pregnant', 'miscarried', 'delivered'])
            ->selectRaw('
                pregnancy_status,
                COUNT(*) as count,
                AVG(CASE WHEN pregnancy_status = "pregnant" THEN DATEDIFF(NOW(), pregnancy_date) ELSE NULL END) as avg_days_pregnant
            ')
            ->groupBy('pregnancy_status')
            ->get()
            ->keyBy('pregnancy_status');

        // Calculate additional statistics
        $totalPregnancies = $stats->sum('count');
        $successRate = $totalPregnancies > 0 ? round(($stats->get('delivered', (object)['count' => 0])->count / $totalPregnancies) * 100, 1) : 0;
        
        // Get upcoming deliveries (next 30 days)
        $upcomingDeliveries = \App\Models\CowInseminationRecord::with(['job.order.cattleFarm', 'job.technician.user'])
            ->whereHas('job.order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->where('pregnancy_status', 'pregnant')
            ->whereRaw('DATEDIFF(NOW(), pregnancy_date) >= 250') // 250+ days pregnant
            ->whereRaw('DATEDIFF(NOW(), pregnancy_date) <= 300') // within 30 days of expected delivery
            ->orderBy('pregnancy_date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_pregnancies' => $totalPregnancies,
                'pending_count' => $stats->get('pending', (object)['count' => 0])->count,
                'conceived_count' => $stats->get('conceived', (object)['count' => 0])->count,
                'pregnant_count' => $stats->get('pregnant', (object)['count' => 0])->count,
                'delivered_count' => $stats->get('delivered', (object)['count' => 0])->count,
                'miscarried_count' => $stats->get('miscarried', (object)['count' => 0])->count,
                'success_rate' => $successRate,
                'avg_days_pregnant' => round($stats->get('pregnant', (object)['avg_days_pregnant' => 0])->avg_days_pregnant ?? 0, 1),
                'upcoming_deliveries' => $upcomingDeliveries
            ]
        ]);
    }

    public function checkPregnancyNotifications(Request $request)
    {
        $farmer = Farmer::where('user_id', $request->user()->id)->first();
        
        if (!$farmer) {
            return response()->json([
                'success' => false,
                'message' => 'Farmer profile not found'
            ], 404);
        }

        $pregnantJobs = Job::with(['order.cattleFarm', 'technician.user'])
            ->whereHas('order', function($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->where('cow_status', 'pregnant')
            ->get();

        $notifications = [];

        foreach ($pregnantJobs as $job) {
            $daysPregnant = $job->actual_visit_at ? 
                \Carbon\Carbon::now()->diffInDays($job->actual_visit_at) : 0;

            // Check for milestone notifications
            if ($daysPregnant >= 100 && !$job->notified_100_days) {
                $notifications[] = [
                    'type' => 'milestone',
                    'title' => '100 Days Pregnant!',
                    'message' => "Your cow from order {$job->order->order_number} has reached 100 days of pregnancy.",
                    'job_id' => $job->id,
                    'days' => $daysPregnant
                ];
                $job->update(['notified_100_days' => true, 'last_notification_sent' => now()]);
            }

            if ($daysPregnant >= 200 && !$job->notified_200_days) {
                $notifications[] = [
                    'type' => 'milestone',
                    'title' => '200 Days Pregnant!',
                    'message' => "Your cow from order {$job->order->order_number} has reached 200 days of pregnancy.",
                    'job_id' => $job->id,
                    'days' => $daysPregnant
                ];
                $job->update(['notified_200_days' => true, 'last_notification_sent' => now()]);
            }

            if ($daysPregnant >= 250 && !$job->notified_250_days) {
                $notifications[] = [
                    'type' => 'milestone',
                    'title' => '250 Days Pregnant!',
                    'message' => "Your cow from order {$job->order->order_number} has reached 250 days of pregnancy. Delivery is approaching!",
                    'job_id' => $job->id,
                    'days' => $daysPregnant
                ];
                $job->update(['notified_250_days' => true, 'last_notification_sent' => now()]);
            }

            if ($daysPregnant >= 270 && !$job->notified_270_days) {
                $notifications[] = [
                    'type' => 'milestone',
                    'title' => '270 Days Pregnant!',
                    'message' => "Your cow from order {$job->order->order_number} has reached 270 days of pregnancy. Delivery is very close!",
                    'job_id' => $job->id,
                    'days' => $daysPregnant
                ];
                $job->update(['notified_270_days' => true, 'last_notification_sent' => now()]);
            }

            if ($daysPregnant >= 280 && !$job->notified_delivery_due) {
                $notifications[] = [
                    'type' => 'urgent',
                    'title' => 'Delivery Due!',
                    'message' => "Your cow from order {$job->order->order_number} has reached full term (280+ days). Please monitor closely for delivery signs.",
                    'job_id' => $job->id,
                    'days' => $daysPregnant
                ];
                $job->update(['notified_delivery_due' => true, 'last_notification_sent' => now()]);
            }
        }

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
    }
}