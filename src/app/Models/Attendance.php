<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'status'
    ];

    // リレーション
    public function user() {
        $this->belongsTo(User::class);
    }
    public function correction_request() {
        $this->hasOne(Correction_request::class);
    }
}
