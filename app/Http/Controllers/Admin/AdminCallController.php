<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;

class AdminCallController extends Controller
{
    public function index()
    {
        return view('admin.call.index');
    }

    // در AdminCallController
    public function show(Call $call)
    {
        $call->load('monthlySubscription.user', 'monthlySubscription.mama', 'monthlySubscription.plan');

        return view('admin.call.show', compact('call'));
    }

    public function bySubscription($trackNumber)
    {
        return view('admin.call.index', [
            'trackNumber' => $trackNumber,
        ]);
    }
}
