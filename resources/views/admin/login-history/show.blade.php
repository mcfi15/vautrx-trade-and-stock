@extends('admin.layouts.app')

@section('title', 'Login History Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('admin.login-history.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Login History</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Login History Details
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Detailed information about this login attempt
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.login-history.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 sm:px-6 {{ $loginHistory->success ? 'bg-green-50' : 'bg-red-50' }}">
                <div class="flex items-center">
                    @if($loginHistory->success)
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-green-800">
                                Successful Login
                            </h3>
                            <p class="mt-1 text-sm text-green-700">
                                User successfully authenticated and gained access
                            </p>
                        </div>
                    @else
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg leading-6 font-medium text-red-800">
                                Failed Login Attempt
                            </h3>
                            <p class="mt-1 text-sm text-red-700">
                                {{ $loginHistory->failure_reason ?? 'Login failed for security reasons' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="border-t border-gray-200">
                <dl>
                    <!-- User Information -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="font-medium">{{ $loginHistory->user->name }}</div>
                            <div class="text-gray-500">{{ $loginHistory->user->email }}</div>
                        </dd>
                    </div>

                    <!-- Timestamp -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Timestamp</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="font-medium">{{ $loginHistory->login_at->format('F d, Y \a\t H:i:s T') }}</div>
                            <div class="text-gray-500">{{ $loginHistory->login_at->diffForHumans() }}</div>
                        </dd>
                    </div>

                    <!-- IP Address -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="font-mono">{{ $loginHistory->ip_address }}</div>
                            @if($loginHistory->isp)
                                <div class="text-gray-500">ISP: {{ $loginHistory->isp }}</div>
                            @endif
                        </dd>
                    </div>

                    <!-- Location -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="font-medium">{{ $loginHistory->formatted_location }}</div>
                            @if($loginHistory->latitude && $loginHistory->longitude)
                                <div class="text-gray-500">
                                    Coordinates: {{ $loginHistory->latitude }}, {{ $loginHistory->longitude }}
                                </div>
                            @endif
                        </dd>
                    </div>

                    <!-- Device Information -->
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Device Information</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="space-y-1">
                                @if($loginHistory->device)
                                    <div><strong>Device:</strong> {{ $loginHistory->device }}</div>
                                @endif
                                @if($loginHistory->browser)
                                    <div><strong>Browser:</strong> {{ $loginHistory->browser }}</div>
                                @endif
                                @if($loginHistory->platform)
                                    <div><strong>Platform:</strong> {{ $loginHistory->platform }}</div>
                                @endif
                            </div>
                        </dd>
                    </div>

                    <!-- User Agent -->
                    @if($loginHistory->user_agent)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="font-mono text-xs break-all">{{ $loginHistory->user_agent }}</div>
                        </dd>
                    </div>
                    @endif

                    @if(!$loginHistory->success)
                    <!-- Failure Reason -->
                    <div class="bg-red-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-red-700">Failure Reason</dt>
                        <dd class="mt-1 text-sm text-red-900 sm:mt-0 sm:col-span-2">
                            {{ $loginHistory->failure_reason ?? 'Security validation failed' }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection