<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoulaSubscription;
use Illuminate\Http\Request;

class AdminDoulaSubscriptionController extends Controller
{
    public function index()
    {
        return view('admin.doula-subscription.index');
    }

    public function show(DoulaSubscription $doulaSubscription)
    {
        $doulaSubscription->load([
            'user',
            'mama',
            'plan',
            'province',
            'city',
            'userPay',
            'successfulUserPay',
        ]);

        return view('admin.doula-subscription.show', compact('doulaSubscription'));
    }

}
