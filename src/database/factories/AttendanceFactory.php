<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\User;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $clockIn = $this->faker->time('H:i:s');
        $clockOut = Carbon::parse($clockIn)->addHours(rand(4,10))->format('H:i:s');
        $status = $this->faker->randomElement(['working', 'break', 'finished', 'off']);      

        return [
            'user_id' => User::factory(),
            'date' => Carbon::today()->format('Y-m-d'),
            'clock_in' => $clockIn,
            'clock_out' => $status === 'off' ? $clockOut : null,
            'status' => $status,
        ];
    }
}
