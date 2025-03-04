<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;
use Carbon\Carbon;

class AttendanceCorrectionTest extends TestCase
{
    use RefreshDatabase;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示されるか
    public function test_clock_in_after_clock_out_shows_error() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ]);

        /**@var User $user */
        $this->actingAs($user)->get("/attendance/$attendance->id");
        $response = $this->post("/request/$attendance->id", [
            'clock_in' => '20:00:00',
            'clock_out' => '19:00:00',
            'reason' => 'テスト理由'
        ]);
        $response->assertSessionHasErrors([
            'clock_out' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    // 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示されるか
    public function test_break_start_after_clock_out_shows_error() {
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
            'break_start' => '14:00',
            'break_end' => '15:00',
        ]);

        /**@var User $user */
        $this->actingAs($user)->get("/attendance/$attendance->id");
        $response = $this->post("/request/$attendance->id", [
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
            'break_end' => '15:00:00',
        ]);

        /**@var User $user */
        $this->actingAs($user)->get("/attendance/$attendance->id");
        $response = $this->post("/request/$attendance->id", [
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
            'break_end' => '15:00:00',
        ]);

        /**@var User $user */
        $this->actingAs($user)->get("/attendance/$attendance->id");
        $response = $this->post("/request/$attendance->id", [
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

    // 修正申請処理が実行されるか
    public function test_correction_request_is_processed() {
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
            'break_end' => '15:00:00',
        ]);

        /**@var User $user */
        $this->actingAs($user)->get("/attendance/$attendance->id");
        $this->post("/request/$attendance->id", [
            'clock_in' => '11:00',
            'clock_out' => '19:00',
            'break_times' => [
                0 => ['break_start'=>'14:00', 'break_end'=>'15:00']
            ],
            'reason' => '1時間遅刻のため'
        ]);
        $correction_riquest_id = CorrectionRequest::latest()->first();

        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        /**@var User $user */
        $response = $this->actingAs($user)->get("/stamp_correction_request/list");
        $response->assertSee('1時間遅刻のため');

        $response = $this->actingAs($user)->get("stamp_correction_request/approve/$correction_riquest_id->id");
        $response->assertSee('1時間遅刻のため');
    }

    // 「承認待ち」にログインユーザーが行った申請が全て表示されているか
    public function test_user_can_view_all_pending_requests() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        /**@var User $user */
        $this->actingAs($user);

        $attendances = Attendance::factory()->count(3)->create([
            'user_id' => $user->id,
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ]);        
        foreach($attendances as $attendance) {
            $response = $this->post("/request/$attendance->id", [
                'clock_in' => '09:00',
                'clock_out' => '20:00',
                'reason' => "テスト変更（$attendance->id）"
            ]);
        }        
        $response = $this->get('/stamp_correction_request/list?tab=pending');
        foreach($attendances as $attendance) {
            $response->assertSee("テスト変更（$attendance->id）");
        }
    }

    // 「承認済」に管理者が承認した修正申請が全て表示されているか
    public function test_user_can_view_all_approved_requests() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        /**@var User $user */
        $this->actingAs($user);

        $attendances = Attendance::factory()->count(3)->create([
            'user_id' => $user->id,
            'clock_in' => '10:00:00',
            'clock_out' => '19:00:00',
            'status' => 'off'
        ]);       
        foreach($attendances as $attendance) {
            $this->post("/request/$attendance->id", [
                'clock_in' => '09:00',
                'clock_out' => '20:00',
                'reason' => "テスト変更（$attendance->id）"
            ]);
        }       
        $correction_requests = CorrectionRequest::all();
        foreach($correction_requests as $correction_request) {
            $correction_request->update([
                'status' => 'approved'
            ]);
        }
        $response = $this->get('/stamp_correction_request/list?tab=approved');
        foreach($attendances as $attendance) {
            $response->assertSee("テスト変更（$attendance->id）");
        }
    }

    //各申請の「詳細」を押下すると申請詳細画面に遷移するか
    public function test_user_can_view_request_detail() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off'
        ]);
        /**@var User $user */
        $response = $this->actingAs($user)->get("/attendance/$attendance->id");
        $response->assertStatus(200);
    }
}
