@extends('admin.layouts.app')

@section('title', 'OAuth Settings')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-key text-indigo-600"></i> OAuth Settings
        </h1>
        <a href="{{ route('admin.settings.index') }}" class="mt-3 sm:mt-0 inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Settings
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded flex items-start">
            <i class="fas fa-check-circle mt-1 mr-2"></i>
            <div>
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded flex items-start">
            <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
            <div>
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle mt-1 mr-2"></i>
                <div>
                    <p class="font-bold">Validation Error:</p>
                    <ul class="list-disc list-inside mt-2 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-indigo-700 flex items-center gap-2 mb-4">
                    <i class="fab fa-google"></i> Google OAuth Configuration
                </h2>

                <form action="{{ route('admin.settings.oauth.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Enable / Disable -->
                    <div class="flex items-center justify-between">
                        <label for="google_oauth_enabled" class="font-semibold text-gray-800">Enable Google OAuth</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="google_oauth_enabled" name="google_oauth_enabled" value="1"
                                class="sr-only peer"
                                {{ old('google_oauth_enabled', $oauthSettings->where('key', 'google_oauth_enabled')->first()->value ?? false) ? 'checked' : '' }}
                                onchange="toggleGoogleOAuthFields()">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    <p class="text-sm text-gray-500">Allow users to log in using their Google account.</p>

                    <hr class="border-gray-200">

                    <div id="google-oauth-fields" class="space-y-4">
                        <!-- Client ID -->
                        <div>
                            <label for="google_client_id" class="block font-medium text-gray-700">Google Client ID <span class="text-red-500" id="client-id-required">*</span></label>
                            <input type="text" id="google_client_id" name="google_client_id"
                                value="{{ old('google_client_id', $oauthSettings->where('key', 'google_client_id')->first()->value ?? '') }}"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter your Google OAuth Client ID">
                            <p class="text-sm text-gray-500 mt-1">
                                Get this from <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="text-indigo-600 underline">Google Cloud Console</a>.
                            </p>
                        </div>

                        <!-- Client Secret -->
                        <div>
                            <label for="google_client_secret" class="block font-medium text-gray-700">Google Client Secret <span class="text-red-500" id="client-secret-required">*</span></label>
                            <div class="relative">
                                <input type="password" id="google_client_secret" name="google_client_secret"
                                    value="{{ old('google_client_secret', $oauthSettings->where('key', 'google_client_secret')->first()->value ?? '') }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 pr-10"
                                    placeholder="Enter your Google OAuth Client Secret">
                                <button type="button" onclick="togglePassword('google_client_secret')" class="absolute right-2 top-2.5 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye" id="toggle-icon-google_client_secret"></i>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Keep this secret safe and do not share publicly.</p>
                        </div>

                        <!-- Redirect URI -->
                        <div>
                            <label for="google_redirect_uri" class="block font-medium text-gray-700">Google Redirect URI <span class="text-red-500" id="redirect-uri-required">*</span></label>
                            <input type="url" id="google_redirect_uri" name="google_redirect_uri"
                                value="{{ old('google_redirect_uri', $oauthSettings->where('key', 'google_redirect_uri')->first()->value ?? url('/auth/google/callback')) }}"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="https://yourdomain.com/auth/google/callback">
                            <p class="text-sm text-gray-500 mt-1">Default: <code class="bg-gray-100 px-1 rounded">{{ url('/auth/google/callback') }}</code></p>
                        </div>

                        <!-- Instructions -->
                        <div class="bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-lg p-4 mt-4">
                            <p class="font-semibold flex items-center gap-2"><i class="fas fa-info-circle"></i> Configuration Steps:</p>
                            <ol class="list-decimal list-inside mt-2 text-sm space-y-1">
                                <li>Go to <a href="https://console.cloud.google.com/" class="text-indigo-600 underline" target="_blank">Google Cloud Console</a></li>
                                <li>Create or select a project</li>
                                <li>Enable the Google+ API</li>
                                <li>Go to Credentials → Create OAuth 2.0 Client ID</li>
                                <li>Set type to “Web application”</li>
                                <li>Add redirect URI: <code>{{ url('/auth/google/callback') }}</code></li>
                                <li>Copy your Client ID and Secret here</li>
                            </ol>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6">
                        <button type="button" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded shadow-sm text-sm flex items-center gap-2" onclick="testConnection()" id="test-btn">
                            <i class="fas fa-vial"></i> Test Connection
                        </button>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.settings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm flex items-center gap-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Help & Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-indigo-700 flex items-center gap-2">
                    <i class="fas fa-question-circle"></i> Help & Information
                </h2>

                <div class="mt-4 text-sm text-gray-700 space-y-4">
                    <div>
                        <h6 class="font-semibold text-indigo-600">What is OAuth?</h6>
                        <p class="text-gray-600">OAuth lets users log in securely using Google accounts without new passwords.</p>
                    </div>

                    <div class="border-t border-gray-200 pt-3">
                        <h6 class="font-semibold text-indigo-600">Current Status</h6>
                        <ul class="text-sm text-gray-700 space-y-2 mt-2">
                            <li><strong>Google OAuth:</strong>
                                @if($oauthSettings->where('key', 'google_oauth_enabled')->first()->value ?? false)
                                    <span class="text-green-600 font-semibold">Enabled</span>
                                @else
                                    <span class="text-gray-500 font-semibold">Disabled</span>
                                @endif
                            </li>
                            <li><strong>Configured:</strong>
                                @php
                                    $clientId = $oauthSettings->where('key', 'google_client_id')->first()->value ?? '';
                                    $clientSecret = $oauthSettings->where('key', 'google_client_secret')->first()->value ?? '';
                                    $configured = !empty($clientId) && !empty($clientSecret);
                                @endphp
                                @if($configured)
                                    <span class="text-green-600 font-semibold">Yes</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">Not Configured</span>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <div class="border-t border-gray-200 pt-3">
                        <h6 class="font-semibold text-indigo-600">Security Tips</h6>
                        <ul class="list-disc list-inside text-gray-600 space-y-1">
                            <li>Never share your Client Secret</li>
                            <li>Use HTTPS for redirect URIs</li>
                            <li>Regularly review authorized domains</li>
                            <li>Monitor OAuth login activity</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-indigo-700 flex items-center gap-2">
                    <i class="fas fa-chart-bar"></i> OAuth Statistics
                </h2>
                @php
                    $totalUsers = \App\Models\User::count();
                    $googleUsers = \App\Models\User::where('auth_provider', 'google')->count();
                    $emailUsers = \App\Models\User::where('auth_provider', 'email')->count();
                @endphp
                <div class="mt-4 grid grid-cols-2 text-center">
                    <div class="col-span-2 mb-4">
                        <p class="text-2xl font-bold text-indigo-600">{{ $totalUsers }}</p>
                        <p class="text-sm text-gray-600">Total Users</p>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-green-600">{{ $googleUsers }}</p>
                        <p class="text-xs text-gray-600">Google</p>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-blue-500">{{ $emailUsers }}</p>
                        <p class="text-xs text-gray-600">Email</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Keep your existing JS (works the same)
toggleGoogleOAuthFields();
</script>
@endsection
