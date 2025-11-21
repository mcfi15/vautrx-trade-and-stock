<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\PoolController;
use App\Http\Controllers\User\StakeController;
use App\Http\Controllers\User\StockController;
use App\Http\Controllers\User\FaucetController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\AirdropController;
use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\User\StakingController;
use App\Http\Controllers\User\TradingController;
use App\Http\Controllers\User\GiftCardController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\EasyTradeController;
use App\Http\Controllers\User\PortfolioController;
use App\Http\Controllers\User\WatchlistController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\User\MiningPoolController;
use App\Http\Controllers\User\UserAirdropController;
use App\Http\Controllers\User\StockTradingController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;


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

Route::controller(App\Http\Controllers\Frontend\FrontendController::class)->group(function () {
    Route::get('/','index');
    Route::post('/do-trade', 'doTrade')->middleware(['auth', 'verify.email'])->name('do-trade');
    Route::get('/markets', 'markets');
    Route::get('/about', 'about');
    Route::get('/contact', 'contact');
    Route::get('/stock-market', 'stock');


    Route::get('/article/1', 'article_1');
    Route::get('/article/2', 'article_2');
    Route::get('/article/3', 'article_3');
    Route::get('/article/4', 'article_4');
    Route::get('/article/10', 'article_10');
    Route::get('/article/11', 'article_11');
    Route::get('/article/13', 'article_13');
    Route::get('/article/24', 'article_24');
    Route::get('/article/25', 'article_25');
    Route::get('/article/26', 'article_26');
    Route::get('/article/27', 'article_27');
    Route::get('/dex', 'dex');
    
});

Route::get('/trade/spot', function () {
    $defaultPair = \App\Models\TradingPair::active()->first();
    if ($defaultPair) {
        return redirect()->route('trade.pair', $defaultPair->id);
    }
    return view('trading.spot');
});

Route::get('/trade/{pairId}', [TradingController::class, 'show'])->name('trade.pair');

Route::get('/easy-trade', [EasyTradeController::class, 'index'])->name('easytrade.index');

Route::get('/staking', [StakingController::class, 'staking'])->name('staking');

Route::post('/staking/invest', [StakingController::class, 'invest'])
    ->middleware(['auth', 'verify.email'])->name('staking.invest');

Route::get('/airdrops', [UserAirdropController::class, 'index'])->name('airdrops.index');
Route::get('/airdrops/{airdrop}', [UserAirdropController::class, 'show'])->name('airdrops.show');
Route::post('/airdrops/{airdrop}/claim', [UserAirdropController::class, 'claim'])->name('airdrops.claim')->middleware(['auth', 'verify.email']);

Route::get('/faucets', [FaucetController::class, 'index'])->name('faucets.index');
Route::post('/faucets/claim/{id}', [FaucetController::class, 'claim'])->name('faucets.claim')->middleware(['auth', 'verify.email']);

Route::prefix('pool')->name('pool.')->group(function () {
    Route::get('/', [PoolController::class, 'index'])->name('index');
    Route::get('/myMachines', [PoolController::class, 'myMachines'])->name('myMachines');
    Route::get('/myRewards', [PoolController::class, 'myRewards'])->name('myRewards');
    Route::post('/rent/{pool}', [PoolController::class, 'rent'])->middleware(['auth', 'verify.email'])->name('rent');
    Route::post('/claim-reward/{reward}', [PoolController::class, 'claimReward'])->middleware(['auth', 'verify.email'])->name('claimReward');
});


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

    

    Route::post('/easytrade/doTrade', [EasyTradeController::class, 'doTrade'])->name('easytrade.doTrade');

    Route::post('/trade/place-order', [TradingController::class, 'placeOrder'])->name('trade.place-order');
    Route::post('/trade/orders/{orderId}/cancel', [TradingController::class, 'cancelOrder'])->name('trade.cancel-order');
    
    // Dashboard
    Route::get('/dashboard', [WalletController::class, 'index'])->name('dashboard');
    // Route::get('/markets', [DashboardController::class, 'markets'])->name('markets');
    
    
    // Stocks
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        // Route::get('/mobile', [StockController::class, 'mobileIndex'])->name('mobile-index');
        Route::get('/{stock}', [StockController::class, 'show'])->name('show');
        Route::get('/{stock}/chart-data', [StockController::class, 'chartData'])->name('chart-data');
        Route::post('/{stock}/watchlist', [StockController::class, 'addToWatchlist'])->name('add-to-watchlist');
        Route::delete('/{stock}/watchlist', [StockController::class, 'removeFromWatchlist'])->name('remove-from-watchlist');
    });
    
    // Trading
    Route::prefix('trading')->name('trading.')->middleware(['trading.enabled'])->group(function () {
        Route::get('/', [StockTradingController::class, 'index'])->name('index');
        Route::post('/buy', [StockTradingController::class, 'buy'])->name('buy');
        Route::post('/sell', [StockTradingController::class, 'sell'])->name('sell');
    });

    // Watchlist
    Route::prefix('watchlist')->name('watchlist.')->group(function () {
        Route::get('/', [WatchlistController::class, 'index'])->name('index');
        Route::patch('/{watchlist}', [WatchlistController::class, 'update'])->name('update');
        Route::delete('/{watchlist}', [WatchlistController::class, 'destroy'])->name('destroy');
    });
    
    // Portfolio
    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/{portfolio}', [PortfolioController::class, 'show'])->name('show');
    });

    Route::prefix('giftcard')->name('giftcard.')->group(function () {
        Route::get('/', [GiftCardController::class, 'index'])->name('index');
        Route::get('/create', [GiftCardController::class, 'create'])->name('create');
        Route::post('/store', [GiftCardController::class, 'store'])->name('store');
        Route::post('/check-value', [GiftCardController::class, 'checkValue'])->name('checkValue');
        Route::post('/redeem', [GiftCardController::class, 'redeem'])->name('redeem');
        Route::get('/view-code/{id}', [GiftCardController::class, 'viewCode'])->name('viewCode');
        Route::get('/view-consumed/{id}', [GiftCardController::class, 'viewConsumed'])->name('viewConsumed');
    });
    
    // Wallet
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/deposit', [WalletController::class, 'showDeposit'])->name('deposit'); // General deposit page
        Route::get('/deposit/{cryptoId}', [WalletController::class, 'showDeposit'])->name('deposit.specific'); // Specific crypto deposit
        Route::post('/deposit/{cryptoId}/submit', [WalletController::class, 'submitDeposit'])->name('deposit.submit');
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
        // Route::get('/login-history', function() {
        //     $loginHistories = Auth::user()->loginHistories()->paginate(20);
        //     return view('wallet.login-history', compact('loginHistories'));
        // })->name('login-history');

    });


    Route::get('/user-center', [SettingController::class, 'index'])->name('user-center');
    Route::get('/kyc', [SettingController::class, 'kyc'])->name('kyc');
    Route::post('/kyc', [SettingController::class, 'submit'])->name('kyc.submit');
    Route::get('/fees', [SettingController::class, 'fees'])->name('fees');
    Route::get('/login-history', [SettingController::class, 'log'])->name('login-history');
    Route::get('/security', [SettingController::class, 'security'])->name('security');
    Route::get('/change-password', [SettingController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [SettingController::class, 'changePasswordUpdate'])->name('user.password.update');

    
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









