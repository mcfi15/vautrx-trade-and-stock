<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by auth provider
        if ($request->has('auth_provider') && $request->auth_provider !== '') {
            $query->where('auth_provider', $request->auth_provider);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['wallets.cryptocurrency', 'orders', 'transactions']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Display user wallets.
     */
    public function wallets(User $user)
    {
        $wallets = $user->wallets()->with('cryptocurrency')->get();

        return view('admin.users.wallets', compact('user', 'wallets'));
    }
}
