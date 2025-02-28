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
            'date' => now()->subMonth()->format('Y-m-d'),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 2,
            'date' => now()->subDay()->format('Y-m-d'),
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 2,
            'date' => now()->format('Y-m-d'),
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 3,
            'date' => now()->subMonth()->format('Y-m-d'),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 3,
            'date' => now()->subDay()->format('Y-m-d'),
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 3,
            'date' => now()->format('Y-m-d'),
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 4,
            'date' => now()->subMonth()->format('Y-m-d'),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 4,
            'date' => now()->subDay()->format('Y-m-d'),
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 4,
            'date' => now()->format('Y-m-d'),
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 5,
            'date' => now()->subMonth()->format('Y-m-d'),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);
        
        $param = [
            'user_id' => 5,
            'date' => now()->subDay()->format('Y-m-d'),
            'clock_in' => '08:00:00',
            'clock_out' => '17:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);

        $param = [
            'user_id' => 5,
            'date' => now()->format('Y-m-d'),
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ];
        Attendance::create($param);
    }
}
