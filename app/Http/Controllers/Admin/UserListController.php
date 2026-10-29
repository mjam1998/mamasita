<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    public function allUsers()
    {
        return view('admin.users-list.all-users');
    }

    public function allMama()
    {
        return view('admin.users-list.mama-list');
    }
}
