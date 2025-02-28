<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\CorrectionBreak;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(AttendanceSeeder::class);
        $this->call(BreakTimeSeeder::class);
        $this->call(CorrectionRequestSeeder::class);
        $this->call(CorrectionBreakSeeder::class);
    }
}
