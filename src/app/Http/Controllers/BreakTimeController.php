<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class BreakTimeController extends Controller
{
    public function breakStart() {
        $user = Auth::user();
        $today = today()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $today)->first();
        
        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::now()->toTimeString()
        ]);
        $attendance->update([
            'status' => 'break'
        ]) ;
        return back();
    }
    public function breakEnd() {
        $user = Auth::user();
        $today = today()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $today)->first();
        
        $breakTime = BreakTime::where('attendance_id', $attendance->id)
                              ->whereNull('break_end');

        $breakTime->update([
            'break_end' => Carbon::now()->toTimeString()
        ]);

        $attendance->update([
            'status' => 'finished'
        ]);
        return back();
    }
}
