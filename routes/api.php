<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CryptoApiController;
use App\Http\Controllers\Api\AdminCryptoController;
use App\Http\Controllers\Api\CryptoPriceController;
use App\Http\Controllers\Admin\CryptocurrencyController;

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

// Webhook to receive live price update (called by your Node WS script)
Route::post('/v1/admin/cryptos/webhook-price', [CryptocurrencyController::class, 'webhookUpdatePrice'])
    ->middleware('throttle:60,1'); // optional throttle

// Route::prefix('v1/admin/crypto')->middleware('auth:sanctum')->group(function () {
//     Route::get('/', [CryptoApiController::class, 'list']);
//     Route::post('/batch-update', [CryptoApiController::class, 'batchUpdate']);
//     Route::get('/status', [CryptoApiController::class, 'status']);
// });

// Route::prefix('v1/admin/crypto')->group(function () {
    
//     Route::get('/', [CryptocurrencyController::class, 'apiList']); // GET prices
//     Route::get('/status', [CryptocurrencyController::class, 'apiStatus']); // GET status

//     Route::post('/batch-update', [CryptocurrencyController::class, 'batchUpdatePrices']); // POST update
// });