<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\TradingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(App\Http\Controllers\Frontend\FrontendController::class)->group(function () {
    Route::get('/','index');
    Route::get('/markets', 'markets');
    Route::get('/about', 'about');
    Route::get('/contact', 'contact');
    Route::post('/post-message', 'postMessage');
    Route::get('/offers', 'offer');
    Route::get('/worker', 'worker');
    Route::get('/hr-services', 'hrservice');
    Route::get('/jobs', 'jobs');
    Route::get('/view/{slug}', 'jobView');
    Route::get('/application/{slug}', 'jobApplication');
    Route::post('/application', 'storeApplication');

    // Route::get('/categories', 'product');
    // Route::get('/product-view/{category_slug}/{product_slug}', 'productView');
    // Route::get('/place-order/{category_slug}/{product_slug}', 'placeOrder');
    // Route::post('/place-order', 'storeOrder');

    Route::get('/search', 'searchProducts');
    
});

Route::get('/trade/{symbol}', [TradingController::class, 'show'])->name('trade.pair');


Route::controller(TradingController::class)->group(function () {
    Route::get('/trading/pro', [TradingController::class, 'pro'])->name('pro');

    Route::get('/trading/spot', [TradingController::class, 'spot'])->name('spot');

    Route::get('/easy-convert', [TradingController::class, 'easy'])->name('easy-convert');
    
    
});

Route::get('/trading/{pairId}', [TradingController::class, 'show'])->name('show');
// Trading
    // Route::controller('trading')->name('trading.')->group(function () {
        
    //     Route::get('/{pairId}', [TradingController::class, 'show'])->name('show');
        
    //     Route::post('/order', [TradingController::class, 'placeOrder'])->name('order.place');
    //     Route::delete('/order/{order}', [TradingController::class, 'cancelOrder'])->name('order.cancel');
    //     Route::get('/history/orders', [TradingController::class, 'orderHistory'])->name('orders');
    //     Route::get('/history/trades', [TradingController::class, 'tradeHistory'])->name('trades');
    // });



Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Google OAuth Routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Email Verification Routes (accessible to both authenticated and unauthenticated users)
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');

// Email Verification Routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify-notice', [EmailVerificationController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

    
// Authenticated routes
Route::middleware(['auth', 'verify.email'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [WalletController::class, 'index'])->name('dashboard');
    // Route::get('/markets', [DashboardController::class, 'markets'])->name('markets');
    
    
    
    // Wallet
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/deposit', [WalletController::class, 'showDeposit'])->name('deposit'); // General deposit page
        Route::get('/deposit/{cryptoId}', [WalletController::class, 'showDeposit'])->name('deposit.specific'); // Specific crypto deposit
        Route::post('/deposit/submit/{cryptoId}', [WalletController::class, 'submitDeposit'])->name('wallet.deposit.submit');
        Route::get('/withdraw', [WalletController::class, 'showWithdraw'])->name('withdraw'); // General withdraw page
        Route::get('/withdraw/{cryptoId}', [WalletController::class, 'showWithdraw'])->name('withdraw.specific'); // Specific crypto withdraw
        Route::post('/withdraw', [WalletController::class, 'processWithdrawal'])->name('withdraw.process');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::get('/transaction/{id}', [WalletController::class, 'transactionDetail'])->name('wallet.transaction.detail');


        // Manual Deposit Management
        Route::get('/deposits', [WalletController::class, 'deposits'])->name('deposits');
        Route::get('/deposits/manual', function() {
            $cryptocurrencies = \App\Models\Cryptocurrency::active()->get();
            return view('wallet.manual-deposit', compact('cryptocurrencies'));
        })->name('deposits.manual');
        Route::post('/deposits/manual', [WalletController::class, 'processManualDeposit'])->name('deposits.manual');
        Route::get('/deposits/{deposit}', [WalletController::class, 'showDeposit'])->name('deposit');
        Route::post('/deposits/{deposit}/payment-proof', [WalletController::class, 'uploadPaymentProof'])->name('deposit.payment-proof');
        
        // Login History
        Route::get('/login-history', function() {
            $loginHistories = Auth::user()->loginHistories()->paginate(20);
            return view('wallet.login-history', compact('loginHistories'));
        })->name('login-history');
    });

    
});

// API Routes for real-time data
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/prices', function () {
        $cryptocurrencies = \App\Models\Cryptocurrency::active()->get(['id', 'symbol', 'current_price', 'price_change_24h']);
        return response()->json($cryptocurrencies);
    });
    
    Route::get('/trading-pair/{pair}/orderbook', function ($pairId) {
        $buyOrders = \App\Models\Order::where('trading_pair_id', $pairId)
            ->where('side', 'buy')
            ->where('type', 'limit')
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('price', 'desc')
            ->take(20)
            ->get();
        
        $sellOrders = \App\Models\Order::where('trading_pair_id', $pairId)
            ->where('side', 'sell')
            ->where('type', 'limit')
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('price', 'asc')
            ->take(20)
            ->get();
        
        return response()->json([
            'buy_orders' => $buyOrders,
            'sell_orders' => $sellOrders,
        ]);
    });
});









