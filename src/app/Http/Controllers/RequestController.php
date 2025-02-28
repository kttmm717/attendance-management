<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\CorrectionBreak;
use Illuminate\Support\Facades\Auth;
use App\Models\CorrectionRequest;


class RequestController extends Controller
{
    public function request($id, Request $request) {
        $attendance = Attendance::find($id);

        $correctionRequest = CorrectionRequest::create([
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

    public function requestList(Request $request) {
        $user = Auth::user();
        $tab = $request->query('tab', 'pending');
        $query = CorrectionRequest::query();
        
        if ($user->role === 'admin') {
            $correction_requests = $query->where('status', $tab)->get();
        } else {
            $correction_requests = $query->where('status', $tab)
                                        ->where('user_id', $user->id)
                                        ->get();
        }
        return view('requests.index', compact('user', 'correction_requests'));
    }

    public function requestDetail($attendance_correct_request) {
        $correction_request = CorrectionRequest::find($attendance_correct_request);
        $correction_breaks = CorrectionBreak::where('correction_request_id', $correction_request->id)->get();
        $user = $correction_request->user;
        return view('admin.requests.approval', compact('correction_request','correction_breaks', 'user'));
    }

    public function approve($attendance_correct_request) {
        $correction_request = CorrectionRequest::find($attendance_correct_request);
        $correction_request->update([
            'status' => 'approved'
        ]);
        return redirect('back');
    }
}
