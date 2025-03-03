<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Attendance;
use Tests\TestCase;
use App\Models\User;
use App\Models\BreakTime;

class BreakTest extends TestCase
{
    use RefreshDatabase;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 休憩ボタンが正しく機能するか
    public function test_break_start() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/staff');
        $response->assertSee('休憩入');
    }

    // 休憩は一日に何回でもできるか
    public function test_multiple_breaks() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working'
        ]);

        /**@var User $user */
        $this->actingAs($user)->get('/staff');

        $this->post('/break/start');
        $this->post('/break/end');

        $this->post('/break/start');
        $this->post('/break/end');

        $response = $this->get('/staff');
        $response->assertSee('休憩入');
    }

    // 休憩戻ボタンが正しく機能するか
    public function test_return_from_break() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working'
        ]);

        /**@var User $user */
        $this->actingAs($user);

        $this->post('/break/start');
        $user->refresh();
        $response = $this->get('/staff');
        $response->assertSee('休憩戻');

        $this->post('/break/end');
        $user->refresh();
        $response = $this->get('/staff');
        $response->assertSee('出勤中');
    }

    // 休憩戻は一日に何回でもできるか
    public function test_multiple_break_returns() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working'
        ]);

        /**@var User $user */
        $this->actingAs($user)->get('/staff');

        $this->post('/break/start');
        $this->post('/break/end');

        $this->post('/break/start');
        $user->refresh();
        $response = $this->get('/staff');
        $response->assertSee('休憩戻');
    }

    // 休憩時刻が管理画面で確認できるか
    public function test_can_see_break_start_time() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working'
        ]);

        /**@var User $user */
        $this->actingAs($user)->get('/staff');

        $this->post('/break/start');
        $this->post('/break/end');

        $this->post('/break/start');
        $this->post('/break/end');

        $breakTimes = BreakTime::where('attendance_id', $attendance->id)->get();

        $response = $this->get('/attendance/$attendance->id');

        foreach($breakTimes as $breakTime) {
            $response->assertSee($breakTime->break_start->format('H:i'));
            $response->assertSee($breakTime->break_end->format('H:i'));
        }
        
    }
}
