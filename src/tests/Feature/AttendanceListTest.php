<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 自分が行った勤怠全て表示されているか
    public function test_my_attendances() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendances = Attendance::factory()->count(5)->create([
            'user_id' => $user->id,
            'clock_in' => ($clockIn = Carbon::now()->format('H:i:s')),
            'clock_out' => Carbon::parse($clockIn)->addHours(rand(4,10))->format('H:i:s'),
            'status' => 'off',            
        ])->each(function($attendance, $index) {
            $attendance->date = Carbon::today()->startOfMonth()->addDays($index);            
            $attendance->save();
        });        
        
        /**@var User $user */
        $response = $this->actingAs($user)->get('/attendance/list');

        foreach($attendances as $attendance) {
            $response->assertSee($attendance->clock_in->format('H:i'));
            $response->assertSee($attendance->clock_out->format('H:i'));
        }        
    }

    // 勤怠一覧画面に遷移した際に現在の月が表示されるか
    public function test_current_month_displayed() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/attendance/list');
        $response->assertSee(Carbon::now()->format('Y/m'));
    }

    // 「前月」を押下したときに表示月の前月の情報が表示されるか
    public function test_previous_month_display() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/attendance/list');
        $response->assertSee($nowMonth = Carbon::now()->format('Y/m'));

        $nowMonth = Carbon::parse(str_replace('/', '-', $nowMonth) . '-01');
        $beforMonth = $nowMonth->subMonth()->format('Y/m');
        $otherBeforMonth = Carbon::parse(str_replace('/', '-', $beforMonth) . '-01');
        $formatedBeforMonth = $otherBeforMonth->format('Y-m');

        $response = $this->get("/attendance/list?month=$formatedBeforMonth");
        $response->assertSee($beforMonth);
    }

    // 「翌月」を押下したときに表示月の翌月の情報が表示されるか
    public function test_next_month_display() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/attendance/list');
        $response->assertSee($nowMonth = Carbon::now()->format('Y/m'));

        $nowMonth = Carbon::parse(str_replace('/', '-', $nowMonth) . '-01');
        $nextMonth = $nowMonth->subMonth()->format('Y/m');
        $otherNextMonth = Carbon::parse(str_replace('/', '-', $nextMonth) . '-01');
        $formatedBeforMonth = $otherNextMonth->format('Y-m');

        $response = $this->get("/attendance/list?month=$formatedBeforMonth");
        $response->assertSee($nextMonth);
    }

    // 「詳細」を押下するとその日の勤怠詳細画面に遷移するか
    public function test_detail_page_redirect() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendances = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off'
        ]);

        /**@var User $user */
        $response = $this->get("/attendance/$attendances->id");
        $response->assertStatus(302);
    }
}
