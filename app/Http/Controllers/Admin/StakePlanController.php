<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StakePlan;
use App\Models\Cryptocurrency;

class StakePlanController extends Controller
{
    public function index()
    {
        $plans = StakePlan::with('cryptocurrency')->get();
        return view('admin.stake_plans.index', compact('plans'));
    }

    public function create()
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.stake_plans.create', compact('coins'));
    }

 public function store(Request $request)
{
    // Normalize the checkbox to boolean
    $request->merge([
        'is_active' => $request->has('is_active') ? true : false,
    ]);

    // Convert lock_periods_raw string to array
    $durations = array_filter(array_map('intval', explode(',', $request->lock_periods_raw)));
    $request->merge(['durations' => $durations]);

    // Validation
    $request->validate([
        'name' => 'required|string',
        'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
        'percent' => 'required|numeric|between:0,100',
        'durations' => 'required|array|min:1',
        'durations.*' => 'integer',
        'min_amount' => 'required|numeric',
        'is_active' => 'required|boolean',
    ]);

    // Create the stake plan
    StakePlan::create([
        'name' => $request->name,
        'cryptocurrency_id' => $request->cryptocurrency_id,
        'percent' => $request->percent,
        'durations' => $request->durations, // this matches migration now
        'min_amount' => $request->min_amount,
        'is_active' => $request->is_active,
    ]);

    return redirect('admin/stake-plans')->with('success', 'Stake plan created.');
}



    public function edit(StakePlan $stakePlan)
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.stake_plans.edit', compact('stakePlan','coins'));
    }

    public function update(Request $request, StakePlan $stakePlan)
    {
        $request->validate([
            'name' => 'required|string',
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'percent' => 'required|numeric',
            'lock_periods' => 'required|array',
            'lock_periods.*' => 'integer',
            'min_amount' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $stakePlan->update([
            'name' => $request->name,
            'cryptocurrency_id' => $request->cryptocurrency_id,
            'percent' => $request->percent,
            'lock_periods' => $request->lock_periods,
            'min_amount' => $request->min_amount,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.stake-plans.index')->with('success', 'Stake plan updated.');
    }

    public function destroy(StakePlan $stakePlan)
    {
        $stakePlan->delete();
        return back()->with('success', 'Stake plan removed.');
    }
}
