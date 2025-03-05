<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;

class ExportController extends Controller
{
    public function export(Request $request) {
        $month = $request->query('month');
        $userId = $request->query('user_id');
        $user = User::findOrFail($userId);
    
        $attendances = Attendance::where('user_id', $userId)
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->get();
    
        $csvData = fopen('php://temp', 'r+');
        
        fputcsv($csvData, mb_convert_encoding(['日付', '出勤', '退勤', '休憩', '合計'], 'SJIS-WIN', 'UTF-8'));
    
        foreach ($attendances as $attendance) {
            fputcsv($csvData, mb_convert_encoding([
                $attendance->date->format('Y-m-d'),
                $attendance->clock_in ? $attendance->clock_in->format('H:i') : '',
                $attendance->clock_out ? $attendance->clock_out->format('H:i') : '',
                $attendance->formatedTotalBreakTime(),
                $attendance->totalAttendanceTime(),
            ], 'SJIS-WIN', 'UTF-8'));
        }
    
        rewind($csvData);
        $csvContent = stream_get_contents($csvData);
        fclose($csvData);
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=Shift_JIS')
            ->header('Content-Disposition', 'attachment; filename="attendance_' . $user->name . '_' . $month . '.csv"');
    }
}
