<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CryptoPriceController;

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
