<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
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
            'date' => '2025-01-25',
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 2,
            'date' => '2025-02-25',
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 3,
            'date' => '2025-01-26',
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 3,
            'date' => '2025-02-26',
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 4,
            'date' => '2025-01-27',
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 4,
            'date' => '2025-02-27',
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);
        
        $param = [
            'user_id' => 5,
            'date' => '2025-01-28',
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 5,
            'date' => '2025-02-28',
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);
    }
}
