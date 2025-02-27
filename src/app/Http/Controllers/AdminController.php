<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request) {
        $date = $request->query('date', now()->format('Y-m-d'));
        $attendances = Attendance::whereDate('date', $date)->get();
        return view('admin.attendance.index', compact('attendances'));
    }
    public function staffList() {
        $users = User::where('role', 'staff')->get();
        return view('admin.staff.index', compact('users'));
    }
    public function attendance($id) {
        $attendance = Attendance::find($id);
        return view('admin.attendance.show', compact('attendance'));
    }
}
