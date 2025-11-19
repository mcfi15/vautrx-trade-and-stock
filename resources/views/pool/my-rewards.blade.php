@extends('layouts.app')

@section('title', 'My Mining Rewards')

@section('content')
<div class="container">
    <div class="row mt-3 mb-3">
        <div class="col-12">
            <h2>My Mining Rewards</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Pool</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Paid At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rewards as $reward)
                            <tr>
                                <td>{{ $reward->miningPool->name }}</td>
                                <td>{{ $reward->amount }} LTC</td>
                                <td>{{ $reward->reward_date }}</td>
                                <td>
                                    <span class="badge badge-{{ $reward->is_paid ? 'success' : 'warning' }}">
                                        {{ $reward->is_paid ? 'Paid' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $reward->paid_at ? $reward->paid_at->format('Y-m-d H:i') : '-' }}</td>
                                
                                <td>
                                    @if(!$reward->is_paid)
                                    <form action="{{ route('pool.claimReward', $reward) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Claim</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection