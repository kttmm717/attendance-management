<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BreakTime;

class BreakTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'attendance_id' => 1,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

        $param = [
            'attendance_id' => 2,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

        $param = [
            'attendance_id' => 3,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

        $param = [
            'attendance_id' => 4,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

        $param = [
            'attendance_id' => 5,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

        $param = [
            'attendance_id' => 6,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

        $param = [
            'attendance_id' => 7,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

        $param = [
            'attendance_id' => 8,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00'
        ];
        BreakTime::create($param);

    }
}
