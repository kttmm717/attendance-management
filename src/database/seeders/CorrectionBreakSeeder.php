<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CorrectionBreak;

class CorrectionBreakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'correction_request_id' => 4,
            'new_break_start' => '11:00:00',
            'new_break_end' => '13:00:00'
        ];
        CorrectionBreak::create($param);
    }
}
