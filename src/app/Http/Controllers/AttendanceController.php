<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function staffView() {
        $user = Auth::user();
        return view('attendance.create', compact('user'));
    }
    public function clockIn() {
        $user = Auth::user();
        
        if($user->todayAttendance) {
            return back()->with('error', '今日はすでに出勤しています。');
        }
        
        Attendance::create([
            'user_id' => $user->id,
            'date' => today()->toDateString(),
            'clock_in' => Carbon::now()->toTimeString(),
            'status' => 'working'
        ]);
        return back();
    }
    public function clockOut() {
        $user = Auth::user();
        $today = today()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $today)->first();
        
        $attendance->update([
            'clock_out' => Carbon::now()->toTimeString(),
            'status' => 'off'
        ]);
        return back();
    }
    public function index() {
        $user = Auth::user();
        $attendances = Attendance::where('user_id', $user->id)
                                ->whereMonth('date', now()->month)
                                ->whereYear('date', now()->year)
                                ->get();

        return view('attendance.index', compact('user', 'attendances'));
    }
    public function detail($id) {
        $attendance = Attendance::find($id);
        return view('attendance.show', compact('attendance'));
    }
}
