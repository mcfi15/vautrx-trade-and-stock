@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
<style>
/* Make the table use fixed layout so the widths below are respected */
.table-fixed-layout {
  table-layout: fixed;
  width: 100%;
}

/* Prevent wrapping and show ellipsis so long content won't widen columns */
.table-fixed-layout th,
.table-fixed-layout td {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  vertical-align: middle;
}

/* Optional: stronger visual header weight */
.table-fixed-layout thead th {
  font-weight: 600;
}

/* Explicit column widths — adjust percentages to taste so they add to ~100% */
.table-fixed-layout thead th:nth-child(1) { width: 10%; } /* Type */
.table-fixed-layout thead th:nth-child(2) { width: 18%; } /* Cryptocurrency */
.table-fixed-layout thead th:nth-child(3) { width: 18%; } /* Amount */
.table-fixed-layout thead th:nth-child(4) { width: 10%; } /* Fee */
.table-fixed-layout thead th:nth-child(5) { width: 10%; } /* Status */
.table-fixed-layout thead th:nth-child(6) { width: 16%; } /* Date */
.table-fixed-layout thead th:nth-child(7) { width: 18%; } /* Actions */

/* If you keep .table-responsive wrapper, ensure it doesn't force different sizing */
.table-responsive > .table-fixed-layout {
  margin-bottom: 0;
}
</style>

<div class="container">

    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 font-weight-bold text-dark">Transaction History</h1>
        <p class="text-muted">View all your wallet transactions</p>
    </div>

    <!-- Filters -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Filters</h5>

            <form method="GET" class="row">

                <div class="form-group col-md-3">
                    <label>Transaction Type</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                        <option value="withdrawal" {{ request('type') === 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                        <option value="trade" {{ request('type') === 'trade' ? 'selected' : '' }}>Trade</option>
                        <option value="transfer" {{ request('type') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Cryptocurrency</label>
                    <select name="crypto" class="form-control">
                        <option value="">All Cryptocurrencies</option>
                        @foreach($cryptocurrencies ?? [] as $crypto)
                            <option value="{{ $crypto->id }}" {{ request('crypto') == $crypto->id ? 'selected' : '' }}>
                                {{ $crypto->name }} ({{ strtoupper($crypto->symbol) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="form-group col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fa fa-filter mr-2"></i> Apply Filters
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="m-0">Transactions</h5>
        </div>

        @if($transactions->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0 ">
                    <thead class="thead-light">
                        <tr>
                            <th>Type</th>
                            <th>Cryptocurrency</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr >
                            <td style="padding-left: 10px;">
                                <span class="badge badge-pill
                                    @if($transaction->type=='deposit') badge-success
                                    @elseif($transaction->type=='withdrawal') badge-danger
                                    @elseif($transaction->type=='trade') badge-info
                                    @else badge-secondary @endif">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>

                            <td style="padding-left: 10px;">
                                <strong>{{ $transaction->cryptocurrency->symbol ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $transaction->cryptocurrency->name ?? 'Unknown' }}</small>
                            </td>

                            <td style="padding-left: 10px;">
                                <span class="{{ $transaction->type=='deposit' ? 'text-success' : 'text-danger' }} font-weight-bold">
                                    {{ $transaction->type=='deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 8) }}
                                </span><br>
                                <small class="text-muted">
                                    ${{ number_format($transaction->amount * ($transaction->cryptocurrency->current_price ?? 0), 2) }}
                                </small>
                            </td>

                            <td style="padding-left: 10px;">{{ number_format($transaction->fee ?? 0, 8) }}</td>

                            <td style="padding-left: 10px;">
                                <span class="badge
                                    @if($transaction->status=='completed') badge-success
                                    @elseif($transaction->status=='pending') badge-warning
                                    @elseif($transaction->status=='failed') badge-danger
                                    @else badge-secondary @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>

                            <td style="padding-left: 10px;">
                                <small>{{ $transaction->created_at->format('M j, Y') }}</small><br>
                                <small class="text-muted">{{ $transaction->created_at->format('g:i A') }}</small>
                            </td>

                            <td style="padding-left: 10px;">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewTransaction({{ $transaction->id }})">
                                    <i class="fa fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

            <div class="card-footer">
                {{ $transactions->appends(request()->query())->links() }}
            </div>

        @else
            <div class="text-center p-5">
                <i class="fa fa-history text-secondary" style="font-size:50px"></i>
                <h5 class="mt-3">No Transactions Found</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['type','status','search','date_from','date_to']))
                        No transactions match your filters.
                    @else
                        You haven't made any transactions yet.
                    @endif
                </p>

                @if(request()->hasAny(['type','status','search','date_from','date_to']))
                    <a href="{{ route('wallet.transactions') }}" class="text-primary font-weight-bold">Clear Filters</a>
                @endif
            </div>
        @endif
    </div>

</div>

<!-- Transaction Detail Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="transactionDetails"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<script>
function viewTransaction(transactionId) {
    document.getElementById('transactionDetails').innerHTML = `
        <div class="text-center py-4">
            <i class="fa fa-spinner fa-spin fa-2x text-muted mb-3"></i>
            <p class="text-muted">Loading transaction details...</p>
        </div>
    `;

    $('#transactionModal').modal('show');

    fetch(`/wallet/transaction/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('transactionDetails').innerHTML = `
                <ul class="list-group text-left">
                    <li class="list-group-item"><strong>Type:</strong> ${data.type}</li>
                    <li class="list-group-item"><strong>Amount:</strong> ${data.amount}</li>
                    <li class="list-group-item"><strong>Fee:</strong> ${data.fee}</li>
                    <li class="list-group-item"><strong>Status:</strong> ${data.status}</li>
                    <li class="list-group-item"><strong>Crypto:</strong> ${data.crypto} (${data.crypto_name})</li>
                    <li class="list-group-item"><strong>Date:</strong> ${data.date}</li>
                    ${data.tx_hash ? `<li class="list-group-item"><strong>Tx Hash:</strong> ${data.tx_hash}</li>` : ''}
                </ul>
            `;
        })
        .catch(() => {
            document.getElementById('transactionDetails').innerHTML =
                `<div class="alert alert-danger">Failed to load transaction details.</div>`;
        });
}
</script>
@endsection
