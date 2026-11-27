<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseOrder;

class AdminCourseOrderController extends Controller
{
    public function index()
    {
        return view('admin.course-order.index');
    }

    public function show(CourseOrder $courseOrder)
    {
        $courseOrder->load([
            'user',
            'course',
            'userPay',
            'successfulUserPay',
            'spotPlayerLicence',
        ]);

        return view('admin.course-order.show', compact('courseOrder'));
    }
}
