@extends('layouts.app')

@section('title', 'My Mining Machines')

@section('content')
<div class="container">
    <div class="row mt-3 mb-3">
        <div class="col-12">
            <h2>My Mining Machines</h2>
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
                                <th>Quantity</th>
                                <th>Total Cost</th>
                                <th>Daily Reward</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Remaining Days</th>
                                <th>Status</th>
                                <th>Total Earned</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($machines as $machine)
                            <tr>
                                <td>{{ $machine->miningPool->name }}</td>
                                <td>{{ $machine->quantity }}</td>
                                <td>{{ $machine->total_cost }} LTC</td>
                                <td>{{ $machine->daily_reward }} LTC</td>
                                <td>{{ $machine->start_date->format('Y-m-d') }}</td>
                                <td>{{ $machine->end_date->format('Y-m-d') }}</td>
                                <td>{{ $machine->remaining_days }}</td>
                                <td>
                                    <span class="badge badge-{{ $machine->is_active ? 'success' : 'secondary' }}">
                                        {{ $machine->status }}
                                    </span>
                                </td>
                                <td>{{ $machine->total_earned }} LTC</td>
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