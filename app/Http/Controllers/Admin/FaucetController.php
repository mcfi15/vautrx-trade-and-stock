<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faucet;
use App\Models\Cryptocurrency;
use Illuminate\Http\Request;

class FaucetController extends Controller
{
    public function index()
    {
        $faucets = Faucet::latest()->paginate(20);
        return view('admin.faucets.index', compact('faucets'));
    }

    public function create()
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.faucets.create', compact('coins'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'amount' => 'required|numeric|min:0.00000001',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'cooldown_seconds' => 'nullable|integer|min:0',
            'max_claims_per_user' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:4096',
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/faucets'), $name);
            $data['image'] = 'uploads/faucets/'.$name;
        }

        Faucet::create($data);
        return redirect()->route('admin.faucets.index')->with('success','Faucet created.');
    }

    public function edit(Faucet $faucet)
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.faucets.edit', compact('faucet','coins'));
    }

    public function update(Request $request, Faucet $faucet)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'amount' => 'required|numeric|min:0.00000001',
            'description' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'cooldown_seconds' => 'nullable|integer|min:0',
            'max_claims_per_user' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|max:4096',
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/faucets'), $name);
            $data['image'] = 'uploads/faucets/'.$name;
        }

        $faucet->update($data);
        return redirect()->route('admin.faucets.index')->with('success','Faucet updated.');
    }

    public function destroy(Faucet $faucet)
    {
        $faucet->delete();
        return redirect()->route('admin.faucets.index')->with('success','Faucet deleted.');
    }
}
