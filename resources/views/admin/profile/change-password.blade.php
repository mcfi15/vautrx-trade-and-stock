@extends('admin.layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white shadow-lg rounded-lg p-8">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">Change Password</h2>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.password.update') }}" method="POST">
        @csrf

        <!-- Current Password -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Current Password</label>
            <input type="password" name="current_password"
                class="w-full border border-gray-300 p-3 rounded focus:ring focus:ring-blue-300"
                required>

            @error('current_password')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">New Password</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 p-3 rounded focus:ring focus:ring-blue-300"
                required>

            @error('password')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation"
                class="w-full border border-gray-300 p-3 rounded focus:ring focus:ring-blue-300"
                required>
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
            Update Password
        </button>
    </form>
</div>
@endsection
