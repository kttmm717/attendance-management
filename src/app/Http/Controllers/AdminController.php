<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Requests\AttendanceCorrectionRequest;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;
use App\Models\CorrectionBreak;

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

    // 管理者が直接勤怠を修正
    public function correction($id, AttendanceCorrectionRequest $request) {
        // 対象の勤怠取得
        $attendance = Attendance::find($id);
        // 上の勤怠に紐づいてる休憩レコード取得
        $break_times = BreakTime::where('attendance_id', $attendance->id)->get();

        // ステータスを承認済みとして一旦修正申請テーブルに保存
        $correction_request = CorrectionRequest::create([
            'user_id' => $attendance->user_id,
            'attendance_id' => $id,
            'requested_at' => now(),
            'status' => 'approved',
            'date' => $attendance->date,
            'new_clock_in' => $request->clock_in,
            'new_clock_out' => $request->clock_out,
            'reason' => $request->reason
        ]);
        // 上の修正に紐づいてる休憩も一旦休憩申請テーブルに保存
        foreach ($request->break_times as $break_time) {
            if (
                $break_time['break_start'] !== $break_time['original_break_start'] ||
                $break_time['break_end'] !== $break_time['original_break_end']
            ) {
                $correction_breaks[] = CorrectionBreak::create([
                    'break_time_id' => $break_time['id'],
                    'correction_request_id' => $correction_request->id,
                    'new_break_start' => $break_time['break_start'],
                    'new_break_end' => $break_time['break_end'],
                ]);
            }
        }
        // 元々の対象の勤怠を、修正申請テーブルに保存した新しい勤怠に更新
        $attendance->update([
            'clock_in' => $correction_request->new_clock_in,
            'clock_out' => $correction_request->new_clock_out,
        ]);

        // 元々の勤怠に紐づいてる休憩レコードを、休憩申請テーブルに保存した新しい休憩に更新        
        $correction_breaks = collect($correction_breaks);// 配列になっているので一旦コレクションに変換

        foreach ($break_times as $break_time) { //元々の休憩レコードをループ
            $correction_break = $correction_breaks->firstWhere('break_time_id', $break_time->id);
            //$correction_breaksテーブルのbreak_time_idカラムと$break_timeのidが一致したら変数$correction_breakに格納
            if ($correction_break) {
                $break_time->update([
                    'break_start' => $correction_break->new_break_start,
                    'break_end' => $correction_break->new_break_end
                ]);
            }
        }
        return redirect()->route('request', ['tab' => 'approved']);
    }
}
