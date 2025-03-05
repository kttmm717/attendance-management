<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;


class AdminStaffListTest extends TestCase
{
    use DatabaseMigrations;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    // 管理者ユーザーが全一般ユーザーの「氏名」「メールアドレス」を確認できるか
    public function test_admin_can_see_all_users_info() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $staffs = User::where(['role' => 'staff'])->get();

        /**@var User $user */
        $response = $this->actingAs($user)->get('/admin/staff/list');
        
        foreach($staffs as $staff) {
            $response->assertSee($staff->name);
            $response->assertSee($staff->email);
        }
    }

    // ユーザーの勤怠情報が正しく表示されるか
    public function test_user_attendance_display() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $attendances = Attendance::where('user_id', 2)
                                ->whereYear('date', Carbon::now()->year)
                                ->whereMonth('date', Carbon::now()->month)
                                ->get();

        /**@var User $user */
        $response = $this->actingAs($user)->get("/admin/attendance/staff/2");
        
        foreach($attendances as $attendance) {
            $response->assertSee($attendance->date->translatedformat('m/d(D)'));
            $response->assertSee($attendance->clock_in->format('H:i'));
            $response->assertSee($attendance->clock_out->format('H:i'));
            $response->assertSee($attendance->formatedTotalBreakTime());
            $response->assertSee($attendance->totalAttendanceTime());
        }
    }

    // 「前月」を押下したときに表示月の前月の情報が表示されるか
    public function test_previous_month_display() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $attendance = Attendance::where('user_id', 2)
                                ->whereYear('date', Carbon::now()->subMonth()->year)
                                ->whereMonth('date', Carbon::now()->subMonth()->month)
                                ->get();

        $previousMonth = Carbon::now()->subMonth();
        $formattedPreviousMonth = $previousMonth->format('Y-m');

        /**@var User $user */
        $response = $this->actingAs($user)->get("/admin/attendance/staff/2?month=$formattedPreviousMonth");
        foreach($attendance as $date) {
            $response->assertSee($date->clock_in->format('H:i'));
            $response->assertSee($date->clock_out->format('H:i'));
            $response->assertSee($date->formatedTotalBreakTime());
            $response->assertSee($date->totalAttendanceTime());
        }
    }

    // 「翌月」を押下したときに表示月の前月の情報が表示されるか
    public function test_next_month_display() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $attendances = Attendance::factory()->count(3)->create([
            'user_id' => 2,
            'date' => Carbon::now()->addMonth()->format('Y-m'),
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'status' => 'off'
        ]);

        $nextMonth = Carbon::now()->addMonth();
        $formattedNextMonth = $nextMonth->format('Y-m');

        /**@var User $user */
        $response = $this->actingAs($user)->get("/admin/attendance/staff/2?month=$formattedNextMonth");
        foreach($attendances as $attendance) {
            $response->assertSee($attendance->clock_in->format('H:i'));
            $response->assertSee($attendance->clock_out->format('H:i'));
            $response->assertSee($attendance->formatedTotalBreakTime());
            $response->assertSee($attendance->totalAttendanceTime());
        }
    }

    // 「詳細」を押下すると、その日の勤怠詳細画面に遷移するか
    public function test_detail_page_redirect() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $staff = User::find(2);

        /**@var User $user */
        $this->actingAs($user)->get('/admin');
        $response = $this->get("/admin/attendance/$staff->id");
        $response->assertStatus(200);
    }
}
