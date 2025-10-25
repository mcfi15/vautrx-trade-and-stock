<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\TradingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Auth\GoogleAuthController;


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


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Google OAuth Routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [WalletController::class, 'index'])->name('dashboard');
    // Route::get('/markets', [DashboardController::class, 'markets'])->name('markets');
    
    // Trading
    Route::prefix('trading')->name('trading.')->group(function () {
        Route::get('/{pair}', [TradingController::class, 'show'])->name('show');
        Route::post('/order', [TradingController::class, 'placeOrder'])->name('order.place');
        Route::delete('/order/{order}', [TradingController::class, 'cancelOrder'])->name('order.cancel');
        Route::get('/history/orders', [TradingController::class, 'orderHistory'])->name('orders');
        Route::get('/history/trades', [TradingController::class, 'tradeHistory'])->name('trades');
    });
    
    // Wallet
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/deposit', [WalletController::class, 'showDeposit'])->name('deposit'); // General deposit page
        Route::get('/deposit/{cryptoId}', [WalletController::class, 'showDeposit'])->name('deposit.specific'); // Specific crypto deposit
        Route::get('/withdraw', [WalletController::class, 'showWithdraw'])->name('withdraw'); // General withdraw page
        Route::get('/withdraw/{cryptoId}', [WalletController::class, 'showWithdraw'])->name('withdraw.specific'); // Specific crypto withdraw
        Route::post('/withdraw', [WalletController::class, 'processWithdrawal'])->name('withdraw.process');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
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
