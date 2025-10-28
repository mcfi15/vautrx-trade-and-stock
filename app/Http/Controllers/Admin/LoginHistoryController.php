<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginHistory::with('user')
            ->latest('login_at');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('success')) {
            $query->where('success', $request->boolean('success'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('login_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('login_at', '<=', $request->date_to);
        }

        $loginHistories = $query->paginate(50);
        $users = User::select('id', 'name', 'email')->get();

        return view('admin.login-history.index', compact('loginHistories', 'users'));
    }

    public function show($id)
    {
        $loginHistory = LoginHistory::with('user')->findOrFail($id);

        return view('admin.login-history.show', compact('loginHistory'));
    }
}