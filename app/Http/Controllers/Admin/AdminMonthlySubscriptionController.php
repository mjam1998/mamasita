<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlySubscription;
use Illuminate\Http\Request;

class AdminMonthlySubscriptionController extends Controller
{
    public function index()
    {
        return view('admin.monthly-subscription.index');
    }

    public function show(MonthlySubscription $monthlySubscription)
    {
        $monthlySubscription->load([
            'user',
            'mama',
            'plan',
            'ticket',
            'userPay',
        ]);

        return view('admin.monthly-subscription.show', compact('monthlySubscription'));
    }

}
