<?php

use Illuminate\Support\Facades\Route;

// Generate application key route
Route::get('/generate-key', function () {
    try {
        // Generate a new application key
        $key = 'base64:' . base64_encode(random_bytes(32));
        
        // Read the .env file
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        
        // Update or add APP_KEY
        if (strpos($envContent, 'APP_KEY=') !== false) {
            $envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);
        } else {
            $envContent .= "\nAPP_KEY=" . $key . "\n";
        }
        
        // Write back to .env file
        file_put_contents($envPath, $envContent);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Application key generated successfully!',
            'key' => $key,
            'timestamp' => now()->toDateTimeString()
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to generate key: ' . $e->getMessage(),
            'timestamp' => now()->toDateTimeString()
        ]);
    }
});

// Test route
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel is working!',
        'app_key_exists' => !empty(config('app.key')),
        'app_name' => config('app.name'),
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Catch all routes and return the SPA view
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');