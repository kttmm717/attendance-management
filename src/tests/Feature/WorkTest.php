<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class WorkTest extends TestCase
{
    use RefreshDatabase;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 出勤ボタンが正しく機能するか
    public function test_clock_in() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);

        /**@var User $user */
        $response = $this->withHeader('Referer', url('/staff'))
                        ->actingAs($user)
                        ->post('/clock/in');

        $response->assertRedirect('/staff');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => today()->toDateString(),
            'status' => 'working',
        ]);

        $user->refresh();
        $response = $this->get('/staff');
        $response->assertSee('出勤中');
    }

    // 出勤は一日一回のみできるか
    public function test_only_one_clock_in_per_day() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'user_id' => $user->id,
            'status' => 'off'
        ]);
        $response = $this->get('/staff');
        $response->assertDontSee('出勤');
    }

    // 出勤時刻が管理画面で確認できるか
    public function test_can_see_clock_in_time() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);

        /**@var User $user */
        $response = $this->withHeader('Referer', url('/staff'))
                        ->actingAs($user)
                        ->post('/clock/in');

        $response->assertRedirect('/staff');

        $attendance = Attendance::where('user_id', $user->id)
                                ->whereDate('date', today()->toDateString())
                                ->first();
        
        $response = $this->get('/attendance/list');
        $response->assertSee($attendance->clock_in->format('H:i'));
    }
}
