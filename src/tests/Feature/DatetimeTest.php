<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use Carbon\Carbon;

class DatetimeTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // 現在の日時情報がUIと同じ形式で出力されているか
    public function test_current_time_format() {
        $user = User::find(2);
        $currentTime = Carbon::now()->format('H:i');

        $this->actingAs($user)->get('/staff');

        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $currentTime);
    }
}
