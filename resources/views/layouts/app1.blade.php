<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Crypto Trading Platform')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .crypto-card { transition: transform 0.3s, box-shadow 0.3s; }
        .crypto-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-2xl font-bold gradient-bg bg-clip-text text-transparent">
                            <i class="fas fa-chart-line"></i> CryptoTrade
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                        <a href="{{ url('markets') }}" class="@if(request()->routeIs('markets')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-bar mr-2"></i> Markets
                        </a>
                        <a href="{{ route('wallet.index') }}" class="@if(request()->routeIs('wallet.*')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-wallet mr-2"></i> Wallet
                        </a>
                        <a href="{{ route('trading.orders') }}" class="@if(request()->routeIs('trading.orders')) border-indigo-500 text-gray-900 @else border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 @endif inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-list mr-2"></i> Orders
                        </a>
                        @auth
                            @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="border-transparent text-red-600 hover:border-red-300 hover:text-red-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                <i class="fas fa-crown mr-2"></i> Admin
                            </a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="flex items-center">
                    @auth
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
                                        <i class="fas fa-user-circle mr-2"></i> {{ auth()->user()->name }}
                                    </span>
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="ml-4 px-4 py-2 rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <i class="fas fa-times"></i>
            </span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <i class="fas fa-times"></i>
            </span>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400">&copy; 2025 Crypto Trading Platform. All rights reserved.</p>
                <p class="text-sm text-gray-500 mt-2">Real-time cryptocurrency trading with blockchain integration</p>
            </div>
        </div>
    </footer>

    <script>
        // Setup CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Price update function
        function updatePrices() {
            fetch('/api/prices')
                .then(response => response.json())
                .then(data => {
                    data.forEach(crypto => {
                        const priceElement = document.querySelector(`[data-price-${crypto.id}]`);
                        if (priceElement) {
                            priceElement.textContent = `$${parseFloat(crypto.current_price).toFixed(2)}`;
                        }
                        
                        const changeElement = document.querySelector(`[data-change-${crypto.id}]`);
                        if (changeElement) {
                            const change = parseFloat(crypto.price_change_24h).toFixed(2);
                            changeElement.textContent = `${change}%`;
                            changeElement.className = change >= 0 ? 'text-green-600' : 'text-red-600';
                        }
                    });
                })
                .catch(error => console.error('Error updating prices:', error));
        }
        
        // Update prices every 30 seconds
        setInterval(updatePrices, 30000);
    </script>

    @stack('scripts')
</body>
</html>
