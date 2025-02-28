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
    public function index(Request $request) {
    $user = Auth::user();
    $currentMonth = $request->query('month', now()->format('Y-m'));

    try {
        $currentMonth = Carbon::createFromFormat('Y-m', $currentMonth);
    } catch (\Exception $e) {
        abort(404);
    }
    $attendances = Attendance::where('user_id', $user->id)
        ->whereYear('date', $currentMonth->year)
        ->whereMonth('date', $currentMonth->month)
        ->get();

    return view('attendance.index', compact('user', 'attendances', 'currentMonth'));
    }

    public function detail($id) {
        $attendance = Attendance::find($id);
        return view('attendance.show', compact('attendance'));
    }
}
