<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPay;

class AdminUserPayController extends Controller
{
    public function index()
    {
        return view('admin.user-pay.index');
    }

    public function show(UserPay $userPay)
    {
        $userPay->load(['user', 'referenceable']);

        return view('admin.user-pay.show', compact('userPay'));
    }
}
