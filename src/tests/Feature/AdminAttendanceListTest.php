<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;
use Database\Seeders\DatabaseSeeder;

class AdminAttendanceListTest extends TestCase
{
    use DatabaseMigrations;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    // その日になされた全ユーザーの勤怠情報が正確に確認できるか
    public function test_all_users_attendance_is_correct() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        /**@var User $user */
        $attendances = Attendance::where('date', Carbon::today()->toDateString())->get();
        $response = $this->actingAs($user)->get('/admin');
        foreach($attendances as $attendance) {
            $response->assertSee($attendance->user->name);
            $response->assertSee($attendance->clock_in->format('H:i'));
            $response->assertSee($attendance->clock_out->format('H:i'));
        }
    }

    // 遷移した際に現在の時刻が表示されるか
    public function test_current_time_is_displayed() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        /**@var User $user */
        $response = $this->actingAs($user)->get('/admin');
        $response->assertSee(Carbon::now()->format('Y-m-d'));
    }

    // 「前日」を押下したときに前の日の勤怠情報が表示されるか
    public function test_previous_day_display() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $attendances = Attendance::where('date', Carbon::now()->subDay()->toDateString())->get();

        $yesterday = Carbon::now()->subDay();
        $formattedYesterday = $yesterday->format('Y-m-d');

        /**@var User $user */
        $response = $this->actingAs($user)->get("/admin?date=$formattedYesterday");
        foreach($attendances as $attendance) {
            $response->assertSee($attendance->user->name);
            $response->assertSee($attendance->clock_in->format('H:i'));
            $response->assertSee($attendance->clock_out->format('H:i'));
        }
    }

    // 「翌日」を押下したときに前の日の勤怠情報が表示されるか
    public function test_next_day_display() {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);

        $attendances = Attendance::factory()->count(3)->create([
            'date' => Carbon::now()->addDay()->format('Y-m-d'),
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'status' => 'off'
        ]);

        $tomorrow = Carbon::now()->addDay();
        $formattedTomorrow = $tomorrow->format('Y-m-d');

        /**@var User $user */
        $response = $this->actingAs($user)->get("/admin?date=$formattedTomorrow");
        
        foreach($attendances as $attendance) {
            $response->assertSee($attendance->user->name);
            $response->assertSee($attendance->clock_in->format('H:i'));
            $response->assertSee($attendance->clock_out->format('H:i'));
        }
    } 
}
