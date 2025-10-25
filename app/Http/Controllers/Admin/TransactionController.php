<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'cryptocurrency'])
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by user
        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'cryptocurrency']);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Approve a pending transaction.
     */
    public function approve(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be approved.');
        }

        $transaction->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Transaction approved successfully.');
    }

    /**
     * Reject a pending transaction.
     */
    public function reject(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be rejected.');
        }

        $transaction->update([
            'status' => 'failed',
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Transaction rejected successfully.');
    }
}
