@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')


<div class="mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
        <i class="fas fa-cog"></i> General Settings
    </h1>
    <p class="text-gray-600 mt-2">Manage your website's general configuration</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Settings Form -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Site Name -->
                <div class="mb-6">
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading"></i> Site Name
                    </label>
                    <input type="text" 
                           id="site_name" 
                           name="site_name" 
                           value="{{ old('site_name', \App\Models\Setting::get('site_name', 'Crypto Trading Platform')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('site_name') border-red-500 @enderror"
                           required>
                    @error('site_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading"></i> Site Address
                    </label>
                    <input type="text" 
                           id="site_address" 
                           name="site_address" 
                           value="{{ old('site_address', \App\Models\Setting::get('site_address', 'Crypto Trading Platform')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('site_address') border-red-500 @enderror"
                           required>
                    @error('site_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="site_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading"></i> Site Phone
                    </label>
                    <input type="text" 
                           id="site_phone" 
                           name="site_phone" 
                           value="{{ old('site_phone', \App\Models\Setting::get('site_phone', 'Crypto Trading Platform')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('site_phone') border-red-500 @enderror"
                           required>
                    @error('site_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site Logo -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image"></i> Site Logo
                    </label>
                    <div class="flex items-center space-x-4">
                        @if(\App\Models\Setting::get('site_logo'))
                            <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" class="h-16 w-16 object-contain border border-gray-300 rounded p-2">
                        @else
                            <div class="h-16 w-16 bg-gray-100 border border-gray-300 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" 
                                   id="site_logo" 
                                   name="site_logo" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('site_logo') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Recommended: PNG or SVG, max 2MB</p>
                            @error('site_logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Site Favicon -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-star"></i> Site Favicon
                    </label>
                    <div class="flex items-center space-x-4">
                        @if(\App\Models\Setting::get('site_favicon'))
                            <img src="{{ asset(\App\Models\Setting::get('site_favicon')) }}" alt="Favicon" class="h-8 w-8 object-contain border border-gray-300 rounded">
                        @else
                            <div class="h-8 w-8 bg-gray-100 border border-gray-300 rounded flex items-center justify-center">
                                <i class="fas fa-star text-gray-400 text-sm"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" 
                                   id="site_favicon" 
                                   name="site_favicon" 
                                   accept="image/x-icon,image/png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('site_favicon') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Recommended: ICO or PNG, 32x32 or 16x16 pixels</p>
                            @error('site_favicon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Site Description -->
                <div class="mb-6">
                    <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left"></i> Site Description
                    </label>
                    <textarea id="site_description" 
                              name="site_description" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('site_description') border-red-500 @enderror"
                              placeholder="Enter a brief description of your website...">{{ old('site_description', \App\Models\Setting::get('site_description', '')) }}</textarea>
                    @error('site_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Email -->
                <div class="mb-6">
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope"></i> Contact Email
                    </label>
                    <input type="email" 
                           id="contact_email" 
                           name="contact_email" 
                           value="{{ old('contact_email', \App\Models\Setting::get('contact_email', '')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 @error('contact_email') border-red-500 @enderror"
                           placeholder="contact@example.com">
                    @error('contact_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maintenance Mode -->
                <div class="mb-6">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="maintenance_mode" class="text-sm font-medium text-gray-700">
                                <i class="fas fa-tools"></i> Maintenance Mode
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Temporarily disable public access to the site</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   id="maintenance_mode" 
                                   name="maintenance_mode" 
                                   value="1"
                                   {{ old('maintenance_mode', \App\Models\Setting::get('maintenance_mode', false)) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>

                <hr class="my-6">

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition text-center">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Links Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Settings Navigation -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-sliders-h"></i> Settings
            </h3>
            <nav class="space-y-2">
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-indigo-50 text-indigo-700">
                    <i class="fas fa-cog mr-2"></i> General Settings
                </a>
                <a href="{{ route('admin.settings.oauth') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md">
                    <i class="fas fa-key mr-2"></i> OAuth Settings
                </a>
            </nav>
        </div>

        <!-- Help Card -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-question-circle"></i> Help
            </h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div>
                    <h4 class="font-semibold text-gray-900">Site Logo</h4>
                    <p>Upload a logo that represents your brand. It will appear in the navigation bar.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Favicon</h4>
                    <p>A small icon that appears in browser tabs and bookmarks.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900">Maintenance Mode</h4>
                    <p>When enabled, only administrators can access the site.</p>
                </div>
            </div>
        </div>

        <!-- Current Settings Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle"></i> Current Status
            </h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Site Name:</dt>
                    <dd class="font-medium">{{ \App\Models\Setting::get('site_name', 'Not Set') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Logo:</dt>
                    <dd class="font-medium">
                        @if(\App\Models\Setting::get('site_logo'))
                            <span class="text-green-600"><i class="fas fa-check"></i> Set</span>
                        @else
                            <span class="text-gray-400">Not Set</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Favicon:</dt>
                    <dd class="font-medium">
                        @if(\App\Models\Setting::get('site_favicon'))
                            <span class="text-green-600"><i class="fas fa-check"></i> Set</span>
                        @else
                            <span class="text-gray-400">Not Set</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Maintenance:</dt>
                    <dd class="font-medium">
                        @if(\App\Models\Setting::get('maintenance_mode', false))
                            <span class="text-red-600"><i class="fas fa-tools"></i> On</span>
                        @else
                            <span class="text-green-600"><i class="fas fa-check"></i> Off</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection