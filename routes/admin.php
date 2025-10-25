<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CryptocurrencyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TradingPairController;
use App\Http\Controllers\Admin\OAuthSettingsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group.
|
*/

// Admin Authentication Routes (Guest Only)
Route::prefix('admin')->name('admin.')->middleware(['guest:admin'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [AdminDashboardController::class, 'index']);
    
    // Cryptocurrency Management
    Route::resource('cryptocurrencies', CryptocurrencyController::class);
    Route::post('cryptocurrencies/{cryptocurrency}/toggle-status', [CryptocurrencyController::class, 'toggleStatus'])
        ->name('cryptocurrencies.toggle-status');
    Route::post('cryptocurrencies/{cryptocurrency}/toggle-realtime', [CryptocurrencyController::class, 'toggleRealtime'])
        ->name('cryptocurrencies.toggle-realtime');
    Route::post('cryptocurrencies/sync-from-binance', [CryptocurrencyController::class, 'syncFromBinance'])
        ->name('cryptocurrencies.sync-from-binance');
    Route::post('cryptocurrencies/sync-prices', [CryptocurrencyController::class, 'syncPrices'])
        ->name('cryptocurrencies.sync-prices');
    Route::post('cryptocurrencies/update-prices', [CryptocurrencyController::class, 'updatePrices'])
        ->name('cryptocurrencies.update-prices');
    
    // Trading Pair Management
    Route::resource('trading-pairs', TradingPairController::class);
    Route::post('trading-pairs/{pair}/toggle-status', [TradingPairController::class, 'toggleStatus'])
        ->name('trading-pairs.toggle-status');
    
    // User Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    
    // Order Management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    
    // Transaction Management
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('transactions/{transaction}/approve', [TransactionController::class, 'approve'])
        ->name('transactions.approve');
    Route::post('transactions/{transaction}/reject', [TransactionController::class, 'reject'])
        ->name('transactions.reject');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // OAuth Settings
    Route::get('settings/oauth', [OAuthSettingsController::class, 'index'])->name('settings.oauth');
    Route::put('settings/oauth', [OAuthSettingsController::class, 'update'])->name('settings.oauth.update');
    Route::post('settings/oauth/test', [OAuthSettingsController::class, 'testConnection'])->name('settings.oauth.test');
});
