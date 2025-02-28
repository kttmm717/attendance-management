<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

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
    public function staffAttendance(Request $request, $id)
{
    $user = User::findOrFail($id); // 該当ユーザーがいない場合は404を返す

    // クエリパラメータから `month` を取得（デフォルトは今月）
    $month = $request->query('month', Carbon::now()->format('Y-m'));
    $startOfMonth = Carbon::parse($month)->startOfMonth();
    $endOfMonth = Carbon::parse($month)->endOfMonth();

    // 指定した月の勤怠データを取得
    $attendances = Attendance::where('user_id', $user->id)
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->orderBy('date', 'asc')
        ->get();

    return view('admin.staff.attendance', compact('user', 'attendances', 'month'));
}
}
