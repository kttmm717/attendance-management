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
        'reason'
    ];

    // リレーション
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function attendance() {
        return $this->belongsTo(Attendance::class);
    }
    public function correction_breaks() {
        return $this->hasMany(CorrectionBreak::class);
    }
}
