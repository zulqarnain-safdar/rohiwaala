<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\Job;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TechnicianController extends Controller
{
    public function profile(Request $request)
    {
        $technician = Technician::with('user')->where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'technician' => $technician
        ]);
    }

    public function updateProfile(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'cnic' => 'required|string|unique:technicians,cnic,' . $technician->id,
            'date_of_birth' => 'required|date',
            'home_address' => 'required|string',
            'clinic_address' => 'nullable|string',
            'province' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'tehsil_city' => 'required|string|max:255',
            'degree_year' => 'required|integer',
            'years_of_experience' => 'required|integer|min:0',
            'customers_per_month' => 'required|integer|min:0',
            'areas_of_expertise' => 'required|string',
            'mobile_1' => 'required|string',
            'mobile_2' => 'nullable|string',
            'clinic_latitude' => 'required|numeric',
            'clinic_longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $technician->update($request->all());
        $request->user()->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'technician' => $technician->fresh()
        ]);
    }

    public function jobs(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $status = $request->get('status', 'all');
        $query = Job::with(['order.farmer.user', 'order.cattleFarm', 'order.orderItems.bullSemen.breed', 'cowInseminationRecords.orderItem.bullSemen.breed'])
            ->where('technician_id', $technician->id);

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Add farm filter
        if ($request->has('farm') && $request->get('farm')) {
            $query->whereHas('order.cattleFarm', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('farm') . '%');
            });
        }
        
        // Add farmer filter
        if ($request->has('farmer') && $request->get('farmer')) {
            $query->whereHas('order.farmer.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('farmer') . '%');
            });
        }
        
        // Add job status filter
        if ($request->has('job_status') && $request->get('job_status')) {
            $query->where('status', $request->get('job_status'));
        }
        
        // Add date filter
        if ($request->has('date_filter') && $request->get('date_filter')) {
            $dateFilter = $request->get('date_filter');
            $now = now();
            
            switch ($dateFilter) {
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
                    if ($request->has('date_from') && $request->has('date_to')) {
                        $query->whereBetween('created_at', [
                            $request->get('date_from') . ' 00:00:00',
                            $request->get('date_to') . ' 23:59:59'
                        ]);
                    }
                    break;
            }
        }

        $perPage = $request->get('per_page', 10); // Default to 10, but allow custom per_page
        
        $jobs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'jobs' => $jobs
        ]);
    }

    public function showJob(Request $request, Job $job)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        $job->load(['order.farmer.user', 'order.cattleFarm', 'order.orderItems.bullSemen.breed', 'cowInseminationRecords.orderItem.bullSemen.breed']);

        return response()->json([
            'success' => true,
            'job' => $job
        ]);
    }

    public function acceptJob(Request $request, Job $job)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Job cannot be accepted in current status'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'estimated_visit_at' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $job->update([
            'status' => 'accepted',
            'progress_status' => 'accepted',
            'estimated_visit_at' => $request->estimated_visit_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job accepted successfully',
            'job' => $job->fresh()
        ]);
    }

    public function rejectJob(Request $request, Job $job)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Job cannot be rejected in current status'
            ], 400);
        }

        $job->update([
            'status' => 'rejected',
            'progress_status' => 'rejected',
            'technician_notes' => $request->get('notes', ''),
        ]);

        // Update the order status back to pending so it can be reassigned
        $job->order->update(['status' => 'pending']);

        return response()->json([
            'success' => true,
            'message' => 'Job rejected successfully',
            'job' => $job->fresh()
        ]);
    }

    public function submitETA(Request $request, Job $job)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'accepted') {
            return response()->json([
                'success' => false,
                'message' => 'ETA can only be provided for accepted jobs'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'eta' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ensure the ETA is stored in UTC but treated as Pakistan time
        $job->update([
            'estimated_visit_at' => $request->eta, // This should already be in ISO format from frontend
            'technician_notes' => $request->get('notes', ''),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ETA submitted successfully',
            'job' => $job->fresh()
        ]);
    }

    public function startJob(Request $request, Job $job)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'accepted') {
            return response()->json([
                'success' => false,
                'message' => 'Job must be accepted before starting'
            ], 400);
        }

        $job->update([
            'status' => 'in_progress',
            'progress_status' => 'in_progress',
        ]);

        // Update the order status to match the job status
        $job->order->update(['status' => 'in_progress']);

        return response()->json([
            'success' => true,
            'message' => 'Job started successfully',
            'job' => $job->fresh()
        ]);
    }

    public function completeJob(Request $request, Job $job)
    {
       
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Job must be in progress before completion'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'visit_latitude' => 'required|numeric',
            'visit_longitude' => 'required|numeric',
            'cow_records' => 'required|array',
            'cow_records.*.order_item_id' => 'required|exists:order_items,id',
            'cow_records.*.cow_in_heat' => 'required|in:yes,no',
            'cow_records.*.amount_received' => 'nullable|numeric|min:0',
            'cow_records.*.payment_method' => 'nullable|string|max:255',
            'cow_records.*.straw_barcode_code' => 'nullable|string',
            'cow_records.*.notes' => 'nullable|string',
            'cow_records.*.cow_photo' => 'nullable|file|max:10240',
            'cow_records.*.straw_photo' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update job with visit location and timestamp
        $job->update([
            'actual_visit_at' => now(),
            'visit_latitude' => $request->visit_latitude,
            'visit_longitude' => $request->visit_longitude,
        ]);

        // Process each cow record
        $totalAmountReceived = 0;
        $hasSuccessfulInsemination = false;

        \Log::info('Processing cow records for job completion', [
            'job_id' => $job->id,
            'cow_records_count' => count($request->cow_records),
            'cow_records' => $request->cow_records
        ]);

        foreach ($request->cow_records as $cowRecord) {
            $cowInHeat = $cowRecord['cow_in_heat'] === 'yes';
            $amountReceived = $cowRecord['amount_received'] ?? 0;
            $totalAmountReceived += $amountReceived;

            \Log::info('Processing cow record', [
                'order_item_id' => $cowRecord['order_item_id'],
                'cow_in_heat' => $cowRecord['cow_in_heat'],
                'cowInHeat_boolean' => $cowInHeat,
                'amount_received' => $amountReceived
            ]);

            if ($cowInHeat) {
                $hasSuccessfulInsemination = true;
            }

            // Handle image uploads for this cow
            $cowPhotoPath = null;
            $strawPhotoPath = null;

            if (isset($cowRecord['cow_photo']) && $cowRecord['cow_photo']->isValid()) {
                $cowPhotoPath = $cowRecord['cow_photo']->store('cow-photos', 'public');
            }

            if (isset($cowRecord['straw_photo']) && $cowRecord['straw_photo']->isValid()) {
                $strawPhotoPath = $cowRecord['straw_photo']->store('straw-photos', 'public');
            }

            // Create cow insemination record
            \App\Models\CowInseminationRecord::create([
                'job_id' => $job->id,
                'order_item_id' => $cowRecord['order_item_id'],
                'cow_in_heat' => $cowRecord['cow_in_heat'],
                'cow_photo' => $cowPhotoPath,
                'straw_barcode_code' => $cowRecord['straw_barcode_code'] ?? null,
                'straw_photo' => $strawPhotoPath,
                'amount_received' => $amountReceived,
                'payment_method' => $cowRecord['payment_method'] ?? null,
                'notes' => $cowRecord['notes'] ?? null,
            ]);
        }

        // Update job status based on results
        $jobStatus = $hasSuccessfulInsemination ? 'completed' : 'failed';
        $progressStatus = $hasSuccessfulInsemination ? 'administered' : 'failed_no_heat';
        $orderStatus = $hasSuccessfulInsemination ? 'completed' : 'failed';

        \Log::info('Updating job and order status', [
            'job_id' => $job->id,
            'hasSuccessfulInsemination' => $hasSuccessfulInsemination,
            'job_status' => $jobStatus,
            'progress_status' => $progressStatus,
            'order_status' => $orderStatus,
            'total_amount_received' => $totalAmountReceived
        ]);

        $job->update([
            'status' => $jobStatus,
            'progress_status' => $progressStatus,
            'amount_paid' => $totalAmountReceived,
        ]);

        // Update the order status to match the job status
        $job->order->update(['status' => $orderStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Job completed successfully',
            'job' => $job->fresh(['cowInseminationRecords.orderItem.bullSemen.breed'])
        ]);
    }

    public function markNoHeat(Request $request, Job $job)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Job must be in progress to mark as no heat'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'amount_received' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
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
            'status' => 'completed',
            'progress_status' => 'failed_no_heat',
            'actual_visit_at' => now(),
            'visit_latitude' => $request->latitude,
            'visit_longitude' => $request->longitude,
            'amount_paid' => $request->amount_received,
            'technician_notes' => $request->notes,
        ]);

        // Update the order status to completed (visit fee only)
        $job->order->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Job marked as no heat successfully',
            'job' => $job->fresh()
        ]);
    }

    public function administerSemen(Request $request, Job $job)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician || $job->technician_id !== $technician->id) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found or access denied'
            ], 404);
        }

        if ($job->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Job must be in progress to administer semen'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'straw_code' => 'nullable|string|max:255',
            'amount_received' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string|max:1000',
            'cow_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'straw_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [
            'status' => 'completed',
            'progress_status' => 'administered',
            'actual_visit_at' => now(),
            'visit_latitude' => $request->latitude,
            'visit_longitude' => $request->longitude,
            'amount_paid' => $request->amount_received,
            'payment_method' => $request->payment_method,
            'semen_straw_code' => $request->straw_code,
            'technician_notes' => $request->notes,
            'cow_status' => 'pregnant'
        ];

        // Handle image uploads
        if ($request->hasFile('cow_photo')) {
            $cowPhoto = $request->file('cow_photo');
            $cowPhotoPath = $cowPhoto->store('job-photos', 'public');
            $updateData['cow_photo'] = $cowPhotoPath;
        }

        if ($request->hasFile('straw_photo')) {
            $strawPhoto = $request->file('straw_photo');
            $strawPhotoPath = $strawPhoto->store('job-photos', 'public');
            $updateData['semen_straw_image'] = $strawPhotoPath;
        }

        $job->update($updateData);

        // Update the order status to completed
        $job->order->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Semen administered successfully',
            'job' => $job->fresh()
        ]);
    }

    public function updateAvailability(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'availability' => 'required|in:online,offline,on_leave',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i',
            'available_24_7' => 'nullable|boolean',
            'service_areas' => 'nullable|array',
            'service_areas.*.name' => 'required_with:service_areas|string|max:255',
            'service_areas.*.radius' => 'required_with:service_areas|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = ['availability' => $request->availability];
        
        if ($request->has('working_hours_start')) {
            $updateData['working_hours_start'] = $request->working_hours_start;
        }
        
        if ($request->has('working_hours_end')) {
            $updateData['working_hours_end'] = $request->working_hours_end;
        }
        
        if ($request->has('available_24_7')) {
            $updateData['available_24_7'] = $request->available_24_7;
        }
        
        if ($request->has('service_areas')) {
            $updateData['service_areas'] = $request->service_areas;
        }

        $technician->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Availability settings updated successfully',
            'technician' => $technician->fresh()
        ]);
    }

    public function earnings(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $period = $request->get('period', 'month'); // day, week, month, year
        $query = Job::where('technician_id', $technician->id)
            ->where('status', 'completed');

        switch ($period) {
            case 'day':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $totalEarnings = $query->sum('amount_paid');
        $jobCount = $query->count();
        $jobs = $query->with(['order.farmer.user'])->get();

        return response()->json([
            'success' => true,
            'earnings' => [
                'total' => $totalEarnings,
                'job_count' => $jobCount,
                'period' => $period,
                'jobs' => $jobs
            ]
        ]);
    }

    public function inventory(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        // Get assigned stock items with quantities
        $assignedItems = $technician->semenProducts()->withPivot('quantity')->with('breed')->get();
        
        // Calculate statistics
        $totalItems = $assignedItems->count();
        $availableItems = $assignedItems->where('pivot.quantity', '>', 0)->count();
        $lowStockItems = $assignedItems->where('pivot.quantity', '<=', 10)->where('pivot.quantity', '>', 0)->count();
        $outOfStockItems = $assignedItems->where('pivot.quantity', '=', 0)->count();

        // Format items for display
        $items = $assignedItems->map(function ($item) {
            return [
                'id' => $item->id,
                'bull_name' => $item->name,
                'breed' => $item->breed,
                'price' => $item->price,
                'stock_quantity' => $item->pivot->quantity,
                'main_stock' => $item->stock_quantity,
                'description' => $item->description,
                'is_active' => $item->is_active
            ];
        });

        return response()->json([
            'success' => true,
            'total_items' => $totalItems,
            'available_items' => $availableItems,
            'low_stock_items' => $lowStockItems,
            'out_of_stock' => $outOfStockItems,
            'items' => $items
        ]);
    }

    public function uploadDocument(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:cnic_front,cnic_back,degree_front,degree_back',
            'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('document');
            $documentType = $request->document_type;
            
            // Generate unique filename
            $filename = $technician->id . '_' . $documentType . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store file in public/technician-documents directory
            $path = $file->storeAs('technician-documents', $filename, 'public');
            
            // Update the appropriate field in the database
            $fieldName = $documentType . '_image';
            $technician->update([$fieldName => $path]);
            
            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'file_path' => $path,
                'file_url' => Storage::url($path)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:cnic_front,cnic_back,degree_front,degree_back',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $documentType = $request->document_type;
            $fieldName = $documentType . '_image';
            
            // Get current file path
            $currentPath = $technician->$fieldName;
            
            if ($currentPath && Storage::disk('public')->exists($currentPath)) {
                // Delete the file
                Storage::disk('public')->delete($currentPath);
            }
            
            // Clear the field in database
            $technician->update([$fieldName => null]);
            
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get technicians within a specific radius of given coordinates
     */
    public function getNearbyTechnicians(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1|max:100', // radius in kilometers
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius;

        // Get all active technicians (excluding those on leave)
        $technicians = Technician::with('user')
            ->where('status', 'active')
            ->where('availability', '!=', 'on_leave')
            ->whereNotNull('clinic_latitude')
            ->whereNotNull('clinic_longitude')
            ->get();

        // Calculate distance and filter by radius
        $nearbyTechnicians = $technicians->filter(function ($technician) use ($latitude, $longitude, $radius) {
            $distance = $this->calculateDistance(
                $latitude, 
                $longitude, 
                (float)$technician->clinic_latitude, 
                (float)$technician->clinic_longitude
            );
            
            if ($distance <= $radius) {
                $technician->distance = round($distance, 1);
                return true;
            }
            return false;
        })->sortBy('distance')->values();

        return response()->json([
            'success' => true,
            'technicians' => $nearbyTechnicians
        ]);
    }

    /**
     * Search technicians by location filters
     */
    public function searchTechnicians(Request $request)
    {
        $query = Technician::with('user')
            ->where('status', 'active')
            ->where('availability', '!=', 'on_leave');

        // Location filters
        if ($request->has('province') && $request->province) {
            $query->where('province', $request->province);
        }

        if ($request->has('division') && $request->division) {
            $query->where('division', $request->division);
        }

        if ($request->has('district') && $request->district) {
            $query->where('district', $request->district);
        }

        if ($request->has('tehsil_city') && $request->tehsil_city) {
            $query->where('tehsil_city', $request->tehsil_city);
        }

        // Legacy support for old field names
        if ($request->has('tehsil') && $request->tehsil) {
            $query->where(function($q) use ($request) {
                $q->where('tehsil_city', $request->tehsil)
                  ->orWhere('tehsil', $request->tehsil);
            });
        }

        if ($request->has('city') && $request->city) {
            $query->where(function($q) use ($request) {
                $q->where('tehsil_city', $request->city)
                  ->orWhere('city', $request->city);
            });
        }

        // Availability filter
        if ($request->has('availability') && $request->availability) {
            $query->where('availability', $request->availability);
        }

        $technicians = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'technicians' => $technicians
        ]);
    }

    /**
     * Get location options for filters (provinces, districts, tehsils, cities)
     */
    public function getLocationOptions()
    {
        $provinces = Technician::whereNotNull('province')
            ->distinct()
            ->pluck('province')
            ->filter()
            ->sort()
            ->values();

        $districts = Technician::whereNotNull('district')
            ->distinct()
            ->pluck('district')
            ->filter()
            ->sort()
            ->values();

        $tehsils = Technician::whereNotNull('tehsil')
            ->distinct()
            ->pluck('tehsil')
            ->filter()
            ->sort()
            ->values();

        $cities = Technician::whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->filter()
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'provinces' => $provinces,
            'districts' => $districts,
            'tehsils' => $tehsils,
            'cities' => $cities
        ]);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    public function reassignJob(Request $request, $jobId)
    {
        $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'reason' => 'required|string|max:1000'
        ]);

        $job = Job::findOrFail($jobId);
        $currentTechnician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$currentTechnician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        // Check if the current technician owns this job
        if ($job->technician_id !== $currentTechnician->id) {
            return response()->json([
                'success' => false,
                'message' => 'This job is no longer assigned to you. Please refresh the page to see the latest job assignments.'
            ], 403);
        }

        // Check if the job is in a reassignable state
        if (!in_array($job->status, ['pending', 'accepted'])) {
            return response()->json([
                'success' => false,
                'message' => 'This job cannot be reassigned'
            ], 400);
        }

        $newTechnician = Technician::findOrFail($request->technician_id);

        // Store the original technician ID for logging
        $originalTechnicianId = $job->technician_id;

        // Update the job
        $job->update([
            'technician_id' => $newTechnician->id,
            'status' => 'pending', // Reset to pending for the new technician
            'progress_status' => 'pending',
            'estimated_visit_at' => null, // Clear any existing ETA
        ]);

        // Log the reassignment
        \App\Models\OrderReassignmentLog::create([
            'order_id' => $job->order_id,
            'job_id' => $job->id,
            'from_technician_id' => $originalTechnicianId,
            'to_technician_id' => $newTechnician->id,
            'reassigned_by_technician_id' => $currentTechnician->id,
            'reason' => $request->get('reason'),
            'metadata' => [
                'reassigned_at' => now()->toISOString(),
                'original_status' => $job->getOriginal('status'),
                'new_status' => 'pending'
            ]
        ]);

        // Create a notification for the new technician
        $newTechnician->user->notifications()->create([
            'type' => 'job_assigned',
            'title' => 'New Job Assigned',
            'message' => "A job has been reassigned to you for {$job->order->farmer->user->name} at {$job->order->cattleFarm->name}",
            'data' => [
                'job_id' => $job->id,
                'order_id' => $job->order_id,
                'farmer_name' => $job->order->farmer->user->name,
                'farm_name' => $job->order->cattleFarm->name,
                'amount' => $job->amount_paid
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job reassigned successfully',
            'job' => $job->fresh(['order.farmer.user', 'order.cattleFarm', 'order.orderItems.bullSemen.breed'])
        ]);
    }

    public function getReassignmentLogs(Request $request)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $logs = \App\Models\OrderReassignmentLog::with([
            'order.cattleFarm',
            'order.farmer.user',
            'fromTechnician.user',
            'toTechnician.user',
            'reassignedByTechnician.user'
        ])
        ->where(function($query) use ($technician) {
            $query->where('from_technician_id', $technician->id)
                  ->orWhere('to_technician_id', $technician->id)
                  ->orWhere('reassigned_by_technician_id', $technician->id);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    public function getAvailableTechnicians(Request $request)
    {
        $search = $request->get('search');
        $radius = $request->get('radius'); // Don't set default to 30
        $farmLat = $request->get('farm_lat');
        $farmLng = $request->get('farm_lng');
        
        // Debug: Log what we received
        \Log::info('getAvailableTechnicians called:', [
            'search' => $search,
            'radius' => $radius,
            'farm_lat' => $farmLat,
            'farm_lng' => $farmLng,
            'radius_is_all' => $radius === 'all'
        ]);
        

        $currentTechnician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$currentTechnician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $query = Technician::with(['user'])
            ->where('id', '!=', $currentTechnician->id) // Exclude current technician
            ->where('status', 'active') // Only active technicians
            ->where('availability', '!=', 'on_leave'); // Exclude technicians on leave

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('mobile', 'like', "%{$search}%");
                })
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('mobile_1', 'like', "%{$search}%")
                ->orWhere('mobile_2', 'like', "%{$search}%")
                ->orWhere('clinic_address', 'like', "%{$search}%")
                ->orWhere('home_address', 'like', "%{$search}%");
            });
        }

        $technicians = $query->get();
        
        // Debug: Log initial results
        \Log::info('Initial technicians found:', [
            'count' => $technicians->count(),
            'technician_ids' => $technicians->pluck('id')->toArray(),
            'technician_names' => $technicians->pluck('name')->toArray()
        ]);

        // Filter by distance if farm coordinates are provided and radius is specified
        if ($farmLat && $farmLng && $radius !== null && $radius !== 'all') {
            $technicians = $technicians->map(function ($technician) use ($farmLat, $farmLng) {
                if ($technician->clinic_latitude && $technician->clinic_longitude) {
                    $technician->distance = $this->calculateDistance(
                        $farmLat, $farmLng,
                        $technician->clinic_latitude, $technician->clinic_longitude
                    );
                } else {
                    $technician->distance = null;
                }
                return $technician;
            });

            // Filter by radius
            $technicians = $technicians->filter(function ($technician) use ($radius) {
                return $technician->distance === null || $technician->distance <= $radius;
            });
        } else {
            // If no distance filter, still calculate distances for display
            if ($farmLat && $farmLng) {
                $technicians = $technicians->map(function ($technician) use ($farmLat, $farmLng) {
                    if ($technician->clinic_latitude && $technician->clinic_longitude) {
                        $technician->distance = $this->calculateDistance(
                            $farmLat, $farmLng,
                            $technician->clinic_latitude, $technician->clinic_longitude
                        );
                    } else {
                        $technician->distance = null;
                    }
                    return $technician;
                });
            }
        }

        // Add availability status (you can customize this based on your business logic)
        $technicians = $technicians->map(function ($technician) {
            $technician->availability = $this->getTechnicianAvailability($technician);
            return $technician;
        });

        // Debug: Log final results
        \Log::info('Final technicians returned:', [
            'count' => $technicians->count(),
            'technician_ids' => $technicians->pluck('id')->toArray(),
            'technician_names' => $technicians->pluck('name')->toArray()
        ]);

        return response()->json([
            'technicians' => $technicians->values()
        ]);
    }

    private function getTechnicianAvailability($technician)
    {
        // Simple availability logic - you can customize this
        $pendingJobs = Job::where('technician_id', $technician->id)
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->count();

        if ($pendingJobs >= 5) {
            return 'busy';
        } elseif ($pendingJobs >= 3) {
            return 'available';
        } else {
            return 'available';
        }
    }

    public function markConception(Request $request, $jobId)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->where('technician_id', $technician->id)
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
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->where('technician_id', $technician->id)
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

    public function markMiscarriage(Request $request, $jobId)
    {
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->where('technician_id', $technician->id)
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
        $technician = Technician::where('user_id', $request->user()->id)->first();
        
        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found'
            ], 404);
        }

        $job = Job::with(['order', 'technician.user'])
            ->where('id', $jobId)
            ->where('technician_id', $technician->id)
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
}