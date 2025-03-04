<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Attendance;
use App\Models\User;
use Tests\TestCase;
use App\Models\BreakTime;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 勤怠詳細画面の「名前」がログインユーザーの氏名になっているか
    public function test_user_name_is_displayed() {
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get("/attendance/$attendance->id");
        $response->assertSee('テスト太郎');
    }
    // 勤怠詳細画面の「日付」が選択した日付になっているか
    public function test_attendance_date_is_displayed() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get("/attendance/$attendance->id");
        $response->assertSee($attendance->date->format('Y年'));
        $response->assertSee($attendance->date->format('n月j日'));
    }

    // 「出勤・退勤」にて記されている時間がログインユーザーの打刻と一致しているか
    public function test_attendance_times_match() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'clock_in' => '08:00:00',
            'clock_out' => '12:00:00',
            'status' => 'off'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get("/attendance/$attendance->id");
        $response->assertSee('08:00');
        $response->assertSee('12:00');
    }

    // 「休憩」にて記されている時間がログインユーザーの打刻と一致しているか
    public function test_break_times_match() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ]);
        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => '14:00:00',
            'break_end' => '15:00:00'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get("/attendance/$attendance->id");
        $response->assertSee('14:00');
        $response->assertSee('15:00');
    }
}
