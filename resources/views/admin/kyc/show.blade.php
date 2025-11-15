@extends('admin.layouts.app')

@section('title', 'KYC Verification')

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">KYC Verification</h1>

        <a href="{{ url('admin/kyc') }}" 
           class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- User Info Card -->
    <div class="bg-white border shadow-md rounded-xl p-6 mb-6">
        <h2 class="text-lg font-semibold border-b pb-3 mb-4 text-gray-700">
            User Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="font-semibold text-gray-600">Name:</p>
                <p>{{ $user->kyc_full_name }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-600">Document Type:</p>
                <p class="capitalize">{{ $user->kyc_document_type }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-600">Document Number:</p>
                <p>{{ $user->kyc_document_number }}</p>
            </div>

            <div>
                <p class="font-semibold text-gray-600">KYC Status:</p>

                @php
                    $color = match($user->kyc_status) {
                        'approved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        default => 'bg-gray-200 text-gray-700'
                    };
                @endphp

                <span class="px-3 py-1 rounded-lg text-sm {{ $color }}">
                    {{ strtoupper($user->kyc_status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="bg-white border shadow-md rounded-xl p-6 mb-6">
        <h2 class="text-lg font-semibold border-b pb-3 mb-4 text-gray-700">
            KYC Documents
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- FRONT -->
            <div class="text-center">
                <p class="font-semibold mb-2">Front Document</p>
                <img src="{{ asset( $user->kyc_front) }}"
                     class="w-full h-48 object-cover rounded-lg shadow">
            </div>

            <!-- BACK -->
            <div class="text-center">
                <p class="font-semibold mb-2">Back Document</p>
                <img src="{{ asset( $user->kyc_back) }}"
                     class="w-full h-48 object-cover rounded-lg shadow">
            </div>

            <!-- SELFIE -->
            <div class="text-center">
                <p class="font-semibold mb-2">Selfie with Document</p>
                <img src="{{ asset( $user->kyc_selfie) }}"
                     class="w-full h-48 object-cover rounded-lg shadow">
            </div>

            <!-- PROOF -->
            <div class="text-center">
                <p class="font-semibold mb-2">Proof of Residence</p>
                <img src="{{ asset( $user->kyc_proof) }}"
                     class="w-full h-48 object-cover rounded-lg shadow">
            </div>

        </div>
    </div>

    <!-- Approve / Reject -->
    <div class="bg-white border shadow-md rounded-xl p-6">

        <h2 class="text-lg font-semibold border-b pb-3 mb-4 text-gray-700">Admin Action</h2>

        <!-- Reject Form -->
        <form action="{{ url('admin/kyc/reject', $user->id) }}" method="POST" class="mb-4">
            @csrf
            @method('PUT')

            <label class="font-semibold text-gray-700">Rejection Reason</label>
            <textarea name="kyc_rejection_reason" required
                      class="mt-2 w-full border rounded-lg p-3 focus:ring focus:ring-red-200"
                      rows="3"
                      placeholder="Enter reason for rejection..."></textarea>

            <button type="submit"
                class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 w-full md:w-auto">
                <i class="fa fa-times-circle"></i> Reject KYC
            </button>
        </form>

        <!-- Approve -->
        <form action="{{ url('admin/kyc/approve', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 w-full md:w-auto">
                <i class="fa fa-check-circle"></i> Approve KYC
            </button>
        </form>

    </div>

</div>

@endsection