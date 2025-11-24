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
                        <li>
                            <a class="btn btn-primary btn-sm" >Withdrawal History</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <ul id="withdraw-money-tabs" class="nav nav-pills" role="tablist">
                    <li class="nav-item white-bg">
                        <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#crypto" data-target=".crypto" role="tab">Crypto</a>
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Dest Tag [if any]</label> 
                                    <input type="text" id="wallet_dest_tag" class="form-control" placeholder="Enter the Dest tag if any">
                                </div>
                            </div>
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