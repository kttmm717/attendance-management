<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function adminView() {
        return view('admin.attendance.index');
    }
    public function staffView() {
        return view('attendance.create');
    }
}
