@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            <i class="fas fa-users"></i> Users Management
        </h1>
        <div class="flex items-center space-x-2">
            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-semibold">
                Total: {{ $users->total() }}
            </span>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white shadow rounded-lg p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Name or email..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Auth Provider Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Auth Provider</label>
            <select name="auth_provider" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All</option>
                <option value="email" {{ request('auth_provider') === 'email' ? 'selected' : '' }}>Email</option>
                <option value="google" {{ request('auth_provider') === 'google' ? 'selected' : '' }}>Google</option>
            </select>
        </div>

        <!-- Withdrawal Permission Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Withdrawal Permission</label>
            <select name="withdrawal_permission" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All</option>
                <option value="active" {{ request('withdrawal_permission') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('withdrawal_permission') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="exceed_limit" {{ request('withdrawal_permission') === 'exceed_limit' ? 'selected' : '' }}>Exceed Limit</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Users Table -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Withdrawal</th>
                    <th class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="h-8 w-8 rounded-full mr-2">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-2">
                                        <span class="text-indigo-600 font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 md:hidden">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="hidden lg:table-cell px-4 py-4">
                            @if($user->auth_provider === 'google')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fab fa-google mr-1"></i> Google
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-envelope mr-1"></i> Email
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->withdrawal_permission_badge }}">
                                <i class="{{ $user->withdrawal_permission_icon }} mr-1"></i> 
                                {{ $user->withdrawal_permission_label }}
                            </span>
                        </td>
                        <td class="hidden sm:table-cell px-4 py-4 text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-4 text-right text-sm font-medium space-x-2">
                            <!-- View -->
                            <a href="{{ route('admin.users.show', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View User">
                                <i class="fas fa-eye"></i><span class="hidden sm:inline ml-1">View</span>
                            </a>
                            
                            <!-- Toggle Status -->
                            <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $user->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $user->is_active ? 'Deactivate User' : 'Activate User' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                    <span class="hidden sm:inline ml-1">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</span>
                                </button>
                            </form>
                            
                            <!-- Withdrawal Permission Dropdown -->
                            <div class="inline-block relative">
                                <button class="text-gray-600 hover:text-gray-900 focus:outline-none" id="withdrawal-menu-{{ $user->id }}">
                                    <i class="fas fa-cog"></i>
                                    <span class="hidden sm:inline ml-1">Withdrawal</span>
                                </button>
                                <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10" id="withdrawal-dropdown-{{ $user->id }}">
                                    <form action="{{ route('admin.users.update-withdrawal-permission', $user->id) }}" method="POST" class="px-2 py-1">
                                        @csrf
                                        <button type="submit" name="withdrawal_permission" value="active" 
                                                class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50 {{ $user->withdrawal_permission === 'active' ? 'bg-green-50' : '' }}">
                                            <i class="fas fa-check-circle mr-2"></i>Set Active
                                        </button>
                                        <button type="submit" name="withdrawal_permission" value="suspended" 
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 {{ $user->withdrawal_permission === 'suspended' ? 'bg-red-50' : '' }}">
                                            <i class="fas fa-ban mr-2"></i>Suspend
                                        </button>
                                        <button type="submit" name="withdrawal_permission" value="exceed_limit" 
                                                class="block w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 {{ $user->withdrawal_permission === 'exceed_limit' ? 'bg-yellow-50' : '' }}">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>Exceed Limit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-2"></i>
                            <p>No users found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- JavaScript for Dropdown -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle withdrawal permission dropdown
    document.querySelectorAll('[id^="withdrawal-menu-"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const userId = this.id.replace('withdrawal-menu-', '');
            const dropdown = document.getElementById('withdrawal-dropdown-' + userId);
            
            // Close all other dropdowns
            document.querySelectorAll('[id^="withdrawal-dropdown-"]').forEach(d => {
                if (d !== dropdown) d.classList.add('hidden');
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('[id^="withdrawal-dropdown-"]').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    });
});
</script>
@endsection