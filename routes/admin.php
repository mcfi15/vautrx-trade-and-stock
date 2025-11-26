<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PoolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\TradeController;
use App\Http\Controllers\Admin\FaucetController;
use App\Http\Controllers\Admin\AirdropController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\GiftCardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\FaucetLogController;
use App\Http\Controllers\Admin\StakePlanController;
use App\Http\Controllers\Admin\UserStakeController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\TradingPairController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AirdropClaimController;
use App\Http\Controllers\Admin\LoginHistoryController;
use App\Http\Controllers\Admin\OAuthSettingsController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CryptocurrencyController;
use App\Http\Controllers\Admin\StockManagementController;

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
    Route::get('/cryptocurrencies', [CryptocurrencyController::class, 'index'])
        ->name('cryptocurrencies.index');

    // Fetch live prices
    Route::get('/cryptocurrencies/prices', [CryptocurrencyController::class, 'fetchPrices'])
        ->name('cryptocurrencies.prices');

    // Service status (real-time connections, API health)
    Route::get('/cryptocurrencies/status', [CryptocurrencyController::class, 'serviceStatus'])
        ->name('cryptocurrencies.status');

    Route::get('/cryptocurrencies/sync-prices', [CryptocurrencyController::class, 'syncPrices'])
        ->name('cryptocurrencies.sync-prices');



    // Sync batch (CoinGecko or Binance)
    Route::post('/cryptocurrencies/sync', [CryptocurrencyController::class, 'syncPrices'])
        ->name('cryptocurrencies.sync-prices');

    Route::post('/cryptocurrencies/sync-from-binance', [CryptocurrencyController::class, 'syncFromBinance'])
        ->name('cryptocurrencies.sync-from-binance');

    
    // Trading Pair Management
    Route::resource('trading-pairs', TradingPairController::class);
    Route::post('trading-pairs/{pair}/toggle-status', [TradingPairController::class, 'toggleStatus'])
        ->name('trading-pairs.toggle-status');

    // User Management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
        ->name('users.toggle-status');
    Route::get('users/{user}/wallets/{wallet}/edit', [UserController::class, 'updateWalletForm'])->name('users.wallets.edit');
    Route::put('users/{user}/wallets/{wallet}', [UserController::class, 'updateWalletBalance'])->name('users.wallets.update');

    Route::post('/users/{user}/withdrawal-permission', [UserController::class, 'updateWithdrawalPermission'])->name('users.update-withdrawal-permission');
    Route::post('/users/{user}/suspend-withdrawals', [UserController::class, 'suspendWithdrawals'])->name('users.suspend-withdrawals');
    Route::post('/users/{user}/activate-withdrawals', [UserController::class, 'activateWithdrawals'])->name('users.activate-withdrawals');

    // Real-Time Stock Management Routes
    Route::prefix('stocks-management')->name('stocks-management.')->group(function () {
        Route::get('/', [StockManagementController::class, 'index'])->name('index');
        Route::post('/import', [StockManagementController::class, 'importStock'])->name('import');
        Route::post('/bulk-import', [StockManagementController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/update-prices', [StockManagementController::class, 'updatePrices'])->name('update-prices');
        Route::get('/lists', [StockManagementController::class, 'getStockLists'])->name('lists');
        Route::get('/{symbol}/details', [StockManagementController::class, 'getStockDetails'])->name('details');
        Route::post('/{stock}/toggle-status', [StockManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{stock}', [StockManagementController::class, 'destroy'])->name('destroy');
    });

    // Stock Management
    // Automatic Stock Import Routes (must be before resource routes)
    Route::get('stocks/import/auto', [StockController::class, 'autoImport'])->name('stocks.auto-import');
    Route::post('stocks/import/single', [StockController::class, 'importStock'])->name('stocks.import-single');
    Route::post('stocks/import/bulk', [StockController::class, 'bulkImport'])->name('stocks.bulk-import');
    Route::post('stocks/import/demo', [StockController::class, 'addDemoStocks'])->name('stocks.add-demo');
    Route::post('stocks/update-all', [StockController::class, 'updateAllStocks'])->name('stocks.update-all');
    Route::post('stocks/{stock}/sync', [StockController::class, 'syncStock'])->name('stocks.sync');

    // Standard Stock CRUD Routes
    Route::resource('stocks', StockController::class);
    Route::patch('stocks/{stock}/toggle-status', [StockController::class, 'toggleStatus'])->name('stocks.toggle-status');

    // Order Management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

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
    Route::put('deposits/{deposit}/approve', [DepositController::class, 'approve'])->name('deposits.approve');
    Route::put('deposits/{deposit}/reject', [DepositController::class, 'reject'])->name('deposits.reject');

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

    Route::get('/kyc', [AdminKycController::class, 'index'])->name('admin.kyc.index');
    Route::get('/kyc/show/{user}', [AdminKycController::class, 'show'])->name('admin.kyc.show');
    Route::put('/kyc/approve/{user}', [AdminKycController::class, 'approve'])->name('admin.kyc.approve');
    Route::put('/kyc/reject/{user}', [AdminKycController::class, 'reject'])->name('admin.kyc.reject');

    Route::resource('stake-plans', StakePlanController::class)->names('admin.stake-plans');
    Route::get('stake-plans/create', [StakePlanController::class, 'create'])->name('stake-plans.create');
    Route::post('stake-plans', [StakePlanController::class, 'store'])->name('stake-plans.store');
    Route::get('stake-plans/{stakePlan}/edit', [StakePlanController::class, 'edit'])->name('stake-plans.edit');
    Route::put('stake-plans/{stakePlan}', [StakePlanController::class, 'update'])->name('stake-plans.update');
    Route::delete('stake-plans/{stakePlan}', [StakePlanController::class, 'destroy'])->name('stake-plans.destroy');


    Route::get('/user-stakes', [UserStakeController::class, 'index'])->name('user-stakes.index');
    Route::post('/user-stakes/approve/{id}', [UserStakeController::class, 'approve'])->name('user-stakes.approve');

    Route::post('/user-stakes/complete/{id}', [UserStakeController::class, 'complete'])
        ->name('user-stakes.complete');

    Route::post('/user-stakes/reject/{id}', [UserStakeController::class, 'reject'])->name('user-stakes.reject');

    Route::resource('airdrops', AirdropController::class)->except(['show']);
    Route::get('airdrops/claims', [AirdropClaimController::class, 'index'])->name('airdrops.claims.index');
    Route::post('airdrops/claims/{id}/approve', [AirdropClaimController::class, 'approve'])->name('airdrops.claims.approve');
    Route::post('airdrops/claims/{id}/reject', [AirdropClaimController::class, 'reject'])->name('airdrops.claims.reject');

    Route::resource('faucets', FaucetController::class)->except(['show']);
    Route::get('faucet-logs', [FaucetLogController::class, 'index'])->name('faucets.logs');

    Route::resource('/pools', PoolController::class)->except(['show']);
    Route::get('pools/machines', [PoolController::class, 'machines'])->name('pools.machines');
    Route::get('pools/rewards', [PoolController::class, 'rewards'])->name('pools.rewards');

    Route::get('/giftcards', [GiftCardController::class, 'index'])->name('giftcards.index');
    Route::get('/giftcards/transactions', [GiftCardController::class, 'transactions'])->name('giftcards.transactions');
    Route::delete('/giftcards/{giftcard}', [GiftCardController::class, 'destroy'])->name('giftcards.destroy');

    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods');
    Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/payment-methods/store', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/payment-methods/{paymentMethod}/update', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/payment-methods/{paymentMethod}/destroy', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

    Route::resource('trades', TradeController::class)
        ->except(['show']); // show can be added if needed

    Route::get('trades/{trade}', [TradeController::class, 'show'])->name('trades.show');

    Route::get('/change-password', [AdminProfileController::class, 'changePasswordForm'])
        ->name('admin.password.form');

    Route::post('/change-password', [AdminProfileController::class, 'updatePassword'])->name('password.update');


});
