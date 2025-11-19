<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaucetLog;
use Illuminate\Http\Request;

class FaucetLogController extends Controller
{
    public function index()
    {
        $logs = FaucetLog::with('user','faucet')->latest()->paginate(50);
        return view('admin.faucets.logs', compact('logs'));
    }
}
