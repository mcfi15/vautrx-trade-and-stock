<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiningPool;
use App\Models\UserMiningMachine;
use App\Models\MiningReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoolController extends Controller
{
    public function index()
    {
        $pools = MiningPool::withCount(['userMachines', 'rewards'])->get();
        $totalRevenue = UserMiningMachine::sum('total_cost');
        $activeMachines = UserMiningMachine::where('is_active', true)->count();
        $totalRewards = MiningReward::sum('amount');
        
        return view('admin.pools.index', compact('pools', 'totalRevenue', 'activeMachines', 'totalRewards'));
    }

    public function create()
    {
        return view('admin.pools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'total' => 'required|integer|min:1',
            'daily_reward' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'user_limit' => 'nullable|integer|min:0',
            'power' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        MiningPool::create([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'price' => $request->price,
            'total' => $request->total,
            'available' => $request->total,
            'daily_reward' => $request->daily_reward,
            'duration_days' => $request->duration_days,
            'user_limit' => $request->user_limit ?? 0,
            'power' => $request->power,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.pools.index')->with('success', 'Mining pool created successfully.');
    }

    public function edit(MiningPool $pool)
    {
        return view('admin.pools.edit', compact('pool'));
    }

    public function update(Request $request, MiningPool $pool)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'total' => 'required|integer|min:1',
            'daily_reward' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'user_limit' => 'nullable|integer|min:0',
            'power' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Calculate new available count
        $soldCount = $pool->total - $pool->available;
        $newAvailable = max(0, $request->total - $soldCount);

        $pool->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'price' => $request->price,
            'total' => $request->total,
            'available' => $newAvailable,
            'daily_reward' => $request->daily_reward,
            'duration_days' => $request->duration_days,
            'user_limit' => $request->user_limit ?? 0,
            'power' => $request->power,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.pools.index')->with('success', 'Mining pool updated successfully.');
    }

    public function destroy(MiningPool $pool)
    {
        if ($pool->userMachines()->exists()) {
            return back()->with('error', 'Cannot delete pool with active mining machines.');
        }

        $pool->delete();
        return back()->with('success', 'Mining pool deleted successfully.');
    }

    public function machines()
    {
        $machines = UserMiningMachine::with(['user', 'miningPool'])->latest()->get();
        return view('admin.pools.machines', compact('machines'));
    }

    public function rewards()
    {
        $rewards = MiningReward::with(['user', 'miningPool'])->latest()->get();
        return view('admin.pools.rewards', compact('rewards'));
    }
}