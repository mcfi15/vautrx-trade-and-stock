<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Cryptocurrency;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('admin.payment-methods.index', [
            'methods' => PaymentMethod::with('cryptocurrency')->get()
        ]);
    }

    public function create()
    {
        return view('admin.payment-methods.create', [
            'cryptos' => Cryptocurrency::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        PaymentMethod::create($request->all());

        return redirect()->route('admin.payment-methods')
            ->with('success', 'Payment method created.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', [
            'method' => $paymentMethod,
            'cryptos' => Cryptocurrency::all(),
        ]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'cryptocurrency_id' => 'required|exists:cryptocurrencies,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $paymentMethod->update($request->all());

        return redirect()->route('admin.payment-methods')
            ->with('success', 'Payment method updated.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return back()->with('success', 'Payment method deleted.');
    }
}
