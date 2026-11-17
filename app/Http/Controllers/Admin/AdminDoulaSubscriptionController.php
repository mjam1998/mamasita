<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDoulaSubscriptionController extends Controller
{
    public function index()
    {
        return view('admin.doula-subscription.index');
    }
}
