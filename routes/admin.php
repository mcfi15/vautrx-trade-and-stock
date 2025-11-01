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
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\LoginHistoryController;

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
    
    // Deposit Management
    Route::get('deposits', [DepositController::class, 'index'])->name('deposits.index');
    Route::get('deposits/create', [DepositController::class, 'create'])->name('deposits.create');
    Route::post('deposits', [DepositController::class, 'store'])->name('deposits.store');
    Route::get('deposits/{deposit}', [DepositController::class, 'show'])->name('deposits.show');
    Route::get('deposits/{deposit}/edit', [DepositController::class, 'edit'])->name('deposits.edit');
    Route::put('deposits/{deposit}', [DepositController::class, 'update'])->name('deposits.update');
    Route::delete('deposits/{deposit}', [DepositController::class, 'destroy'])->name('deposits.destroy');
    Route::post('deposits/{deposit}/confirm', [DepositController::class, 'confirm'])->name('deposits.confirm');
    Route::get('deposits/user/{user}', [DepositController::class, 'getUserDeposits'])->name('deposits.user');
    
    // Payment Proof Management
    Route::get('deposits/{deposit}/payment-proof', [DepositController::class, 'viewPaymentProof'])->name('deposits.payment-proof');
    Route::get('deposits/{deposit}/payment-proof/download', [DepositController::class, 'downloadPaymentProof'])->name('deposits.payment-proof.download');
    Route::delete('deposits/{deposit}/payment-proof', [DepositController::class, 'deletePaymentProof'])->name('deposits.payment-proof.delete');
    Route::post('deposits/{deposit}/approve', [DepositController::class, 'approve'])->name('deposits.approve');
    Route::post('deposits/{deposit}/reject', [DepositController::class, 'reject'])->name('deposits.reject');
    
    // Withdrawal Management
    Route::get('withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('withdrawals/create', [WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('withdrawals/{withdrawal}', [WithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::get('withdrawals/{withdrawal}/edit', [WithdrawalController::class, 'edit'])->name('withdrawals.edit');
    Route::put('withdrawals/{withdrawal}', [WithdrawalController::class, 'update'])->name('withdrawals.update');
    Route::delete('withdrawals/{withdrawal}', [WithdrawalController::class, 'destroy'])->name('withdrawals.destroy');
    Route::post('withdrawals/{withdrawal}/approve', [WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('withdrawals/{withdrawal}/complete', [WithdrawalController::class, 'complete'])->name('withdrawals.complete');
    Route::post('withdrawals/{withdrawal}/reject', [WithdrawalController::class, 'reject'])->name('withdrawals.reject');
    Route::get('withdrawals/user/{user}', [WithdrawalController::class, 'getUserWithdrawals'])->name('withdrawals.user');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // OAuth Settings
    Route::get('settings/oauth', [OAuthSettingsController::class, 'index'])->name('settings.oauth');
    Route::put('settings/oauth', [OAuthSettingsController::class, 'update'])->name('settings.oauth.update');
    Route::post('settings/oauth/test', [OAuthSettingsController::class, 'testConnection'])->name('settings.oauth.test');
    
    // Login History Management
    Route::get('login-history', [LoginHistoryController::class, 'index'])->name('login-history.index');
    Route::get('login-history/{id}', [LoginHistoryController::class, 'show'])->name('login-history.show');
});
