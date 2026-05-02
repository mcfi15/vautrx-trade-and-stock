@extends('layouts.app')

@section('title', 'Withdraw ' . ($cryptocurrency->name ?? 'Cryptocurrency'))

@section('content')

<main class="wrapper grey-bg airdrop-page">
    <div class="container">
        <div class="container ">
            <div class="row mt-3 mb-3">
                <div class="col-12 col-md-6 order-2 order-md-1">
                    <div class="page-title-content d-flex align-items-start mt-2">
                        <span>Welcome, <span> {{ Auth::user()->name ?? 'User' }}!</span> <br /></span>
                    </div>
                </div>

                <div class="col-12 col-md-6 order-1 order-md-2 p-0 float-right">
                    <ul class="text-right breadcrumbs list-unstyle">
                        <li>
                            <a href="{{ url('wallet') }}" class="btn btn-primary btn-sm">Finance</a>
                        </li>
                        <li class="btn btn-primary btn-sm active">Crypto Withdrawal</li>
                        
                    </ul>
                </div>
            </div>
        </div>

        <div class="mb-4 d-flex flex-wrap gap-3">
            <a href="{{ url('wallet/deposit') }}" class="btn btn-primary text-dark fw-semibold">
                <i class="fa fa-plus-circle me-2"></i> Deposit
            </a>
            <a href="{{ route('wallet.withdraw') }}" class="btn btn-outline-light">
                <i class="fa fa-minus-circle me-2"></i> Withdraw
            </a>
            <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-light">
                <i class="fa fa-history me-2"></i> Transaction History
            </a>
        </div>


        <div class="row">
            <div class="col-12">
                <ul id="withdraw-money-tabs" class="nav nav-pills" role="tablist">
                    <li class="nav-item white-bg">
                        <a class="nav-link active" data-toggle="pill" href="#crypto" role="tab">Crypto Withdrawal</a>
                    </li>
                    <li class="nav-item white-bg">
                        <a class="nav-link" data-toggle="pill" href="#bank" role="tab">Bank Withdrawal</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-12 white-bg">
            <div class="tab-content withdraw-tab white-bg">

                <div class="tab-pane fade show active p-t-15 crypto" role="tabpanel" id="crypto">
                    <form id="withdrawalForm" method="POST" action="{{ route('wallet.withdraw.process') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Select Coin</label>
                                    <select id="withdrawCyrpto" name="cryptocurrency_id" class="bootstrap-select" data-live-search="true" data-live-search-placeholder='Search' data-width="100%">
                                        @foreach($cryptocurrencies as $crypto)
                                            <option value="{{ $crypto->id }}" 
                                                    data-symbol="{{ $crypto->symbol }}"
                                                    data-name="{{ $crypto->name }}"
                                                    data-content="<img src='{{ $crypto->logo_url }}' height='20px'/> {{ strtoupper($crypto->symbol) }}"
                                                    {{ $selectedCrypto && $selectedCrypto->id == $crypto->id ? 'selected' : '' }}>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="alert alert-primary hide-mobile">
                                    <ul class="description-box-rules small">
                                        <li>Do not crowdfund or directly withdraw to ICO addresses as tokens from such sales will not be credited to your account.</li>
                                        <li>Transfers between Dectrx accounts are internal transfers, there is no transaction fee for internal transfers and the entire amount you enter in the amount section is sent to the recipient.</li>
                                    </ul>
                                </div>
                                
                                <div class="form-group">
                                    <label>Network</label>
                                    <select id="selectNetwork" name="network" class="bootstrap-select" data-live-search="true" data-live-search-placeholder='Search' data-width="100%">
                                        @if($selectedCrypto)
                                            <option value="{{ $selectedCrypto->symbol }}" selected="selected">
                                                {{ $selectedCrypto->name }} ({{ strtoupper($selectedCrypto->symbol) }})
                                            </option>
                                        @else
                                            <option value="" selected="selected">Please select network</option>
                                        @endif
                                    </select>
                                    <small>Make sure the network you choose for the deposit matches the withdrawal network or your assets may be lost.</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="">{{ $selectedCrypto ? strtoupper($selectedCrypto->symbol) : 'Coin' }} Withdrawal Address</label>
                                    <select id="crypto-address" name="address" class="bootstrap-select" data-live-search="true" data-live-search-placeholder="Search" data-width="100%" title="Select withdrawals address">
                                        <option value="new-address" class="yellow">+ Add New Address</option>
                                        @if($addresses)
                                            @foreach($addresses as $address)
                                                <option value="{{ $address->address }}" data-dest-tag="{{ $address->dest_tag ?? '' }}">
                                                    {{ $address->label ?: $address->address }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="usdout_paypassword">Fund Password</label> 
                                    <input type="password" id="usdout_paypassword" name="fund_password" class="form-control" placeholder="Enter Funding Password" required>
                                    <a href="{{ url('/fund-password') }}" class="yellow m-t-5 d-inline-block">I forgot my password?</a>
                                </div>

                                <div class="not0">
                                    <div class="alert alert-primary ">
                                        <div class="row">
                                            <div class="small col-md-4 col-sm-12">
                                                <div><span class="text-bold">Balance:</span> 
                                                    <span id="balance">{{ $walletBalance ?? 0 }}</span> 
                                                    <span id="balanceSymbol">{{ $selectedCrypto ? strtoupper($selectedCrypto->symbol) : 'BAT' }}</span> 
                                                    ≈ <span class="text-small" id="balanceUsd">0 USDT</span>
                                                </div>
                                            </div>	
                                            <div class="small col-md-4 col-sm-12">
                                                <div><span class="text-bold">Limit:</span> ≤ <span id="maxLimit">10000</span> and ≥ <span id="minLimit">0.01</span> <span id="limitSymbol">{{ $selectedCrypto ? strtoupper($selectedCrypto->symbol) : 'BAT' }}</span></div>
                                            </div>
                                            <div class="small col-md-4 col-sm-12">
                                                <div><span class="text-bold">Fees</span> <span id="perfee">0.1</span>% + <span id="flatfee">0.001</span> <span id="feeSymbol">{{ $selectedCrypto ? strtoupper($selectedCrypto->symbol) : 'BAT' }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Amount</label> 
                                    <input class="form-control" placeholder="Minimum 0.01" type="number" id="usdout_num" name="amount" step="0.00000001" required>
                                </div>
                                
                                <div class="form-group input-group">
                                    <input type="text" class="form-control" id="otp" name="otp" value="" placeholder="Enter OTP" required>
                                    <div class="input-group-append">
                                        <button type="button" onclick="requestOTP()" class="btn btn-outline-secondary">Request OTP</button>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-6 p-l-0 p-r-0">
                                        <span class="btn btn-block bold common-text">Receiveable: <span id="receiveable">0.00</span> <span id="receiveSymbol">{{ $selectedCrypto ? strtoupper($selectedCrypto->symbol) : 'BAT' }}</span></span>
                                    </div>
                                    <div class="col-6 align-self-center">
                                        <button type="submit" class="btn btn-block yellow-bg text-center">Withdraw</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade bank" id="bank" role="tabpanel">
                    <form id="bankWithdrawalForm" method="POST" action="{{ route('wallet.withdraw.bank.process') }}">
                        @csrf
                        <input type="hidden" name="type" value="bank">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                    <label>Select Coin to Withdraw From</label>
                    <select name="cryptocurrency_id" class="form-control select-control" style="color:black !important;">
                        @foreach($cryptocurrencies as $crypto)
                            <option value="{{ $crypto->id }}" 
                                    data-symbol="{{ $crypto->symbol }}"
                                    data-name="{{ $crypto->name }}"
                                    data-content="<img src='{{ $crypto->logo_url }}' height='20px'/> {{ strtoupper($crypto->symbol) }}"
                                    {{ $selectedCrypto && $selectedCrypto->id == $crypto->id ? 'selected' : '' }}>
                            </option>
                        @endforeach
                    </select>
                </div>
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="e.g. JPMorgan Chase" required>
                                </div>
                                <div class="form-group">
                                    <label>Account Holder Name</label>
                                    <input type="text" name="account_name" class="form-control" placeholder="Full legal name" required>
                                </div>
                                <div class="form-group">
                                    <label>Account Number / IBAN</label>
                                    <input type="text" name="account_number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>SWIFT / BIC Code</label>
                                    <input type="text" name="swift_code" class="form-control" placeholder="Optional for domestic">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                
                                <div class="form-group">
                                    <label>Withdrawal Amount (USD)</label>
                                    <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required>
                                </div>
                                <div class="form-group input-group">
                                    <input type="text" class="form-control" id="otp" name="otp" value="" placeholder="Enter OTP" required>
                                    <div class="input-group-append">
                                        <button type="button" onclick="requestOTP()" class="btn btn-outline-secondary">Request OTP</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Funding Password</label>
                                    <input type="password" name="fund_password" class="form-control" required>
                                </div>
                                <div class="alert alert-info small">
                                    <i class="fa fa-info-circle"></i> Bank transfers typically take 1-3 business days to process. Ensure all details are correct to avoid reversal fees.
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Request Bank Transfer</button>
                            </div>
                        </div>
                    </form>

                </div>
                
            </div>
        </div>
        
        <!-- Withdrawal History Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card white-bg">
                    <div class="card-header">
                        <h4 class="mb-0">Withdrawal History</h4>
                    </div>
                    <div class="card-body">
                        @if($withdrawals && $withdrawals->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <<th>Date</th>
                                            <th>Coin</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            {{-- <th>Transaction ID</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($withdrawals as $withdrawal)
                                            <tr>
                                                <td>{{ $withdrawal->created_at->format('Y-m-d H:i') }}</td>
                                                <td>

                                                    <div class="d-flex align-items-center">

                                                        @if($withdrawal->cryptocurrency && $withdrawal->cryptocurrency->logo_url)

                                                            <img src="{{ $withdrawal->cryptocurrency->logo_url }}" style="height:30px;width:30px;" class="mr-2">

                                                        @endif

                                                        <span>{{ strtoupper($withdrawal->cryptocurrency->symbol ?? 'N/A') }}</span>

                                                    </div>

                                                </td>
                                                <td><span class="badge badge-info">{{ strtoupper($withdrawal->withdrawal_type ?? 'Crypto') }}</span></td>
                                                <td>{{ number_format($withdrawal->amount, 8) }}</td>
                                                <td>{{ $withdrawal->withdrawal_address ?? $withdrawal->bank_name }}</td>
                                                <td>
                                                    @if($withdrawal->status === 'completed')
                                                        <span class="badge badge-success">Completed</span>
                                                    @elseif($withdrawal->status === 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($withdrawal->status === 'failed')
                                                        <span class="badge badge-danger">Failed</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                                    @endif
                                                </td>
                                                {{-- <td class="text-truncate" style="max-width: 120px;">
                                                    @if($withdrawal->txid)
                                                        <a href="#" class="text-primary" title="{{ $withdrawal->txid }}">
                                                            {{ substr($withdrawal->txid, 0, 10) }}...
                                                        </a>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @if($withdrawals->hasPages())
                                <div class="mt-3">
                                    {{ $withdrawals->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">No withdrawal history found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add payment method modal -->
    <div class="modal fade" id="addPaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="Add new payment method" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">
                    <div class="coin-title d-flex align-items-center">
                        <div class="title">Add your withdrawal address</div>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Crypto</label>
                                    <span id="selectedCoin" class="form-control"></span>
                                    <input type="hidden" id="selectedCoinId">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <label>Network</label>
                                    <span id="selectedChain" class="form-control"></span>
                                    <input type="hidden" id="selectedNetworkValue">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Withdrawal Address</label> 
                                    <input id="wallet_addr" type="text" class="form-control" placeholder="Please enter the withdrawal address" required>
                                </div>
                            </div>
                            {{-- <div class="col-12">
                                <div class="form-group">
                                    <label>Dest Tag [if any]</label> 
                                    <input type="text" id="wallet_dest_tag" class="form-control" placeholder="Enter the Dest tag if any">
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Funding Password</label> 
                                    <input type="password" id="wallet_paypassword" class="form-control" placeholder="Enter your funding password" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Address Label</label> 
                                    <input type="text" id="wallet_name" class="form-control" placeholder="Enter the address label">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-1" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="wallet_up();" class="btn-2">Approve</button>
            </div>
        </div>
    </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap Select
    $('.bootstrap-select').selectpicker();
    
    // Coin selection change handler
    $('#withdrawCyrpto').on('changed.bs.select', function(e) {
        const selectedCoinId = $(this).val();
        const selectedCoinSymbol = $(this).find('option:selected').data('symbol');
        const selectedCoinName = $(this).find('option:selected').data('name');
        
        // Update network dropdown with coin name and symbol
        $('#selectNetwork').empty();
        $('#selectNetwork').append(`<option value="${selectedCoinSymbol}" selected>${selectedCoinName} (${selectedCoinSymbol.toUpperCase()})</option>`);
        $('#selectNetwork').selectpicker('refresh');
        
        // Update all symbol references on the page
        updateCoinSymbols(selectedCoinSymbol);
        
        // Reload page with selected coin to get updated addresses and balance
        window.location.href = `/wallet/withdraw?coin=${selectedCoinId}`;
    });
    
    // Address selection handler
    $('#crypto-address').on('changed.bs.select', function(e) {
        const selectedValue = $(this).val();
        
        if (selectedValue === 'new-address') {
            // Show modal to add new address
            const selectedCoinId = $('#withdrawCyrpto').val();
            const selectedCoinSymbol = $('#withdrawCyrpto option:selected').data('symbol');
            const selectedCoinName = $('#withdrawCyrpto option:selected').data('name');
            const selectedNetwork = $('#selectNetwork option:selected').text().trim();
            const selectedNetworkValue = $('#selectNetwork').val();
            
            // Populate modal fields
            $('#selectedCoin').text(`${selectedCoinName} (${selectedCoinSymbol.toUpperCase()})`);
            $('#selectedCoinId').val(selectedCoinId);
            $('#selectedChain').text(selectedNetwork);
            $('#selectedNetworkValue').val(selectedNetworkValue);
            
            $('#addPaymentMethodModal').modal('show');
            
            // Reset the select to previous value
            $(this).val($(this).data('prev-value') || '').selectpicker('refresh');
        } else {
            // Store current value for next time
            $(this).data('prev-value', selectedValue);
            
            // If address has dest tag, show it or handle accordingly
            const selectedOption = $(this).find('option:selected');
            const destTag = selectedOption.data('dest-tag');
            if (destTag) {
                // You might want to display the dest tag somewhere or handle it
                console.log('Destination tag:', destTag);
            }
        }
    });
    
    // Amount input handler to calculate receiveable amount
    $('#usdout_num').on('input', function() {
        calculateReceiveable();
    });
    
    // Initial calculation
    calculateReceiveable();
    
    // Display flash messages if any
    displayFlashMessages();
});

function updateCoinSymbols(symbol) {
    const upperSymbol = symbol.toUpperCase();
    $('#balanceSymbol').text(upperSymbol);
    $('#limitSymbol').text(upperSymbol);
    $('#feeSymbol').text(upperSymbol);
    $('#receiveSymbol').text(upperSymbol);
    
    // Update the address label
    $('label[for="crypto-address"]').text(`${upperSymbol} Withdrawal Address`);
}

function calculateReceiveable() {
    const amount = parseFloat($('#usdout_num').val()) || 0;
    const feePercentage = parseFloat($('#perfee').text()) / 100;
    const flatFee = parseFloat($('#flatfee').text());
    
    const feeAmount = (amount * feePercentage) + flatFee;
    const receiveable = Math.max(0, amount - feeAmount);
    
    $('#receiveable').text(receiveable.toFixed(8));
}

function requestOTP() {
    // Show loading state
    const otpButton = $('.btn-outline-secondary');
    otpButton.prop('disabled', true).text('Sending...');
    
    // Send AJAX request to get OTP
    fetch('{{ route("wallet.withdraw.sendOtp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('OTP sent to your email!');
        } else {
            alert('Failed to send OTP: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send OTP. Please try again.');
    })
    .finally(() => {
        otpButton.prop('disabled', false).text('Request OTP');
    });
}

function wallet_up() {
    const coinId = $('#selectedCoinId').val();
    const address = $('#wallet_addr').val();
    const label = $('#wallet_name').val();
    const network = $('#selectedNetworkValue').val();
    const destTag = $('#wallet_dest_tag').val();
    const fundPassword = $('#wallet_paypassword').val();
    
    if (!address) {
        alert('Please enter a withdrawal address');
        return;
    }
    
    if (!fundPassword) {
        alert('Please enter your funding password');
        return;
    }

    // Show loading state
    const approveButton = $('.btn-2');
    approveButton.prop('disabled', true).text('Adding...');

    // Create form data for proper encoding
    const formData = new FormData();
    formData.append('cryptocurrency_id', coinId);
    formData.append('address', address);
    formData.append('label', label || '');
    formData.append('network', network || '');
    formData.append('dest_tag', destTag || '');
    formData.append('fund_password', fundPassword);
    formData.append('_token', '{{ csrf_token() }}');

    // Send AJAX request to add address
    fetch('{{ route("wallet.withdraw.addAddress") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Add the new address to the dropdown
            const displayText = label ? `${label} (${address})` : address;
            const newOption = `<option value="${address}" data-dest-tag="${destTag || ''}">${displayText}</option>`;
            $('#crypto-address').append(newOption);
            $('#crypto-address').selectpicker('refresh');
            
            // Select the new address
            $('#crypto-address').val(address);
            $('#crypto-address').selectpicker('refresh');
            $('#crypto-address').data('prev-value', address);
            
            // Close modal and reset form
            $('#addPaymentMethodModal').modal('hide');
            $('#wallet_addr, #wallet_name, #wallet_dest_tag, #wallet_paypassword').val('');
            
            alert('Address added successfully!');
        } else {
            alert('Failed to add address: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add address. Please try again.');
    })
    .finally(() => {
        approveButton.prop('disabled', false).text('Approve');
    });
}

// Enhanced Form submission handler
$('#withdrawalForm').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    
    // Clear previous errors
    clearErrors();
    
    // Basic validation
    let isValid = true;
    const amount = parseFloat($('#usdout_num').val());
    const minLimit = parseFloat($('#minLimit').text());
    const maxLimit = parseFloat($('#maxLimit').text());
    const address = $('#crypto-address').val();
    const fundPassword = $('#usdout_paypassword').val();
    const otp = $('#otp').val();
    
    // Validate amount
    if (!amount || amount <= 0) {
        showError('usdout_num', 'Please enter a valid amount');
        isValid = false;
    } else if (amount < minLimit || amount > maxLimit) {
        showError('usdout_num', `Amount must be between ${minLimit} and ${maxLimit}`);
        isValid = false;
    }
    
    // Validate address
    if (!address || address === 'new-address') {
        showError('crypto-address', 'Please select a withdrawal address');
        isValid = false;
    }
    
    // Validate fund password
    if (!fundPassword) {
        showError('usdout_paypassword', 'Please enter your funding password');
        isValid = false;
    }
    
    // Validate OTP
    if (!otp) {
        showError('otp', 'Please enter OTP');
        isValid = false;
    }
    
    if (!isValid) {
        return false;
    }
    
    // If all validations pass, submit the form
    this.submit();
});

// Helper functions for error handling
function showError(fieldName, message) {
    const field = $(`[name="${fieldName}"]`);
    const formGroup = field.closest('.form-group');
    
    // Remove existing error
    formGroup.find('.error-message').remove();
    
    // Add error message
    formGroup.append(`<div class="error-message text-danger small mt-1">${message}</div>`);
    
    // Add error class to field
    field.addClass('is-invalid');
}

function clearErrors() {
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
}

function displayFlashMessages() {
    // Check for Laravel flash messages
    @if(session('success'))
        alert('Success: {{ session('success') }}');
    @endif
    
    @if(session('error'))
        alert('Error: {{ session('error') }}');
    @endif
    
    // Display validation errors
    @if($errors->any())
        @foreach($errors->all() as $error)
            alert('Error: {{ $error }}');
        @endforeach
    @endif
}

// Reset modal when closed
$('#addPaymentMethodModal').on('hidden.bs.modal', function () {
    $('#wallet_addr, #wallet_name, #wallet_dest_tag, #wallet_paypassword').val('');
    $('.btn-2').prop('disabled', false).text('Approve');
});



// Bank Withdrawal Specific Script
document.addEventListener('DOMContentLoaded', function() {
    // Initialize bank withdrawal specific elements
    initBankWithdrawal();
});

function initBankWithdrawal() {
    // Get all bank withdrawal form elements
    const bankForm = document.getElementById('bankWithdrawalForm');
    if (!bankForm) return;
    
    const bankAmountInput = bankForm.querySelector('input[name="amount"]');
    const bankCoinSelect = bankForm.querySelector('select[name="cryptocurrency_id"]');
    const bankNameInput = bankForm.querySelector('input[name="bank_name"]');
    const accountNameInput = bankForm.querySelector('input[name="account_name"]');
    const accountNumberInput = bankForm.querySelector('input[name="account_number"]');
    const swiftCodeInput = bankForm.querySelector('input[name="swift_code"]');
    const bankOTPButton = bankForm.querySelector('.input-group-append .btn-outline-secondary');
    const bankOTPInput = bankForm.querySelector('#otp');
    
    // Add bank withdrawal specific validation and features
    setupBankAmountValidation(bankAmountInput, bankCoinSelect);
    setupBankDetailsValidation(bankNameInput, accountNameInput, accountNumberInput, swiftCodeInput);
    setupBankOTPHandler(bankOTPButton, bankOTPInput);
    setupBankFormSubmission(bankForm);
    setupBankCurrencyDisplay(bankCoinSelect);
    setupBankSavedAccounts(bankForm);
}

// Bank amount validation with real-time USD to crypto conversion
function setupBankAmountValidation(amountInput, coinSelect) {
    if (!amountInput || !coinSelect) return;
    
    // Add input event for real-time validation
    amountInput.addEventListener('input', function() {
        const amountUSD = parseFloat(this.value) || 0;
        const selectedCoin = coinSelect.options[coinSelect.selectedIndex];
        const coinSymbol = selectedCoin ? selectedCoin.getAttribute('data-symbol') : 'BTC';
        
        // Validate amount range (assuming bank withdrawal minimum $50, maximum $50,000)
        if (amountUSD > 0) {
            if (amountUSD < 50) {
                showBankError(amountInput, 'Minimum bank withdrawal amount is $50.00');
                updateBankWithdrawalSummary(0, coinSymbol);
            } else if (amountUSD > 50000) {
                showBankError(amountInput, 'Maximum bank withdrawal amount is $50,000.00');
                updateBankWithdrawalSummary(0, coinSymbol);
            } else {
                clearBankError(amountInput);
                updateBankWithdrawalSummary(amountUSD, coinSymbol);
            }
        } else {
            clearBankError(amountInput);
            updateBankWithdrawalSummary(0, coinSymbol);
        }
    });
    
    // Add blur event for final validation
    amountInput.addEventListener('blur', function() {
        const amountUSD = parseFloat(this.value) || 0;
        if (amountUSD > 0 && (amountUSD < 50 || amountUSD > 50000)) {
            this.value = '';
            showBankError(amountInput, `Amount must be between $50 and $50,000 USD`);
        }
    });
}

// Update withdrawal summary with fees and estimated crypto amount
function updateBankWithdrawalSummary(amountUSD, coinSymbol) {
    // Find or create summary div
    let summaryDiv = document.querySelector('.bank-withdrawal-summary');
    if (!summaryDiv) {
        const bankAmountGroup = document.querySelector('#bankWithdrawalForm .form-group:has(input[name="amount"])');
        if (bankAmountGroup) {
            summaryDiv = document.createElement('div');
            summaryDiv.className = 'alert alert-info bank-withdrawal-summary mt-2';
            bankAmountGroup.parentNode.insertBefore(summaryDiv, bankAmountGroup.nextSibling);
        }
    }
    
    if (summaryDiv && amountUSD > 0) {
        // Calculate fees (example: 1% fee with minimum $5)
        const feePercentage = 1; // 1%
        const minFee = 5;
        let feeAmount = (amountUSD * feePercentage) / 100;
        feeAmount = Math.max(minFee, feeAmount);
        const netAmount = amountUSD - feeAmount;
        
        // Estimated crypto conversion (assuming BTC price - you should fetch real price from API)
        const estimatedCrypto = (netAmount / 50000).toFixed(8); // Placeholder conversion
        
        summaryDiv.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">Withdrawal Amount:</small>
                    <strong class="text-success">$${amountUSD.toFixed(2)} USD</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Fee (${feePercentage}%):</small>
                    <strong class="text-warning">$${feeAmount.toFixed(2)} USD</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Net Amount:</small>
                    <strong class="text-primary">$${netAmount.toFixed(2)} USD</strong>
                </div>
                <div class="col-md-12 mt-2">
                    <small class="text-muted">Estimated ${coinSymbol.toUpperCase()} Amount:</small>
                    <strong>${estimatedCrypto} ${coinSymbol.toUpperCase()}</strong>
                    <small class="text-muted d-block">*Rate may vary at time of processing</small>
                </div>
            </div>
        `;
    } else if (summaryDiv) {
        summaryDiv.innerHTML = '<small class="text-muted">Enter amount to see withdrawal summary</small>';
    }
}

// Bank details validation with bank-specific rules
function setupBankDetailsValidation(bankNameInput, accountNameInput, accountNumberInput, swiftCodeInput) {
    if (!bankNameInput) return;
    
    // Bank name validation
    bankNameInput.addEventListener('blur', function() {
        const bankName = this.value.trim();
        if (bankName.length < 3) {
            showBankError(this, 'Please enter a valid bank name (minimum 3 characters)');
        } else {
            clearBankError(this);
            suggestBankType(bankName);
        }
    });
    
    // Account holder name validation
    if (accountNameInput) {
        accountNameInput.addEventListener('blur', function() {
            const accountName = this.value.trim();
            if (accountName.length < 5) {
                showBankError(this, 'Please enter the complete account holder name');
            } else if (!/^[a-zA-Z\s\.\-]+$/.test(accountName)) {
                showBankError(this, 'Account name should only contain letters, spaces, dots, and hyphens');
            } else {
                clearBankError(this);
            }
        });
    }
    
    // Account number validation
    if (accountNumberInput) {
        accountNumberInput.addEventListener('blur', function() {
            const accountNumber = this.value.trim();
            if (accountNumber.length < 5 || accountNumber.length > 34) {
                showBankError(this, 'Account number/IBAN should be between 5 and 34 characters');
            } else if (!/^[a-zA-Z0-9]+$/.test(accountNumber)) {
                showBankError(this, 'Account number should only contain letters and numbers');
            } else {
                clearBankError(this);
                if (accountNumber.length >= 15 && /^[A-Z]{2}[0-9]{2}/.test(accountNumber)) {
                    validateIBAN(accountNumber);
                }
            }
        });
    }
    
    // SWIFT/BIC validation
    if (swiftCodeInput) {
        swiftCodeInput.addEventListener('blur', function() {
            const swiftCode = this.value.trim();
            if (swiftCode && swiftCode.length !== 8 && swiftCode.length !== 11) {
                showBankError(this, 'SWIFT/BIC code should be 8 or 11 characters');
            } else if (swiftCode && !/^[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?$/.test(swiftCode)) {
                showBankError(this, 'Please enter a valid SWIFT/BIC code format');
            } else {
                clearBankError(this);
            }
        });
    }
}

// Suggest bank type based on name
function suggestBankType(bankName) {
    const bankNameLower = bankName.toLowerCase();
    let bankType = '';
    
    if (bankNameLower.includes('chase') || bankNameLower.includes('jpmorgan')) {
        bankType = 'JPMorgan Chase (US Bank)';
    } else if (bankNameLower.includes('bank of america')) {
        bankType = 'Bank of America (US Bank)';
    } else if (bankNameLower.includes('wells fargo')) {
        bankType = 'Wells Fargo (US Bank)';
    } else if (bankNameLower.includes('hsbc')) {
        bankType = 'HSBC (International)';
    } else if (bankNameLower.includes('barclays')) {
        bankType = 'Barclays (UK Bank)';
    }
    
    if (bankType) {
        console.log(`Suggested bank type: ${bankType}`);
    }
}

// IBAN validation helper
function validateIBAN(iban) {
    const ibanPattern = /^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$/;
    if (!ibanPattern.test(iban)) {
        showBankError(document.querySelector('input[name="account_number"]'), 'Invalid IBAN format for international transfer');
        return false;
    }
    return true;
}

// Bank OTP handler
function setupBankOTPHandler(otpButton, otpInput) {
    if (!otpButton || !otpInput) return;
    
    // Remove any existing event listeners by cloning
    const newButton = otpButton.cloneNode(true);
    otpButton.parentNode.replaceChild(newButton, otpButton);
    
    newButton.addEventListener('click', function(e) {
        e.preventDefault();
        requestBankWithdrawalOTP(this, otpInput);
    });
}

function requestBankWithdrawalOTP(button, otpInput) {
    // Validate bank details before sending OTP
    const bankForm = document.getElementById('bankWithdrawalForm');
    const amount = bankForm.querySelector('input[name="amount"]').value;
    const bankName = bankForm.querySelector('input[name="bank_name"]').value;
    const accountName = bankForm.querySelector('input[name="account_name"]').value;
    const accountNumber = bankForm.querySelector('input[name="account_number"]').value;
    
    if (!amount || parseFloat(amount) < 50) {
        alert('Please enter a valid withdrawal amount (minimum $50)');
        return;
    }
    
    if (!bankName || !accountName || !accountNumber) {
        alert('Please fill in all bank details before requesting OTP');
        return;
    }
    
    // Disable button and show loading
    button.disabled = true;
    const originalText = button.textContent;
    button.textContent = 'Sending...';
    
    // Send OTP request
    fetch('/wallet/withdraw/send-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            type: 'bank',
            amount: amount,
            bank_name: bankName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('OTP has been sent to your registered email address. Please check your inbox.');
            startBankOTPTimer(button, originalText);
            otpInput.focus();
        } else {
            alert('Failed to send OTP: ' + (data.message || 'Please try again'));
            button.disabled = false;
            button.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please check your connection and try again.');
        button.disabled = false;
        button.textContent = originalText;
    });
}

function startBankOTPTimer(button, originalText) {
    let seconds = 60;
    const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(timer);
            button.disabled = false;
            button.textContent = originalText;
        } else {
            button.textContent = `Resend (${seconds}s)`;
        }
    }, 1000);
}

// Bank form submission with comprehensive validation
function setupBankFormSubmission(bankForm) {
    if (!bankForm) return;
    
    bankForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        clearBankErrors();
        
        // Validate all fields
        let isValid = true;
        
        // Validate coin selection
        const coinSelect = this.querySelector('select[name="cryptocurrency_id"]');
        if (!coinSelect.value) {
            showBankError(coinSelect, 'Please select a cryptocurrency');
            isValid = false;
        }
        
        // Validate amount
        const amount = this.querySelector('input[name="amount"]').value;
        const amountNum = parseFloat(amount);
        if (!amount || amountNum < 50 || amountNum > 50000) {
            showBankError(this.querySelector('input[name="amount"]'), 'Amount must be between $50 and $50,000 USD');
            isValid = false;
        }
        
        // Validate bank details
        const bankName = this.querySelector('input[name="bank_name"]').value;
        if (!bankName || bankName.trim().length < 3) {
            showBankError(this.querySelector('input[name="bank_name"]'), 'Please enter a valid bank name');
            isValid = false;
        }
        
        const accountName = this.querySelector('input[name="account_name"]').value;
        if (!accountName || accountName.trim().length < 5) {
            showBankError(this.querySelector('input[name="account_name"]'), 'Please enter the account holder name');
            isValid = false;
        }
        
        const accountNumber = this.querySelector('input[name="account_number"]').value;
        if (!accountNumber || accountNumber.trim().length < 5) {
            showBankError(this.querySelector('input[name="account_number"]'), 'Please enter a valid account number/IBAN');
            isValid = false;
        }
        
        // Validate OTP
        const otp = this.querySelector('#otp').value;
        if (!otp || otp.length < 4) {
            showBankError(this.querySelector('#otp'), 'Please enter the OTP sent to your email');
            isValid = false;
        }
        
        // Validate funding password
        const fundPassword = this.querySelector('input[name="fund_password"]').value;
        if (!fundPassword) {
            showBankError(this.querySelector('input[name="fund_password"]'), 'Please enter your funding password');
            isValid = false;
        }
        
        if (!isValid) {
            const firstError = document.querySelector('.bank-field-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Show confirmation dialog for bank withdrawal
        showBankWithdrawalConfirmation(this);
    });
}

// FIXED: Main submission function - handles redirect responses correctly
function showBankWithdrawalConfirmation(form) {
    const amount = form.querySelector('input[name="amount"]').value;
    const bankName = form.querySelector('input[name="bank_name"]').value;
    const accountName = form.querySelector('input[name="account_name"]').value;
    const accountNumber = form.querySelector('input[name="account_number"]').value;
    
    const confirmationMessage = `Please review your bank withdrawal details:

Amount: ${parseFloat(amount).toFixed(8)} (in crypto)
Bank: ${bankName}
Account Holder: ${accountName}
Account Number: ${accountNumber}

Note: Bank transfers typically take 1-3 business days to process.
Fees are non-refundable once the withdrawal is initiated.

Do you wish to proceed with this withdrawal?`;
    
    if (confirm(confirmationMessage)) {
        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';
        
        // Submit the form via AJAX
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // Check if response is a redirect (302)
            if (response.redirected) {
                // Success - withdrawal went through and server redirected
                showBankSuccessNotification('Bank withdrawal request submitted successfully! You will receive the funds within 1-3 business days.');
                resetBankForm(form);
                refreshBankWithdrawalData();
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data === null) return; // Already handled redirect case
            
            if (data.success) {
                showBankSuccessNotification(data.message || 'Bank withdrawal request submitted successfully!');
                resetBankForm(form);
                refreshBankWithdrawalData();
            } else {
                alert('Withdrawal failed: ' + (data.message || 'Please try again or contact support'));
                if (data.field) {
                    const field = form.querySelector(`[name="${data.field}"]`);
                    if (field) {
                        showBankError(field, data.message);
                        field.focus();
                    }
                }
            }
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        })
        .catch(error => {
            console.error('Error:', error);
            // If we get a network error but the withdrawal might have gone through,
            // let's check by reloading the page data
            alert('Unable to process request. Please refresh the page to check your withdrawal status.');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            refreshBankWithdrawalData();
        });
    }
}

// Success notification function
function showBankSuccessNotification(message) {
    // Check if we already have a notification container
    let notificationContainer = document.getElementById('bank-notification-container');
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'bank-notification-container';
        notificationContainer.style.position = 'fixed';
        notificationContainer.style.top = '20px';
        notificationContainer.style.right = '20px';
        notificationContainer.style.zIndex = '9999';
        document.body.appendChild(notificationContainer);
    }
    
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show';
    notification.style.marginBottom = '10px';
    notification.style.minWidth = '300px';
    notification.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    notification.style.borderRadius = '8px';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <i class="fa fa-check-circle fa-2x text-success"></i>
            </div>
            <div class="flex-grow-1">
                <strong class="d-block">Success!</strong>
                <span>${message}</span>
            </div>
            <button type="button" class="close ml-3" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    notificationContainer.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification && notification.remove) {
            notification.remove();
        }
    }, 5000);
    
    // Also show traditional alert as backup
    alert(message);
}

// Refresh bank withdrawal data (balance and history) without full page reload
function refreshBankWithdrawalData() {
    // Refresh the current page data by reloading the withdrawal history section
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update withdrawal history table if it exists
        const newHistoryTable = doc.querySelector('.table-responsive table');
        const currentHistoryTable = document.querySelector('.table-responsive table');
        if (newHistoryTable && currentHistoryTable) {
            currentHistoryTable.innerHTML = newHistoryTable.innerHTML;
        }
        
        // Update balance display
        const newBalance = doc.querySelector('#bankWithdrawalForm .alert-primary #balance');
        const currentBalance = document.querySelector('#bankWithdrawalForm .alert-primary #balance');
        if (newBalance && currentBalance) {
            currentBalance.textContent = newBalance.textContent;
        }
    })
    .catch(error => {
        console.error('Error refreshing data:', error);
        // If AJAX refresh fails, do a full page reload after 2 seconds
        setTimeout(() => window.location.reload(), 2000);
    });
}

// Reset form function
function resetBankForm(form) {
    // Clear all input fields except submit buttons
    const inputs = form.querySelectorAll('input:not([type="submit"]):not([type="button"])');
    inputs.forEach(input => {
        if (input.type !== 'hidden' && input.id !== 'otp') {
            input.value = '';
        }
    });
    
    // Clear OTP field specifically
    const otpField = form.querySelector('#otp');
    if (otpField) otpField.value = '';
    
    // Reset OTP button
    const otpButton = form.querySelector('.input-group-append .btn-outline-secondary');
    if (otpButton) {
        otpButton.disabled = false;
        otpButton.textContent = 'Request OTP';
    }
    
    // Clear all errors
    clearBankErrors();
    
    // Reset withdrawal summary
    const summaryDiv = document.querySelector('.bank-withdrawal-summary');
    if (summaryDiv) {
        summaryDiv.innerHTML = '<small class="text-muted">Enter amount to see withdrawal summary</small>';
    }
}

// Currency display handler
function setupBankCurrencyDisplay(coinSelect) {
    if (!coinSelect) return;
    
    coinSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const coinSymbol = selectedOption ? selectedOption.getAttribute('data-symbol') : 'BTC';
        
        // Update balance symbol
        const balanceSpan = document.querySelector('#bankWithdrawalForm .alert-primary #balanceSymbol');
        if (balanceSpan) {
            balanceSpan.textContent = coinSymbol.toUpperCase();
        }
        
        // Update amount placeholder
        const amountInput = document.querySelector('#bankWithdrawalForm input[name="amount"]');
        if (amountInput) {
            amountInput.placeholder = `Enter amount in ${coinSymbol.toUpperCase()}`;
        }
        
        // Trigger amount validation to update summary
        if (amountInput && amountInput.value) {
            const event = new Event('input');
            amountInput.dispatchEvent(event);
        }
    });
}

// Saved bank accounts feature
function setupBankSavedAccounts(bankForm) {
    const savedAccounts = JSON.parse(localStorage.getItem('savedBankAccounts') || '[]');
    
    if (savedAccounts.length > 0 && !document.querySelector('.saved-banks-section')) {
        const bankNameGroup = bankForm.querySelector('.form-group:has(input[name="bank_name"])');
        if (bankNameGroup) {
            const savedBanksDiv = document.createElement('div');
            savedBanksDiv.className = 'saved-banks-section mb-3';
            savedBanksDiv.innerHTML = `
                <label>Saved Bank Accounts</label>
                <select class="form-control saved-banks-select" style="color: black !important;">
                    <option value="">-- Select a saved bank account --</option>
                    ${savedAccounts.map((account, index) => `
                        <option value="${index}">${account.bank_name} - ${account.account_name} (${account.account_number})</option>
                    `).join('')}
                </select>
            `;
            
            bankNameGroup.parentNode.insertBefore(savedBanksDiv, bankNameGroup);
            
            const savedBanksSelect = savedBanksDiv.querySelector('.saved-banks-select');
            savedBanksSelect.addEventListener('change', function() {
                const selectedIndex = this.value;
                if (selectedIndex !== '') {
                    const account = savedAccounts[selectedIndex];
                    bankForm.querySelector('input[name="bank_name"]').value = account.bank_name;
                    bankForm.querySelector('input[name="account_name"]').value = account.account_name;
                    bankForm.querySelector('input[name="account_number"]').value = account.account_number;
                    if (account.swift_code && bankForm.querySelector('input[name="swift_code"]')) {
                        bankForm.querySelector('input[name="swift_code"]').value = account.swift_code;
                    }
                    localStorage.setItem('lastUsedBankAccount', JSON.stringify(account));
                }
            });
        }
    }
}

// Helper functions for bank errors
function showBankError(element, message) {
    if (!element) return;
    
    clearBankError(element);
    element.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'bank-field-error text-danger small mt-1';
    errorDiv.textContent = message;
    
    if (element.parentElement.classList.contains('input-group')) {
        element.parentElement.parentElement.appendChild(errorDiv);
    } else {
        element.parentElement.appendChild(errorDiv);
    }
}

function clearBankError(element) {
    if (!element) return;
    element.classList.remove('is-invalid');
    
    const parent = element.parentElement;
    const errorDiv = parent.querySelector('.bank-field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function clearBankErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.bank-field-error').forEach(el => {
        el.remove();
    });
}
</script>

@endsection

{{-- @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const requestBtn = document.getElementById('requestOtpBtn');
    const otpInput = document.getElementById('otp');
    const amountInput = document.getElementById('usdout_num');
    const receiveableEl = document.getElementById('receiveable');
    const balanceEl = document.getElementById('wallet_balance');

    // Request OTP
    requestBtn && requestBtn.addEventListener('click', function () {
        requestBtn.disabled = true;
        fetch("{{ route('wallet.withdraw.sendOtp') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        }).then(r => r.json())
        .then(data => {
            if (data.success) {
                requestBtn.textContent = 'OTP Sent';
                let seconds = 60;
                const t = setInterval(() => {
                    seconds--;
                    requestBtn.textContent = 'Retry ('+seconds+'s)';
                    if (seconds <= 0) {
                        clearInterval(t);
                        requestBtn.disabled = false;
                        requestBtn.textContent = 'Request OTP';
                    }
                }, 1000);
            } else {
                alert(data.message || 'Failed to send OTP');
                requestBtn.disabled = false;
            }
        }).catch(e=>{
            console.error(e);
            alert('Failed to send OTP');
            requestBtn.disabled = false;
        });
    });

    // calculate receivable live (subtract fee)
    amountInput && amountInput.addEventListener('input', function () {
        const val = parseFloat(this.value) || 0;
        // same fee calc as server: 0.1% or 0.001 minimum
        let fee = Math.max(0.001, (val * 0.001));
        const net = Math.max(0, val - fee);
        receiveableEl.textContent = net.toFixed(8);
    });

    // Save address modal
    document.getElementById('saveAddressBtn').addEventListener('click', function () {
        const payload = {
            cryptocurrency_id: document.getElementById('withdrawCyrpto').value,
            address: document.getElementById('wallet_addr').value,
            dest_tag: document.getElementById('wallet_dest_tag').value,
            label: document.getElementById('wallet_name').value,
            network: document.getElementById('selectNetwork').value,
            fund_password: document.getElementById('wallet_paypassword').value,
        };
        fetch("{{ route('wallet.withdraw.addAddress') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        }).then(r => r.json())
        .then(data => {
            if (data.success) {
                // append to addresses dropdown
                const sel = document.getElementById('crypto-address');
                const opt = document.createElement('option');
                opt.value = data.data.address;
                opt.text = data.data.label || data.data.address;
                sel.appendChild(opt);
                $('#addPaymentMethodModal').modal('hide');
                alert('Address added');
            } else {
                alert(data.message || 'Address add failed');
            }
        }).catch(err => {
            console.error(err);
            alert('Address add failed');
        });
    });

});
</script>

@endpush --}}