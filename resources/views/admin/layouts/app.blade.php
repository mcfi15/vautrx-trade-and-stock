<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Crypto Trading Platform') }}</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('site_favicon', '/favicon.ico')) }}">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- DESKTOP TOP NAVBAR WITH ADMIN DROPDOWN -->
    <div class="hidden md:flex items-center justify-between bg-white border-b px-6 py-3 shadow-sm">

        <!-- Left: App Name -->
        <div class="flex items-center space-x-2">
            <span class="font-semibold text-lg text-gray-700">
                {{ \App\Models\Setting::get('site_name', 'Admin Panel') }}
            </span>
        </div>

        <!-- Right: Admin Dropdown -->
        <div class="relative group">
            <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                <i class="fas fa-user-shield text-lg"></i>
                <span>{{ Auth::guard('admin')->user()->name }}</span>
                <i class="fas fa-chevron-down text-sm"></i>
            </button>

            <div class="absolute right-0 top-full w-48 bg-white text-gray-800 border rounded shadow-lg hidden group-hover:block z-50">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">User Dashboard</a>
                <a href="{{ url('admin/login-history') }}" class="block px-4 py-2 hover:bg-gray-100">Login Activities</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>

    </div>


    <!-- MOBILE NAVBAR -->
    <div class="md:hidden flex items-center justify-between bg-indigo-600 text-white px-4 py-3">
        <button id="openSidebar" class="text-2xl">
            <i class="fas fa-bars"></i>
        </button>

        <span class="font-semibold text-lg">{{ \App\Models\Setting::get('site_name', 'Admin Panel') }}</span>

        <!-- Mini mobile admin dropdown -->
        <div class="relative group">
            <button class="text-xl">
                <i class="fas fa-user-circle"></i>
            </button>
            <div class="absolute right-0 top-full w-48 bg-white text-gray-800 rounded shadow-lg hidden group-hover:block z-50">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">User Dashboard</a>
                <a href="{{ url('admin/login-history') }}" class="block px-4 py-2 hover:bg-gray-100">Login Activities</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed md:fixed inset-y-0 left-0 transform -translate-x-full md:translate-x-0
           transition-transform duration-300 z-50 w-64 h-screen bg-indigo-600 text-white flex flex-col">

        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-indigo-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                @if(\App\Models\Setting::get('site_logo'))
                    <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" class="h-8 w-8 object-contain">
                @else
                    <i class="fas fa-crown text-2xl"></i>
                @endif
                <span class="font-bold text-lg">{{ \App\Models\Setting::get('site_name', 'Admin Panel') }}</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto mt-4">
            <ul class="space-y-1">
                <li><a href="{{ route('admin.dashboard') }}" class="nav-item"><i class="fas fa-tachometer-alt w-5"></i><span class="ml-3">Dashboard</span></a></li>
                <li><a href="{{ route('admin.cryptocurrencies.index') }}" class="nav-item"><i class="fas fa-coins w-5"></i><span class="ml-3">Crypto</span></a></li>
                <li><a href="{{ url('admin/stocks') }}" class="nav-item"><i class="fas fa-chart-line w-5"></i><span class="ml-3">Stock</span></a></li>
                <li><a href="{{ route('admin.users.index') }}" class="nav-item"><i class="fas fa-users w-5"></i><span class="ml-3">Users</span></a></li>
                <li><a href="{{ url('admin/kyc') }}" class="nav-item"><i class="fas fa-id-card w-5"></i><span class="ml-3">KYC</span></a></li>
                <li><a href="{{ route('admin.orders.index') }}" class="nav-item"><i class="fas fa-list w-5"></i><span class="ml-3">Orders</span></a></li>
                <li><a href="{{ route('admin.deposits.index') }}" class="nav-item"><i class="fas fa-arrow-down w-5"></i><span class="ml-3">Deposits</span></a></li>
                <li><a href="{{ route('admin.withdrawals.index') }}" class="nav-item"><i class="fas fa-arrow-up w-5"></i><span class="ml-3">Withdrawals</span></a></li>
                <li><a href="{{ route('admin.settings.index') }}" class="nav-item"><i class="fas fa-cog w-5"></i><span class="ml-3">Settings</span></a></li>
            </ul>
        </nav>

    </aside>

    <!-- Main content -->
    <main class="flex-1 p-4 md:ml-64 mt-16 md:mt-0">
        @yield('content')
    </main>

    <!-- Overlay for mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 hidden md:hidden z-40"></div>

    <style>
        .nav-item { @apply flex items-center px-4 py-3 hover:bg-indigo-700 transition rounded; }
    </style>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openSidebarBtn = document.getElementById('openSidebar');

        openSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

</body>
</html>
