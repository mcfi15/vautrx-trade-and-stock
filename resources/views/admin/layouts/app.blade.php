<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Crypto Trading Platform') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('site_favicon', '/favicon.ico')) }}">
    <style>
        /* Mobile-friendly improvements */
        @media (max-width: 768px) {
            .mobile-menu { display: none; }
            .mobile-menu.active { display: block; }
        }
        
        /* Smooth transitions */
        .transition-all { transition: all 0.3s ease; }
        
        /* Dropdown hover fix for mobile */
        @media (hover: none) {
            .group:active .group-hover\:block { display: block !important; }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        @if(\App\Models\Setting::get('site_logo'))
                            <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" class="h-8 w-8 object-contain">
                        @else
                            <i class="fas fa-crown text-2xl"></i>
                        @endif
                        <span class="font-bold text-lg sm:text-xl hidden sm:inline">{{ \App\Models\Setting::get('site_name', 'Admin Panel') }}</span>
                        <span class="font-bold text-lg sm:text-xl sm:hidden">Admin</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-2 lg:space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:bg-indigo-700 px-2 lg:px-3 py-2 rounded-md text-sm transition-all">
                        <i class="fas fa-tachometer-alt mr-1"></i><span class="hidden lg:inline">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.cryptocurrencies.index') }}" class="hover:bg-indigo-700 px-2 lg:px-3 py-2 rounded-md text-sm transition-all">
                        <i class="fas fa-coins mr-1"></i><span class="hidden lg:inline">Crypto</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="hover:bg-indigo-700 px-2 lg:px-3 py-2 rounded-md text-sm transition-all">
                        <i class="fas fa-users mr-1"></i><span class="hidden lg:inline">Users</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="hover:bg-indigo-700 px-2 lg:px-3 py-2 rounded-md text-sm transition-all">
                        <i class="fas fa-list mr-1"></i><span class="hidden lg:inline">Orders</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="hover:bg-indigo-700 px-2 lg:px-3 py-2 rounded-md text-sm transition-all">
                        <i class="fas fa-cog mr-1"></i><span class="hidden lg:inline">Settings</span>
                    </a>
                    
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="hover:bg-indigo-700 px-2 lg:px-3 py-2 rounded-md flex items-center text-sm transition-all">
                            <i class="fas fa-user-shield mr-1"></i>
                            <span class="hidden xl:inline">{{ Auth::guard('admin')->user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-home mr-2"></i>User Dashboard
                            </a>
                            <a href="{{ url('admin/login-history') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-chart-line mr-2"></i>Login Activities
                            </a>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button onclick="toggleMobileMenu()" class="hover:bg-indigo-700 px-3 py-2 rounded-md">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="mobile-menu md:hidden bg-indigo-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="block hover:bg-indigo-800 px-3 py-2 rounded-md">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.cryptocurrencies.index') }}" class="block hover:bg-indigo-800 px-3 py-2 rounded-md">
                    <i class="fas fa-coins mr-2"></i>Cryptocurrencies
                </a>
                <a href="{{ route('admin.users.index') }}" class="block hover:bg-indigo-800 px-3 py-2 rounded-md">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
                <a href="{{ route('admin.orders.index') }}" class="block hover:bg-indigo-800 px-3 py-2 rounded-md">
                    <i class="fas fa-list mr-2"></i>Orders
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block hover:bg-indigo-800 px-3 py-2 rounded-md">
                    <i class="fas fa-cog mr-2"></i>Settings
                </a>
                <hr class="my-2 border-indigo-600">
                <a href="{{ route('dashboard') }}" class="block hover:bg-indigo-800 px-3 py-2 rounded-md">
                    <i class="fas fa-home mr-2"></i>User Dashboard
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left block hover:bg-indigo-800 px-3 py-2 rounded-md">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-check-circle mt-1 mr-2"></i>
                    <div>
                        <p class="font-bold">Success!</p>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
                    <div>
                        <p class="font-bold">Error!</p>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mt-1 mr-2"></i>
                    <div>
                        <p class="font-bold">There were some errors:</p>
                        <ul class="list-disc list-inside mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow mt-8">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-xs sm:text-sm">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Crypto Trading Platform') }} - Admin Panel
            </p>
        </div>
    </footer>

    <script>
        // Toggle mobile menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('active');
        }

        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button');
            
            if (!menu.contains(event.target) && !button && menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
        });
    </script>
    
    @yield('scripts')
    
</body>
</html>