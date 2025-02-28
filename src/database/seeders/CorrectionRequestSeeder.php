<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CorrectionRequest;

class CorrectionRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 2,
            'attendance_id' => 1,
            'requested_at' => now()->format('Y-m-d H:i'),
            'status' => 'pending',
            'date' => now()->subMonth(),
            'new_clock_in' => '10:00:00',
            'new_clock_out' => '17:00:00',
            'reason' => '遅刻のため'
        ];
        CorrectionRequest::create($param);

        $param = [
            'user_id' => 3,
            'attendance_id' => 5,
            'requested_at' => now()->format('Y-m-d H:i'),
            'status' => 'pending',
            'date' => now()->subDay(),
            'new_clock_in' => '09:00:00',
            'new_clock_out' => '17:00:00',
            'reason' => '遅刻のため'
        ];
        CorrectionRequest::create($param);

        $param = [
            'user_id' => 4,
            'attendance_id' => 7,
            'requested_at' => now()->format('Y-m-d H:i'),
            'status' => 'approved',
            'date' => now()->subMonth(),
            'new_clock_in' => '10:00:00',
            'new_clock_out' => '18:00:00',
            'reason' => '遅刻のため'
        ];
        CorrectionRequest::create($param);

        $param = [
            'user_id' => 5,
            'attendance_id' => 11,
            'requested_at' => now()->format('Y-m-d H:i'),
            'status' => 'pending',
            'date' => now()->subDay(),
            'new_clock_in' => '08:00:00',
            'new_clock_out' => '17:00:00',
            'reason' => '休憩2時間取得したため'
        ];
        CorrectionRequest::create($param);
    }
}
