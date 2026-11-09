<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineVisit;
use Illuminate\Http\Request;

class AdminOnlineVisitController extends Controller
{
    public function index()
    {
        return view('admin.online-visit.index');
    }

    public function show(OnlineVisit $onlineVisit)
    {
        $onlineVisit->load([
            'user',
            'mama',
            'plan',
            'ticket',
            'successfulUserPay',
            'userPay' => fn ($q) => $q->latest(),
        ]);

        return view('admin.online-visit.show', compact('onlineVisit'));
    }
}
