<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;

class AdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 勤怠詳細画面に表示されるデータが選択したものになっているか
    public function test_attendance_detail_display() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $attendance = Attendance::factory()->create([
            'clock_in' => '08:00:00',
            'clock_out' => '12:00:00',
            'status' => 'off'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get("/admin/attendance/$attendance->id");
        $response->assertSee($attendance->user->name);
        $response->assertSee($attendance->clock_in->format('H:i'));
        $response->assertSee($attendance->clock_out->format('H:i'));
    }

    // 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示されるか
    public function test_clock_in_after_clock_out_shows_error() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $attendance = Attendance::factory()->create([
            'clock_in' => '08:00:00',
            'clock_out' => '12:00:00',
            'status' => 'off'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->post("/correction/$attendance->id", [
            'clock_in' => '13:00',
            'clock_out' => '12:00',
            'reason' => 'テスト変更'
        ]);
        $response->assertSessionHasErrors([
            'clock_out' => '出勤時間もしくは退勤時間が不適切な値です'
        ]);
    }

    // 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示されるか
    public function test_break_start_after_clock_out_shows_error() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $attendance = Attendance::factory()->create([
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
        $response = $this->actingAs($user)->post("/correction/$attendance->id", [
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'break_times' => [
                0 => ['break_start'=>'20:00', 'break_end'=>'15:00']
            ],
            'reason' => 'テスト理由'
        ]);
        $response->assertSessionHasErrors([
            'break_times.0.break_start' => '休憩開始時間は退勤時間より前である必要があります',
        ]);
    }

    // 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示されるか
    public function test_break_end_after_clock_out_shows_error() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $attendance = Attendance::factory()->create([
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
        $response = $this->actingAs($user)->post("/correction/$attendance->id", [
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'break_times' => [
                0 => ['break_start'=>'14:00', 'break_end'=>'20:00']
            ],
            'reason' => 'テスト理由'
        ]);
        $response->assertSessionHasErrors([
            'break_times.0.break_end' => '休憩終了時間は退勤時間より前である必要があります',
        ]);
    }

    // 備考欄が未入力の場合、エラーメッセージが表示されるか
    public function test_reason_is_required() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $attendance = Attendance::factory()->create([
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
        $response = $this->actingAs($user)->post("/correction/$attendance->id", [
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'break_times' => [
                0 => ['break_start'=>'14:00', 'break_end'=>'15:00']
            ],
            'reason' => ''
        ]);
        $response->assertSessionHasErrors([
            'reason' => '備考を記入してください',
        ]);
    }
}
