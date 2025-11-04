<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Technician;
use App\Models\Order;
use App\Models\Job;
use App\Models\BullSemen;
use App\Models\Breed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function farmers(Request $request)
    {
        $query = Farmer::with('user', 'cattleFarms', 'orders');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $farmers = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'farmers' => $farmers
        ]);
    }

    public function updateFarmerStatus(Request $request, Farmer $farmer)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $farmer->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Farmer status updated successfully',
            'farmer' => $farmer->fresh()
        ]);
    }

    public function technicians(Request $request)
    {
        $query = Technician::with('user', 'jobs.order.farmer.user');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('availability')) {
            $query->where('availability', $request->availability);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile_1', 'like', "%{$search}%")
                  ->orWhere('unique_code', 'like', "%{$search}%");
            });
        }

        $technicians = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'technicians' => $technicians
        ]);
    }

    public function updateTechnicianStatus(Request $request, Technician $technician)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:incomplete,pending,active,inactive,blacklist',
            'availability' => 'nullable|in:online,offline,on_leave',
            'reason' => 'nullable|string|max:500',
            'admin_comments' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = ['status' => $request->status];
        
        if ($request->has('availability')) {
            $updateData['availability'] = $request->availability;
        }
        
        if ($request->has('admin_comments')) {
            $updateData['admin_comments'] = $request->admin_comments;
        }

        $technician->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Technician status updated successfully',
            'technician' => $technician->fresh()
        ]);
    }

    public function approveTechnician(Request $request, Technician $technician)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'admin_comments' => 'nullable|string',
            'admin_signature' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [
            'status' => $request->status === 'approved' ? 'active' : 'inactive',
            'admin_comments' => $request->admin_comments,
            'admin_signature' => $request->admin_signature,
        ];

        $technician->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Technician approval updated successfully',
            'technician' => $technician->fresh()
        ]);
    }

    public function orders(Request $request)
    {
        $query = Order::with(['farmer.user', 'cattleFarm', 'orderItems.bullSemen.breed', 'jobs.technician.user', 'jobs.cowInseminationRecords.orderItem.bullSemen.breed']);
        
        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Farmer filter
        if ($request->has('farmer_id') && $request->farmer_id) {
            $query->where('farmer_id', $request->farmer_id);
        }
        
        // Farmer name filter
        if ($request->has('farmer_name') && $request->farmer_name) {
            $query->whereHas('farmer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->farmer_name . '%');
            });
        }
        
        // Farm filter
        if ($request->has('farm') && $request->farm) {
            $query->whereHas('cattleFarm', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->farm . '%');
            });
        }
        
        // Technician filter
        if ($request->has('technician') && $request->technician) {
            $query->whereHas('jobs.technician', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->technician . '%');
            });
        }
        
        // Date range filter
        if ($request->has('date_range') && $request->date_range) {
            $now = now();
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()]);
                    break;
            }
        }
        
        // Date range with specific dates
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 10);
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function showOrder(Request $request, Order $order)
    {
        // Admin can view any order, no access restrictions
        $order->load(['farmer.user', 'cattleFarm', 'orderItems.bullSemen.breed', 'jobs.technician.user', 'jobs.cowInseminationRecords.orderItem.bullSemen.breed']);

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    public function jobs(Request $request)
    {
        $query = Job::with(['order.farmer.user', 'order.cattleFarm', 'technician.user', 'order.orderItems.bullSemen.breed']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('progress_status')) {
            $query->where('progress_status', $request->progress_status);
        }
        
        if ($request->has('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'jobs' => $jobs
        ]);
    }

    public function analytics(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate && $endDate) {
            $query = Order::whereBetween('created_at', [$startDate, $endDate]);
        } else {
            switch ($period) {
                case 'day':
                    $query = Order::whereDate('created_at', today());
                    break;
                case 'week':
                    $query = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query = Order::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query = Order::whereYear('created_at', now()->year);
                    break;
                default:
                    $query = Order::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
            }
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->sum('total_amount');
        $completedOrders = $query->where('status', 'completed')->count();
        $pendingOrders = $query->where('status', 'pending')->count();

        $totalFarmers = Farmer::count();
        $activeFarmers = Farmer::where('status', 'active')->count();
        $totalTechnicians = Technician::count();
        $activeTechnicians = Technician::where('status', 'active')->count();
        $onlineTechnicians = Technician::where('availability', 'online')->count();

        $topTechnicians = Job::with('technician.user')
            ->selectRaw('technician_id, COUNT(*) as job_count, SUM(amount_paid) as total_earnings')
            ->where('status', 'completed')
            ->groupBy('technician_id')
            ->orderBy('total_earnings', 'desc')
            ->limit(5)
            ->get();

        $recentOrders = Order::with(['farmer.user', 'cattleFarm'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'analytics' => [
                'orders' => [
                    'total' => $totalOrders,
                    'completed' => $completedOrders,
                    'pending' => $pendingOrders,
                    'revenue' => $totalRevenue,
                ],
                'users' => [
                    'total_farmers' => $totalFarmers,
                    'active_farmers' => $activeFarmers,
                    'total_technicians' => $totalTechnicians,
                    'active_technicians' => $activeTechnicians,
                    'online_technicians' => $onlineTechnicians,
                ],
                'top_technicians' => $topTechnicians,
                'recent_orders' => $recentOrders,
                'period' => $period,
            ]
        ]);
    }

    public function breeds(Request $request)
    {
        $breeds = Breed::with('bullSemen')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'breeds' => $breeds
        ]);
    }

    public function storeBreed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:breeds,name',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $breedData = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/breeds', $imageName);
                $breedData['image'] = 'breeds/' . $imageName;
            }

            $breed = Breed::create($breedData);

            return response()->json([
                'success' => true,
                'message' => 'Breed created successfully',
                'data' => $breed->load('bullSemen')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create breed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBreed(Request $request, Breed $breed)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:breeds,name,' . $breed->id,
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true)
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($breed->image) {
                    Storage::delete('public/' . $breed->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/breeds', $imageName);
                $updateData['image'] = 'breeds/' . $imageName;
            }

            $breed->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Breed updated successfully',
                'data' => $breed->load('bullSemen')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update breed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyBreed(Breed $breed)
    {
        try {
            // Check if breed has semen products
            if ($breed->bullSemen()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete breed with existing semen products'
                ], 400);
            }

            // Delete image if exists
            if ($breed->image) {
                Storage::delete('public/' . $breed->image);
            }

            $breed->delete();

            return response()->json([
                'success' => true,
                'message' => 'Breed deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete breed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function semenProducts(Request $request)
    {
        $query = BullSemen::with('breed');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(20);

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    public function earnings(Request $request)
    {
        $period = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate && $endDate) {
            $query = Order::whereBetween('created_at', [$startDate, $endDate]);
        } else {
            switch ($period) {
                case 'month':
                    $query = Order::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query = Order::whereYear('created_at', now()->year);
                    break;
                default:
                    $query = Order::query();
            }
        }

        $totalRevenue = $query->sum('total_amount');
        $totalOrders = $query->count();

        // Calculate platform earnings (10% of total revenue)
        $platformEarnings = $totalRevenue * 0.10;
        $technicianEarnings = $totalRevenue * 0.90;

        // Get earnings by technician
        $earningsByTechnician = Job::with('technician.user')
            ->selectRaw('technician_id, COUNT(*) as orders_count, SUM(amount_paid) as total_earnings, SUM(amount_paid) * 0.9 as commission')
            ->where('status', 'completed')
            ->whereHas('order', function($q) use ($query) {
                $q->whereIn('id', $query->pluck('id'));
            })
            ->groupBy('technician_id')
            ->orderBy('total_earnings', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->technician_id,
                    'name' => $item->technician->user->name ?? 'Unknown',
                    'orders_count' => $item->orders_count,
                    'total_earnings' => $item->total_earnings,
                    'commission' => $item->commission
                ];
            });

        return response()->json([
            'success' => true,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'platform_earnings' => $platformEarnings,
            'technician_earnings' => $technicianEarnings,
            'by_technician' => $earningsByTechnician,
        ]);
    }

    public function performance(Request $request)
    {
        $period = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate && $endDate) {
            $query = Job::whereBetween('created_at', [$startDate, $endDate]);
        } else {
            switch ($period) {
                case 'month':
                    $query = Job::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query = Job::whereYear('created_at', now()->year);
                    break;
                default:
                    $query = Job::query();
            }
        }

        $totalJobs = $query->count();
        $completedJobs = $query->where('status', 'completed')->count();
        $successRate = $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100, 2) : 0;

        // Calculate average response time (time from job creation to acceptance)
        $avgResponseTime = Job::whereNotNull('estimated_visit_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, estimated_visit_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

        // Calculate customer satisfaction (average rating)
        $customerSatisfaction = Job::whereNotNull('rating')
            ->avg('rating') ?? 0;

        // Get technician performance - use the same date filtering as the main query
        $technicianPerformance = $query->with('technician.user')
            ->selectRaw('
                technician_id,
                COUNT(*) as total_jobs,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_jobs,
                AVG(rating) as avg_rating,
                AVG(TIMESTAMPDIFF(HOUR, created_at, estimated_visit_at)) as avg_response_time
            ')
            ->groupBy('technician_id')
            ->orderBy('avg_rating', 'desc')
            ->get()
            ->map(function($item) {
                $successRate = $item->total_jobs > 0 ? round(($item->completed_jobs / $item->total_jobs) * 100, 1) : 0;
                return [
                    'id' => $item->technician_id,
                    'name' => $item->technician->user->name ?? 'Unknown',
                    'jobs_completed' => $item->completed_jobs,
                    'success_rate' => $successRate,
                    'avg_rating' => round($item->avg_rating ?? 0, 1),
                    'avg_response_time' => round($item->avg_response_time ?? 0, 1)
                ];
            });

        return response()->json([
            'success' => true,
            'success_rate' => $successRate,
            'avg_response_time' => round($avgResponseTime, 1),
            'customer_satisfaction' => round($customerSatisfaction, 1),
            'total_jobs' => $totalJobs,
            'completed_jobs' => $completedJobs,
            'technicians' => $technicianPerformance,
        ]);
    }

    public function stock(Request $request)
    {
        // Get stock information for semen products
        $totalItems = BullSemen::sum('stock_quantity');
        $lowStockItems = BullSemen::where('stock_quantity', '<=', 10)->count();
        $outOfStockItems = BullSemen::where('stock_quantity', '=', 0)->count();
        
        // Calculate total value
        $totalValue = BullSemen::selectRaw('SUM(price * stock_quantity) as total')
            ->value('total') ?? 0;

        // Get stock by breed
        $stockByBreed = BullSemen::with('breed')
            ->selectRaw('breed_id, SUM(stock_quantity) as total_stock, AVG(price) as avg_price')
            ->groupBy('breed_id')
            ->orderBy('total_stock', 'desc')
            ->get();

        // Get stock by technician using many-to-many relationship with quantities
        $stockByTechnician = Technician::with(['user', 'semenProducts' => function($query) {
            $query->withPivot('quantity');
        }])
            ->whereHas('semenProducts')
            ->get()
            ->map(function($technician) {
                $totalQuantity = $technician->semenProducts->sum('pivot.quantity');
                $lowStockCount = $technician->semenProducts->where('pivot.quantity', '<=', 10)->where('pivot.quantity', '>', 0)->count();
                // Count items where technician's assigned quantity is 0 OR main inventory is 0
                $outOfStockCount = $technician->semenProducts->filter(function($item) {
                    return $item->pivot->quantity == 0 || $item->stock_quantity == 0;
                })->count();
                
                return [
                    'id' => $technician->id,
                    'name' => $technician->user?->name ?? 'Unknown Technician',
                    'total_items' => $technician->semenProducts->count(),
                    'total_quantity' => $totalQuantity,
                    'low_stock_items' => $lowStockCount,
                    'out_of_stock_items' => $outOfStockCount,
                    'stock_items' => $technician->semenProducts->map(function($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'breed' => $item->breed->name ?? 'Unknown',
                            'quantity' => $item->pivot->quantity,
                            'price' => $item->price,
                            'main_stock' => $item->stock_quantity
                        ];
                    })
                ];
            });

        // Get low stock items
        $lowStockProducts = BullSemen::with('breed')
            ->where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'total_items' => $totalItems,
            'low_stock' => $lowStockItems,
            'out_of_stock' => $outOfStockItems,
            'total_value' => $totalValue,
            'by_breed' => $stockByBreed,
            'by_technician' => $stockByTechnician,
            'low_stock_products' => $lowStockProducts,
        ]);
    }

    public function storeSemenProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'breed_id' => 'required|exists:breeds,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'collection_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:collection_date',
            'description' => 'nullable|string',
            'bull_characteristics' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'name' => $request->name,
                'breed_id' => $request->breed_id,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'collection_date' => $request->collection_date,
                'expiry_date' => $request->expiry_date,
                'description' => $request->description,
                'bull_characteristics' => $request->bull_characteristics,
                'is_active' => $request->boolean('is_active', true)
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Ensure directory exists
                $directory = storage_path('app/public/semen_images');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Store the file
                $stored = $image->move($directory, $imageName);
                
                if ($stored) {
                    $data['image'] = 'semen_images/' . $imageName;
                }
            }

            $semenProduct = BullSemen::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Semen product created successfully',
                'data' => $semenProduct->load('breed', 'technicians.user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create semen product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroySemenProduct(BullSemen $semenProduct)
    {
        try {
            // Detach all technicians first
            $semenProduct->technicians()->detach();
            
            // Delete the semen product
            $semenProduct->delete();

            return response()->json([
                'success' => true,
                'message' => 'Semen product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete semen product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSemenProduct(Request $request, BullSemen $semenProduct)
    {
        try {
            // Debug logging
            \Log::info('Update semen product request received', [
                'semen_product_id' => $semenProduct->id,
                'has_image' => $request->hasFile('image'),
                'all_files' => $request->allFiles(),
                'request_data' => $request->except(['image'])
            ]);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'breed_id' => 'required|exists:breeds,id',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'collection_date' => 'nullable|date',
                'expiry_date' => 'nullable|date|after:collection_date',
                'description' => 'nullable|string',
                'bull_characteristics' => 'nullable|string',
                'is_active' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = [
                'name' => $request->name,
                'bull_name' => $request->name, // Map name to bull_name for database
                'breed_id' => $request->breed_id,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'collection_date' => $request->collection_date,
                'expiry_date' => $request->expiry_date,
                'description' => $request->description,
                'bull_characteristics' => $request->bull_characteristics,
                'is_active' => $request->is_active ?? true
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                \Log::info('Image file detected, processing upload');
                
                // Delete old image if exists
                if ($semenProduct->image) {
                    $oldImagePath = storage_path('app/public/' . $semenProduct->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                        \Log::info('Old image deleted: ' . $oldImagePath);
                    }
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                \Log::info('Storing image with name: ' . $imageName);
                \Log::info('Image file details:', [
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime_type' => $image->getMimeType(),
                    'is_valid' => $image->isValid(),
                    'temp_path' => $image->getPathname()
                ]);
                
                // Ensure directory exists
                $directory = storage_path('app/public/semen_images');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                    \Log::info('Created directory: ' . $directory);
                }
                
                // Store the file
                $fullPath = $directory . '/' . $imageName;
                \Log::info('Attempting to move file to: ' . $fullPath);
                
                $stored = $image->move($directory, $imageName);
                
                if ($stored) {
                    $data['image'] = 'semen_images/' . $imageName;
                    \Log::info('Image stored successfully at: ' . $fullPath);
                    \Log::info('Image path in database: ' . $data['image']);
                    
                    // Verify file exists
                    if (file_exists($fullPath)) {
                        \Log::info('File verification: File exists at ' . $fullPath);
                    } else {
                        \Log::error('File verification: File does not exist at ' . $fullPath);
                    }
                } else {
                    \Log::error('Failed to store image at: ' . $fullPath);
                }
            } else {
                \Log::info('No image file in request');
            }

            \Log::info('Updating database with data:', $data);
            $semenProduct->update($data);
            \Log::info('Database update completed');

            return response()->json([
                'success' => true,
                'message' => 'Semen product updated successfully',
                'semen_product' => $semenProduct->load('breed')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update semen product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSemenProductStatus(Request $request, BullSemen $semenProduct)
    {
        try {
            $request->validate([
                'is_active' => 'required|boolean'
            ]);

            $semenProduct->update([
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Semen product status updated successfully',
                'semen_product' => $semenProduct
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update semen product status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTechniciansForStock()
    {
        $technicians = Technician::with('user')
            ->where('status', 'active')
            ->get()
            ->map(function($technician) {
                return [
                    'id' => $technician->id,
                    'name' => $technician->user?->name ?? 'Unknown Technician',
                    'unique_code' => $technician->unique_code
                ];
            });

        return response()->json([
            'success' => true,
            'technicians' => $technicians
        ]);
    }

    public function assignStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'technician_id' => 'required|exists:technicians,id',
            'stock_assignments' => 'required|array',
            'stock_assignments.*.stock_item_id' => 'required|exists:bull_semen,id',
            'stock_assignments.*.quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $technician = Technician::findOrFail($request->technician_id);
            $assignedCount = 0;

            // Process each stock assignment
            foreach ($request->stock_assignments as $assignment) {
                $stockItem = BullSemen::findOrFail($assignment['stock_item_id']);
                $quantity = $assignment['quantity'];

                // Check if there's enough stock available
                if ($stockItem->stock_quantity < $quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$stockItem->name}. Available: {$stockItem->stock_quantity}, Requested: {$quantity}"
                    ], 400);
                }

                // Check if already assigned to this technician
                $existingAssignment = $technician->semenProducts()->where('bull_semen_id', $stockItem->id)->first();
                
                if ($existingAssignment) {
                    // Update existing assignment with new quantity
                    $technician->semenProducts()->updateExistingPivot($stockItem->id, [
                        'quantity' => $existingAssignment->pivot->quantity + $quantity
                    ]);
                } else {
                    // Create new assignment
                    $technician->semenProducts()->attach($stockItem->id, [
                        'quantity' => $quantity
                    ]);
                }

                // Update the main stock quantity
                $stockItem->decrement('stock_quantity', $quantity);
                $assignedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => 'Stock items assigned successfully',
                'assigned_count' => $assignedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign stock items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assignJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'technician_id' => 'required|exists:technicians,id',
            'estimated_visit_at' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $order = Order::findOrFail($request->order_id);
            $technician = Technician::findOrFail($request->technician_id);

            // Check if technician is active and available
            if ($technician->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot assign job to inactive technician'
                ], 400);
            }

            // Check if job already exists for this order
            $existingJob = Job::where('order_id', $request->order_id)->first();
            if ($existingJob) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job already exists for this order'
                ], 400);
            }

            // Create the job
            $job = Job::create([
                'order_id' => $request->order_id,
                'technician_id' => $request->technician_id,
                'status' => 'assigned',
                'progress_status' => 'pending',
                'estimated_visit_at' => $request->estimated_visit_at,
                'technician_notes' => $request->notes
            ]);

            // Update order status to 'assigned'
            $order->update(['status' => 'assigned']);

            return response()->json([
                'success' => true,
                'message' => 'Job assigned successfully',
                'job' => $job->load(['order.farmer.user', 'technician.user'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateJobStatus(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:assigned,accepted,rejected,completed,cancelled',
            'progress_status' => 'nullable|in:pending,in_progress,completed',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'status' => $request->status
            ];

            if ($request->has('progress_status')) {
                $updateData['progress_status'] = $request->progress_status;
            }

            if ($request->has('notes')) {
                $updateData['technician_notes'] = $request->notes;
            }

            $job->update($updateData);

            // Update order status based on job status
            if ($request->status === 'completed') {
                $job->order->update(['status' => 'completed']);
            } elseif ($request->status === 'cancelled') {
                $job->order->update(['status' => 'cancelled']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Job status updated successfully',
                'job' => $job->load(['order.farmer.user', 'technician.user'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getReassignmentLogs(Request $request)
    {
        $logs = \App\Models\OrderReassignmentLog::with([
            'order.cattleFarm',
            'order.farmer.user',
            'fromTechnician.user',
            'toTechnician.user',
            'reassignedByTechnician.user'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return response()->json([
            'success' => true,
            'logs' => $logs
        ]);
    }

    public function getPregnancyStats(Request $request)
    {
        // Derive stats from individual cow insemination records to avoid relying on job-level cow_status
        $recordStats = \App\Models\CowInseminationRecord::selectRaw('
                COALESCE(pregnancy_status, "pending") as status,
                COUNT(*) as count
            ')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $conceived = $recordStats->get('conceived', (object)['count' => 0])->count;
        $pregnant = $recordStats->get('pregnant', (object)['count' => 0])->count;
        $delivered = $recordStats->get('delivered', (object)['count' => 0])->count;
        $miscarried = $recordStats->get('miscarried', (object)['count' => 0])->count;

        $totalPregnancies = $conceived + $pregnant + $delivered + $miscarried;
        $successRate = $totalPregnancies > 0 ? round(($delivered / $totalPregnancies) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'stats' => [
                'total_pregnancies' => $totalPregnancies,
                'conceived_count' => $conceived,
                'pregnant_count' => $pregnant,
                'delivered_count' => $delivered,
                'miscarried_count' => $miscarried,
                'success_rate' => $successRate,
                'avg_days_pregnant' => null,
            ]
        ]);
    }

    public function getPregnancyJobs(Request $request)
    {
        $jobs = Job::with([
            'order.cattleFarm',
            'order.farmer.user',
            'technician.user',
            'order.orderItems.bullSemen.breed',
            'cowInseminationRecords.orderItem.bullSemen.breed'
        ])
        // Only include jobs that have cow_insemination_records entries
        ->whereHas('cowInseminationRecords')
        ->orderBy('updated_at', 'desc')
        ->get()
        ->map(function ($job) {
            // Derive a job-level status from its cow records if job.cow_status is missing
            if (empty($job->cow_status)) {
                $statuses = collect($job->cowInseminationRecords)->pluck('pregnancy_status')->filter();
                $derived = null;
                if ($statuses->contains('delivered')) {
                    $derived = 'delivered';
                } elseif ($statuses->contains('miscarried')) {
                    $derived = 'miscarried';
                } elseif ($statuses->contains('pregnant')) {
                    $derived = 'pregnant';
                } elseif ($statuses->contains('conceived')) {
                    $derived = 'conceived';
                }
                $job->setAttribute('cow_status', $derived ?? 'pending');
            }
            return $job;
        });

        return response()->json([
            'success' => true,
            'jobs' => $jobs
        ]);
    }
}