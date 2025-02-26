<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Correction_request;
use App\Models\Attendance;
use App\Models\CorrectionBreak;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function request($id, Request $request) {
        $attendance = Attendance::find($id);

        $correctionRequest = Correction_request::create([
            'user_id' => Auth::user()->id,
            'attendance_id' => $id,
            'requested_at' => now(),
            'status' => 'pending',
            'date' => $attendance->date,
            'new_clock_in' => $request->clock_in,
            'new_clock_out' => $request->clock_out,
            'reason' => $request->reason
        ]);
        foreach ($request->break_times as $break_time) {
            if (
                $break_time['break_start'] !== $break_time['original_break_start'] ||
                $break_time['break_end'] !== $break_time['original_break_end']
            ) {
                CorrectionBreak::create([
                    'correction_request_id' => $correctionRequest->id,
                    'new_break_start' => $break_time['break_start'],
                    'new_break_end' => $break_time['break_end'],
                ]);
            }
        }        
        return redirect()->back();
    }

    public function requestList() {
        $user = Auth::user();
        return view('requests.index', compact('user'));
    }
}
