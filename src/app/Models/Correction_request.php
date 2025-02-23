<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correction_request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'requested_at',
        'status',
        'date',
        'new_clock_in',
        'new_clock_out',
        'new_break_start',
        'new_break_end',
        'reason'
    ];

    // リレーション
    public function user() {
        $this->belongsTo(User::class);
    }
    public function attendance() {
        $this->belongsTo(Attendance::class);
    }
}
