<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'break_time_id',
        'correction_request_id',
        'new_break_start',
        'new_break_end'
    ];

    protected $casts = [
        'new_break_start' => 'datetime',
        'new_break_end' => 'datetime'
    ];
}
