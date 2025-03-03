<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;

class ClockOutTest extends TestCase
{
    use RefreshDatabase;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 退勤ボタンが正しく機能するか
    public function test_clock_out() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'working'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/staff');
        $response->assertSee('退勤');

        $response = $this->post('/clock/out');
        $response->assertRedirect('/staff');
        
        $user->refresh();
        $attendance->refresh();

        $this->assertEquals('off', $attendance->status);

        $response = $this->get('/staff');
        $response->assertSee('退勤済');
    }

    // 退勤時刻が管理画面で確認できるか
    public function test_can_see_clock_out_time() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        /**@var User $user */
        $response = $this->actingAs($user)->get('/staff');

        $response = $this->post('/clock/in');
        $response->assertRedirect('/staff');

        $response = $this->post('/clock/out');
        $response->assertRedirect('/staff');

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('date', today()->toDateString())
                                ->first();

        $response = $this->get('/attendance/$attendance->id');
        $response->assertSee($attendance->clock_out->format('H:i'));
    }
}
