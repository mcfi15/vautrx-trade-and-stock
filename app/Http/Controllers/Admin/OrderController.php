<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'tradingPair.baseCurrency', 'tradingPair.quoteCurrency'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by side
        if ($request->has('side')) {
            $query->where('side', $request->side);
        }

        // Search by user email
        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
{
    $order->load(['user', 'tradingPair.baseCurrency', 'tradingPair.quoteCurrency', 'trades']);

    return view('admin.orders.show', compact('order'));
}

public function cancel(Order $order)
{
    try {
        $order->cancel();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order has been cancelled successfully.');
            
    } catch (\Exception $e) {
        return redirect()->route('admin.orders.show', $order)
            ->with('error', 'Failed to cancel order: ' . $e->getMessage());
    }
}

public function complete(Order $order)
{
    try {
        $order->complete();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order has been marked as completed successfully.');
            
    } catch (\Exception $e) {
        return redirect()->route('admin.orders.show', $order)
            ->with('error', 'Failed to complete order: ' . $e->getMessage());
    }
}
}
