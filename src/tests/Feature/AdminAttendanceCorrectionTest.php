<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\CorrectionRequest;
use Illuminate\Support\Carbon;

class AdminAttendanceCorrectionTest extends TestCase
{
    use DatabaseMigrations;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    // 承認待ちの修正申請が全て表示されているか
    public function test_pending_corrections_list() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $correction_requests = CorrectionRequest::where('status', 'pending')->get();

        /**@var User $user */
        $response = $this->actingAs($user)->get('stamp_correction_request/list?tab=pending');
        foreach($correction_requests as $correction_request) {
            $response->assertSee($correction_request->user->name);
            $response->assertSee($correction_request->date->format('Y/m/d'));
            $response->assertSee($correction_request->reason);
            $response->assertSee($correction_request->requested_at->format('Y/m/d'));
        }
    }

    // 承認済みの修正申請が全て表示されているか
    public function test_approved_corrections_list() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $correction_requests = CorrectionRequest::where('status', 'approved')->get();

        /**@var User $user */
        $response = $this->actingAs($user)->get('stamp_correction_request/list?tab=approved');
        foreach($correction_requests as $correction_request) {
            $response->assertSee($correction_request->user->name);
            $response->assertSee($correction_request->date->format('Y/m/d'));
            $response->assertSee($correction_request->reason);
            $response->assertSee($correction_request->requested_at->format('Y/m/d'));
        }
    }

    // 修正申請の詳細内容が正しく表示されているか
    public function test_correction_details_displayed() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $correction_request = CorrectionRequest::find(1);

        /**@var User $user */
        $response = $this->actingAs($user)->get("/stamp_correction_request/approve/$correction_request->id");
        $response->assertSee($correction_request->user->name);
        $response->assertSee($correction_request->date->format('Y年'));
        $response->assertSee($correction_request->date->format('n月j日'));
        $response->assertSee($correction_request->new_clock_in->format('H:i'));
        $response->assertSee($correction_request->new_clock_out->format('H:i'));
        $response->assertSee($correction_request->reason);
    }

    // 修正申請の承認処理が正しく行われるか
    public function test_correction_approval() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $attendance = Attendance::factory()->create([
            'date' => Carbon::today()->subDays(2)->format('Y-m-d'),
            'clock_in' => '08:00:00',
            'clock_out' => '12:00:00',
            'status' => 'off'
        ]);

        $correction_request = CorrectionRequest::create([
            'user_id' => 2,
            'attendance_id' => $attendance->id,
            'requested_at' => Carbon::now(),
            'status' => 'pending',
            'date' => $attendance->date,
            'new_clock_in' => '09:00:00',
            'new_clock_out' => '13:00:00',
            'reason' => '遅刻のため'
        ]);

        /**@var User $user */
        $this->actingAs($user)->post("/stamp_correction_request/approve/$correction_request->id");
        
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'clock_in' => '09:00:00',
            'clock_out' => '13:00:00',
        ]);

        $this->assertDatabaseHas('correction_requests', [
            'id' => $correction_request->id,
            'status' => 'approved'
        ]);
    }
}
