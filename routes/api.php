<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FarmerController;
use App\Http\Controllers\Api\TechnicianController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Admin\CowTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/remembered-mobile', [AuthController::class, 'rememberedMobile']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/auth/resend-otp', [AuthController::class, 'resendOtp']);
// Forgot password via OTP
Route::post('/auth/forgot-password/request-otp', [AuthController::class, 'forgotPasswordRequestOtp']);
Route::post('/auth/forgot-password/verify-otp', [AuthController::class, 'forgotPasswordVerifyOtp']);
Route::post('/auth/forgot-password/reset', [AuthController::class, 'resetPassword']);

// Public product routes (no authentication required)
Route::get('/public/products/breeds', [ProductController::class, 'breeds']);
Route::get('/public/products/semen', [ProductController::class, 'semen']);
Route::get('/public/products/semen/{semen}', [ProductController::class, 'showSemen']);
Route::get('/public/cow-types', [CowTypeController::class, 'active']);

// Guest order routes (no authentication required)
Route::post('/guest/orders', [App\Http\Controllers\Api\GuestOrderController::class, 'store']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    
    // Farmer routes
    Route::prefix('farmer')->group(function () {
        Route::get('/profile', [FarmerController::class, 'profile']);
        Route::put('/profile', [FarmerController::class, 'updateProfile']);
        Route::get('/farms', [FarmerController::class, 'farms']);
        Route::post('/farms', [FarmerController::class, 'createFarm']);
        Route::put('/farms/{farm}', [FarmerController::class, 'updateFarm']);
        Route::delete('/farms/{farm}', [FarmerController::class, 'deleteFarm']);
        Route::get('/orders', [FarmerController::class, 'orders']);
        Route::get('/orders/{order}', [FarmerController::class, 'showOrder']);
        Route::get('/jobs/{jobId}', [FarmerController::class, 'showJob']);
        Route::post('/jobs/{jobId}/confirm', [FarmerController::class, 'confirmService']);
        Route::get('/jobs', [FarmerController::class, 'getJobs']);
        Route::get('/cow-insemination-records', [FarmerController::class, 'getCowInseminationRecords']);
        Route::put('/jobs/{jobId}/conception', [FarmerController::class, 'markConception']);
        Route::put('/jobs/{jobId}/pregnancy', [FarmerController::class, 'markPregnancy']);
        Route::put('/jobs/{jobId}/miscarriage', [FarmerController::class, 'markMiscarriage']);
        Route::put('/jobs/{jobId}/delivery', [FarmerController::class, 'markDelivery']);
        Route::put('/cow-insemination-records/{recordId}/conception', [FarmerController::class, 'markCowConception']);
        Route::put('/cow-insemination-records/{recordId}/pregnancy', [FarmerController::class, 'markCowPregnancy']);
        Route::put('/cow-insemination-records/{recordId}/miscarriage', [FarmerController::class, 'markCowMiscarriage']);
        Route::put('/cow-insemination-records/{recordId}/delivery', [FarmerController::class, 'markCowDelivery']);
        Route::post('/cow-insemination-records/{recordId}/delivery', [FarmerController::class, 'markCowDelivery']);
        Route::get('/pregnancy-stats', [FarmerController::class, 'getPregnancyStats']);
        Route::get('/pregnancy-notifications', [FarmerController::class, 'checkPregnancyNotifications']);
    });
    
    // Technician routes
    Route::prefix('technician')->group(function () {
        Route::get('/profile', [TechnicianController::class, 'profile']);
        Route::put('/profile', [TechnicianController::class, 'updateProfile']);
        Route::get('/jobs', [TechnicianController::class, 'jobs']);
        Route::get('/jobs/{job}', [TechnicianController::class, 'showJob']);
        Route::put('/jobs/{job}/accept', [TechnicianController::class, 'acceptJob']);
        Route::put('/jobs/{job}/reject', [TechnicianController::class, 'rejectJob']);
        Route::put('/jobs/{job}/eta', [TechnicianController::class, 'submitETA']);
        Route::put('/jobs/{job}/start', [TechnicianController::class, 'startJob']);
        Route::put('/jobs/{job}/no-heat', [TechnicianController::class, 'markNoHeat']);
        Route::put('/jobs/{job}/administer', [TechnicianController::class, 'administerSemen']);
        Route::put('/jobs/{job}/complete', [TechnicianController::class, 'completeJob']);
        Route::put('/jobs/{job}/conception', [TechnicianController::class, 'markConception']);
        Route::put('/jobs/{job}/pregnancy', [TechnicianController::class, 'markPregnancy']);
        Route::put('/jobs/{job}/miscarriage', [TechnicianController::class, 'markMiscarriage']);
        Route::put('/jobs/{job}/delivery', [TechnicianController::class, 'markDelivery']);
        Route::put('/jobs/{job}/reassign', [TechnicianController::class, 'reassignJob']);
        Route::get('/reassignment-logs', [TechnicianController::class, 'getReassignmentLogs']);
        Route::get('/available-technicians', [TechnicianController::class, 'getAvailableTechnicians']);
        Route::put('/availability', [TechnicianController::class, 'updateAvailability']);
        Route::get('/earnings', [TechnicianController::class, 'earnings']);
        Route::get('/inventory', [TechnicianController::class, 'inventory']);
        Route::post('/upload-document', [TechnicianController::class, 'uploadDocument']);
        Route::delete('/delete-document', [TechnicianController::class, 'deleteDocument']);
    });
    
    // Public technician search routes (for farmers)
    Route::get('/technicians/nearby', [TechnicianController::class, 'getNearbyTechnicians']);
    Route::get('/technicians/search', [TechnicianController::class, 'searchTechnicians']);
    Route::get('/technicians/location-options', [TechnicianController::class, 'getLocationOptions']);

    
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/farmers', [AdminController::class, 'farmers']);
        Route::put('/farmers/{farmer}/status', [AdminController::class, 'updateFarmerStatus']);
        Route::get('/technicians', [AdminController::class, 'technicians']);
        Route::put('/technicians/{technician}/status', [AdminController::class, 'updateTechnicianStatus']);
        Route::put('/technicians/{technician}/approve', [AdminController::class, 'approveTechnician']);
        Route::get('/orders', [AdminController::class, 'orders']);
        Route::get('/orders/{order}', [AdminController::class, 'showOrder']);
        Route::get('/jobs', [AdminController::class, 'jobs']);
        Route::post('/jobs/assign', [AdminController::class, 'assignJob']);
        Route::put('/jobs/{job}/status', [AdminController::class, 'updateJobStatus']);
        Route::get('/analytics', [AdminController::class, 'analytics']);
        Route::get('/breeds', [AdminController::class, 'breeds']);
        Route::post('/breeds', [AdminController::class, 'storeBreed']);
        Route::put('/breeds/{breed}', [AdminController::class, 'updateBreed']);
        Route::post('/breeds/{breed}', [AdminController::class, 'updateBreed']); // For FormData with _method=PUT
        Route::delete('/breeds/{breed}', [AdminController::class, 'destroyBreed']);
        Route::get('/semen-products', [AdminController::class, 'semenProducts']);
        Route::post('/semen-products', [AdminController::class, 'storeSemenProduct']);
        Route::put('/semen-products/{semenProduct}', [AdminController::class, 'updateSemenProduct']);
        Route::post('/semen-products/{semenProduct}', [AdminController::class, 'updateSemenProduct']); // For FormData with _method=PUT
        Route::put('/semen-products/{semenProduct}/status', [AdminController::class, 'updateSemenProductStatus']);
        Route::delete('/semen-products/{semenProduct}', [AdminController::class, 'destroySemenProduct']);
        Route::get('/technicians-for-stock', [AdminController::class, 'getTechniciansForStock']);
        Route::post('/assign-stock', [AdminController::class, 'assignStock']);
        Route::get('/earnings', [AdminController::class, 'earnings']);
        Route::get('/performance', [AdminController::class, 'performance']);
        Route::get('/stock', [AdminController::class, 'stock']);
        Route::get('/reassignment-logs', [AdminController::class, 'getReassignmentLogs']);
        
        // Cow Types Management
        Route::get('/cow-types', [CowTypeController::class, 'index']);
        Route::post('/cow-types', [CowTypeController::class, 'store']);
        Route::get('/cow-types/{cowType}', [CowTypeController::class, 'show']);
        Route::put('/cow-types/{cowType}', [CowTypeController::class, 'update']);
        Route::delete('/cow-types/{cowType}', [CowTypeController::class, 'destroy']);
        Route::get('/pregnancy-stats', [AdminController::class, 'getPregnancyStats']);
        Route::get('/pregnancy-jobs', [AdminController::class, 'getPregnancyJobs']);
        
        // Debug route for testing image upload
        Route::post('/test-image-upload', function(Request $request) {
            \Log::info('Test image upload request received');
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                \Log::info('Test upload - Image details:', [
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                    'mime_type' => $image->getMimeType(),
                    'is_valid' => $image->isValid(),
                    'temp_path' => $image->getPathname()
                ]);
                
                $directory = storage_path('app/public/semen_images');
                $fullPath = $directory . '/' . $imageName;
                
                \Log::info('Test upload - Attempting to move to: ' . $fullPath);
                
                $stored = $image->move($directory, $imageName);
                
                if ($stored) {
                    \Log::info('Test upload - File moved successfully');
                    if (file_exists($fullPath)) {
                        \Log::info('Test upload - File exists at: ' . $fullPath);
                        return response()->json(['success' => true, 'message' => 'File uploaded successfully', 'path' => $fullPath]);
                    } else {
                        \Log::error('Test upload - File does not exist after move');
                        return response()->json(['success' => false, 'message' => 'File does not exist after move']);
                    }
                } else {
                    \Log::error('Test upload - Failed to move file');
                    return response()->json(['success' => false, 'message' => 'Failed to move file']);
                }
            } else {
                \Log::info('Test upload - No image file in request');
                return response()->json(['success' => false, 'message' => 'No image file in request']);
            }
        });
    });
    
    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::put('/{order}', [OrderController::class, 'update']);
        Route::delete('/{order}', [OrderController::class, 'destroy']);
    });
    
    // Product routes
    Route::prefix('products')->group(function () {
        Route::get('/breeds', [ProductController::class, 'breeds']);
        Route::get('/semen', [ProductController::class, 'semen']);
        Route::get('/semen/{semen}', [ProductController::class, 'showSemen']);
    });
    
    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::put('/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
    });
    
    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::get('/{payment}', [PaymentController::class, 'show']);
        Route::post('/', [PaymentController::class, 'create']);
        Route::put('/{payment}/status', [PaymentController::class, 'updateStatus']);
        Route::get('/analytics', [PaymentController::class, 'analytics']);
    });
    
    // Review routes
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::get('/{review}', [ReviewController::class, 'show']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::put('/{review}', [ReviewController::class, 'update']);
        Route::delete('/{review}', [ReviewController::class, 'destroy']);
        Route::get('/technician/{technician}/stats', [ReviewController::class, 'technicianStats']);
    });
    
    // Message routes
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index']);
        Route::get('/conversations', [MessageController::class, 'conversations']);
        Route::post('/', [MessageController::class, 'store']);
        Route::put('/{message}/read', [MessageController::class, 'markAsRead']);
        Route::put('/mark-conversation-read', [MessageController::class, 'markConversationAsRead']);
        Route::get('/unread-count', [MessageController::class, 'unreadCount']);
        Route::delete('/{message}', [MessageController::class, 'destroy']);
    });
});
