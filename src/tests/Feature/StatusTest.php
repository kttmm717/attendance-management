<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Attendance;

class StatusTest extends TestCase
{
    use DatabaseMigrations;

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 勤務外の場合、勤怠ステータスが正しく表示されるかテスト
    public function test_off_status() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/staff');
        $response->assertSee('勤務外');
    }

    // 出勤中の場合、勤怠ステータスが正しく表示されるか
    public function test_working_status() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'status' => 'working'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/staff');
        $response->assertSee('出勤中');
    }

    // 休憩中の場合、勤怠ステータスが正しく表示されるか
    public function test_break_status() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'status' => 'break'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/staff');
        $response->assertSee('休憩中');
    }

    // 退勤済みの場合、勤怠ステータスが正しく表示されるか
    public function test_finished_status() {
        $user = User::factory()->create([
            'role' => 'staff'
        ]);
        Attendance::factory()->create([
            'status' => 'off'
        ]);

        /**@var User $user */
        $response = $this->actingAs($user)->get('/staff');
        $response->assertSee('退勤済');
    }
}
