@extends('admin.layouts.app')

@section('title', 'Deposit Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.deposits.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Deposits
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Deposit #{{ $deposit->id }}</h1>
                <p class="mt-2 text-gray-600">Deposit details and management</p>
            </div>
            <div class="flex space-x-2">
                @if($deposit->status === 'pending')
                    <button onclick="showApprovalModal()" 
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                    <button onclick="showRejectionModal()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reject
                    </button>
                @endif
                <a href="{{ route('admin.deposits.edit', $deposit) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Deposit Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
            <div class="text-center">
                <span class="px-4 py-2 inline-flex text-lg leading-5 font-semibold rounded-full 
                    @if($deposit->status === 'completed') bg-green-100 text-green-800
                    @elseif($deposit->status === 'confirmed') bg-blue-100 text-blue-800
                    @elseif($deposit->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($deposit->status) }}
                </span>
                @if($deposit->status === 'pending' && $deposit->hasPaymentProof())
                <div class="mt-2 text-sm text-green-600">
                    <i class="fas fa-check-circle mr-1"></i>Payment proof uploaded
                </div>
                @endif
            </div>
            
            @if($deposit->admin_notes)
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Admin Notes:</h4>
                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $deposit->admin_notes }}</p>
            </div>
            @endif
            
            @if($deposit->reviewed_at && $deposit->reviewedBy)
            <div class="mt-4">
                <p class="text-sm text-gray-500">
                    Reviewed by {{ $deposit->reviewedBy->name }} on {{ $deposit->reviewed_at->format('M d, Y H:i') }}
                </p>
            </div>
            @endif
        </div>

        <!-- Amount Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Amount Details</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Amount:</span>
                    <span class="font-medium">{{ number_format($deposit->amount, 8) }} {{ strtoupper($deposit->cryptocurrency->symbol) }}</span>
                </div>
                @if($deposit->fee > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Fee:</span>
                    <span class="font-medium">{{ number_format($deposit->fee, 8) }} {{ strtoupper($deposit->cryptocurrency->symbol) }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Network:</span>
                    <span class="font-medium">{{ $deposit->cryptocurrency->network ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Confirmations:</span>
                    <span class="font-medium">{{ $deposit->confirmations }}/{{ $deposit->required_confirmations }}</span>
                </div>
            </div>
        </div>

        <!-- User Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600">Name:</span>
                    <div class="font-medium">{{ $deposit->user->name }}</div>
                </div>
                <div>
                    <span class="text-gray-600">Email:</span>
                    <div class="font-medium">{{ $deposit->user->email }}</div>
                </div>
                <div>
                    <span class="text-gray-600">User ID:</span>
                    <div class="font-medium">#{{ $deposit->user->id }}</div>
                </div>
                <div>
                    <span class="text-gray-600">Joined:</span>
                    <div class="font-medium">{{ $deposit->user->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Proof Section -->
    @if($deposit->hasPaymentProof())
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Payment Proof</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.deposits.payment-proof', $deposit) }}" 
                   target="_blank"
                   class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                    <i class="fas fa-eye mr-1"></i>View Full Size
                </a>
                <a href="{{ route('admin.deposits.payment-proof.download', $deposit) }}" 
                   class="bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700">
                    <i class="fas fa-download mr-1"></i>Download
                </a>
                <form method="POST" action="{{ route('admin.deposits.payment-proof.delete', $deposit) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to delete this payment proof?')"
                            class="bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
        
        <div class="border rounded-lg p-4">
            <img src="{{ asset($deposit->payment_proof_path) }}" 
                 alt="Payment Proof" 
                 class="w-full h-auto max-h-96 object-contain rounded cursor-pointer"
                 onclick="openImageModal('{{ $deposit->payment_proof_url }}')">
            <div class="mt-2 text-sm text-gray-500">
                <i class="fas fa-file mr-1"></i>
                {{ $deposit->payment_proof_filename }} 
                (Uploaded {{ $deposit->updated_at->format('M d, Y H:i') }})
            </div>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
            <div>
                <h3 class="text-lg font-medium text-yellow-800">No Payment Proof</h3>
                <p class="text-yellow-700">No payment proof has been uploaded for this deposit.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Transaction Details -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Hash</label>
                <div class="flex items-center">
                    <input type="text" 
                           value="{{ $deposit->transaction_hash }}" 
                           readonly 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l bg-gray-50 text-sm">
                    <button onclick="copyToClipboard('{{ $deposit->transaction_hash }}')" 
                            class="px-3 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700 text-sm">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <a href="#" onclick="viewOnBlockchain('{{ $deposit->transaction_hash }}')" 
                   class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-block">
                    <i class="fas fa-external-link-alt mr-1"></i>View on Blockchain
                </a>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Created</label>
                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-sm">
                    {{ $deposit->created_at->format('M d, Y H:i:s') }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-sm">
                    {{ $deposit->updated_at->format('M d, Y H:i:s') }}
                </div>
            </div>
            
            @if($deposit->transaction)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-sm">
                    #{{ $deposit->transaction->id }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Cryptocurrency Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cryptocurrency Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                        {{ strtoupper(substr($deposit->cryptocurrency->symbol, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-medium">{{ $deposit->cryptocurrency->name }}</div>
                        <div class="text-sm text-gray-500">{{ strtoupper($deposit->cryptocurrency->symbol) }}</div>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Price</label>
                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-sm">
                    ${{ number_format($deposit->cryptocurrency->current_price ?? 0, 2) }}
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Network</label>
                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded text-sm">
                    {{ $deposit->cryptocurrency->network ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="max-w-4xl max-h-4xl p-4">
        <div class="relative">
            <button onclick="closeImageModal()" 
                    class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full w-8 h-8 flex items-center justify-center hover:bg-opacity-75">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="Payment Proof" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Deposit</h3>
            <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                    <textarea name="admin_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Add any notes about this approval..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideApprovalModal()" 
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Deposit</h3>
            <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                    <textarea name="admin_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Reason for rejection (required)..." required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectionModal()" 
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showApprovalModal() {
    document.getElementById('approvalModal').classList.remove('hidden');
}

function hideApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
}

function showRejectionModal() {
    document.getElementById('rejectionModal').classList.remove('hidden');
}

function hideRejectionModal() {
    document.getElementById('rejectionModal').classList.add('hidden');
}

function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a success message (you can implement a toast notification here)
        alert('Copied to clipboard!');
    });
}

function viewOnBlockchain(hash) {
    // Implement blockchain explorer link based on cryptocurrency network
    const baseUrl = 'https://bscscan.com/tx/'; // Example for BSC
    window.open(baseUrl + hash, '_blank');
}

// Close modals when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) closeImageModal();
});

document.getElementById('approvalModal').addEventListener('click', function(e) {
    if (e.target === this) hideApprovalModal();
});

document.getElementById('rejectionModal').addEventListener('click', function(e) {
    if (e.target === this) hideRejectionModal();
});
</script>
@endsection