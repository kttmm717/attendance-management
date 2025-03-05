<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
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

        $response = new StreamedResponse(function () use ($attendances) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, mb_convert_encoding(['日付', '出勤', '退勤', '休憩', '合計'], 'SJIS-WIN', 'UTF-8'));

            foreach ($attendances as $attendance) {
                fputcsv($handle, mb_convert_encoding([
                    $attendance->date->format('Y-m-d'),
                    $attendance->clock_in ? $attendance->clock_in->format('H:i') : '出勤中',
                    $attendance->clock_out ? $attendance->clock_out->format('H:i') : '出勤中',
                    $attendance->formatedTotalBreakTime(),
                    $attendance->totalAttendanceTime(),
                ], 'SJIS-WIN', 'UTF-8'));
            }

            fclose($handle);
        });

        $safeUserName = str_replace([' ', '　'], '_', $user->name);
        $fileName = "attendance_{$safeUserName}_{$month}.csv";

        $response->headers->set('Content-Type', 'text/csv; charset=Shift_JIS');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$fileName\"");

        return $response;
    }
}
