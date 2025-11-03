<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CryptoPriceController;
use App\Http\Controllers\Api\AdminCryptoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes
Route::prefix('v1')->group(function () {
    
    // Cryptocurrency Prices (Real-time)
    Route::get('/crypto/prices', [CryptoPriceController::class, 'index']);
    Route::get('/crypto/prices/{id}', [CryptoPriceController::class, 'show']);
    Route::get('/crypto/status', [CryptoPriceController::class, 'status']);
    
});

// Admin API Routes (Public - authentication handled by web routes)
Route::prefix('v1/admin')->group(function () {
    
    // Test endpoint to verify API is working
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Admin API is working!',
            'timestamp' => now()->toIso8601String(),
            'user_agent' => request()->userAgent(),
            'ip' => request()->ip(),
        ]);
    });
    
    // Cryptocurrency Management
    Route::get('/crypto', [AdminCryptoController::class, 'index']);
    Route::post('/crypto/update/{id}', [AdminCryptoController::class, 'updatePrice']);
    Route::post('/crypto/batch-update', [AdminCryptoController::class, 'batchUpdate']);
    Route::get('/crypto/status', [AdminCryptoController::class, 'status']);
    
});
