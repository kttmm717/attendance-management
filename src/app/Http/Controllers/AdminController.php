<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\BreakTime;

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
    public function staffAttendance(Request $request, $id) {
    $user = User::findOrFail($id); 

    $month = $request->query('month', Carbon::now()->format('Y-m'));
    $startOfMonth = Carbon::parse($month)->startOfMonth();
    $endOfMonth = Carbon::parse($month)->endOfMonth();

    $attendances = Attendance::where('user_id', $user->id)
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->orderBy('date', 'asc')
        ->get();

    return view('admin.staff.attendance', compact('user', 'attendances', 'month'));
    }
    public function correction($id, AttendanceCorrectionRequest $request) {
        $attendance = Attendance::find($id);
        $break_times = BreakTime::where('attendance_id', $attendance->id)->get();

        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'admin_correction_reason' => $request->reason,
        ]);
        foreach($break_times as $index => $break) {
            if(isset($correction_breaks[$index])) {
                $break->update([
                    'break_start' => $correction_breaks[$index]->new_break_start,
                    'break_end' => $correction_breaks[$index]->new_break_end
                ]);
            }
        }
        return redirect()->route('request', ['tab' => 'approved']);
    }
}
