<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'admin_correction_reason'
    ];
    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime'
    ];

    // リレーション
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function correction_request() {
        return $this->hasOne(CorrectionRequest::class);
    }
    public function break_times() {
        return $this->hasMany(BreakTime::class);
    }
    // メソッド
    //休憩の合計時間を計算
    public function totalBreakTime() { 
        $totalBreakTime = 0;
        $breaks = BreakTime::where('attendance_id', $this->id)->get();

        foreach($breaks as $break) {
            if($break->break_start && $break->break_end) {
                $breakTime = $break->break_start->diffInMinutes($break->break_end);
                $totalBreakTime += $breakTime;
            }            
        }
        return $totalBreakTime;
    }
    //休憩時間をフォーマット
    public function formatedTotalBreakTime() { 
        $totalBreakTime = $this->totalBreakTime();
        $interval = CarbonInterval::minutes($totalBreakTime)->cascade();
        return sprintf('%d:%02d', $interval->hours, $interval->minutes);
    }
    //休憩時間を考慮した勤務時間を計算
    public function totalAttendanceTime() { 
        $clockIn = $this->clock_in;
        $clockOut = $this->clock_out;
        $totalBreakTime = $this->totalBreakTime();

        $attendanceTime = $clockIn->diffInMinutes($clockOut);
        $attendanceTime -= $totalBreakTime;

        $interval = CarbonInterval::minutes($attendanceTime)->cascade();
        return sprintf('%d:%02d', $interval->hours, $interval->minutes);
    }
}
