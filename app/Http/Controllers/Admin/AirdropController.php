<?php

namespace App\Http\Controllers\Admin;

use App\Models\Airdrop;
use Illuminate\Http\Request;
use App\Models\Cryptocurrency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AirdropController extends Controller
{
    public function index()
    {
        $airdrops = Airdrop::latest()->paginate(20);
        return view('admin.airdrops.index', compact('airdrops'));
    }

    public function create()
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.airdrops.create', compact('coins'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $request->validate([
            'title'=>'required|string|max:255',
            'holding_currency_id'=>'nullable|exists:cryptocurrencies,id',
            'min_hold_amount'=>'nullable|numeric|min:0',
            'airdrop_currency_id'=>'required|exists:cryptocurrencies,id',
            'airdrop_amount'=>'required|numeric|min:0',
            'start_at'=>'nullable|date',
            'end_at'=>'nullable|date|after_or_equal:start_at',
            'image'=>'nullable|image|max:4096',
            'description'=>'nullable|string',
            'is_active'=>'nullable|boolean',
        ]);

        $data = $request->only([
            'title','holding_currency_id','min_hold_amount','airdrop_currency_id',
            'airdrop_amount','start_at','end_at','description'
        ]);
        // $data['is_active'] = $request->has('is_active');

        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/airdrop/',$filename);
            $data['image'] = "uploads/airdrop/$filename";
        }

        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $name = time().'.'.$file->getClientOriginalExtension();
        //     $file->move(public_path('uploads/airdrops'), $name);
        //     $data['image'] = 'uploads/airdrops/' . $name;
        // }

        Airdrop::create($data);

        return redirect()->route('admin.airdrops.index')->with('success','Airdrop created.');
    }

    public function edit(Airdrop $airdrop)
    {
        $coins = Cryptocurrency::active()->get();
        return view('admin.airdrops.edit', compact('airdrop','coins'));
    }

    public function update(Request $request, Airdrop $airdrop)
    {
        $request->merge([
            'is_active' => $request->has('is_active') ? true : false,
        ]);
        $request->validate([
            'title'=>'required|string|max:255',
            'holding_currency_id'=>'nullable|exists:cryptocurrencies,id',
            'min_hold_amount'=>'nullable|numeric|min:0',
            'airdrop_currency_id'=>'required|exists:cryptocurrencies,id',
            'airdrop_amount'=>'required|numeric|min:0',
            'start_at'=>'nullable|date',
            'end_at'=>'nullable|date|after_or_equal:start_at',
            'image'=>'nullable|image|max:4096',
            'description'=>'nullable|string',
            'is_active'=>'nullable|boolean',
        ]);

        $data = $request->only([
            'title','holding_currency_id','min_hold_amount','airdrop_currency_id',
            'airdrop_amount','start_at','end_at','description'
        ]);
        // $data['is_active'] = $request->has('is_active');

        if($request->hasFile('image')){

            $path = $airdrop->image;
            if(File::exists($path)){
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/airdrop/',$filename);
            $airdrop->image = "uploads/airdrop/$filename";
        }

        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $name = time().'.'.$file->getClientOriginalExtension();
        //     $file->move(public_path('uploads/airdrops'), $name);
        //     $data['image'] = 'uploads/airdrops/' . $name;
        // }

        $airdrop->update($data);

        return redirect()->route('admin.airdrops.index')->with('success','Airdrop updated.');
    }

    public function destroy(Airdrop $airdrop)
    {
        $airdrop->delete();
        return redirect()->route('admin.airdrops.index')->with('success','Airdrop deleted.');
    }
}
