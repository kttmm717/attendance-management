<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StaffLoginTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testEnvironmentIsTesting() {
        $this->assertEquals('testing', config('app.env'));
    }

    // メールアドレス未入力の場合、バリデーションメッセージが表示されるか
    public function test_email_is_required() {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123'
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    // パスワード未入力の場合、バリデーションメッセージが表示されるか
    public function test_password_is_required() {
        $response = $this->post('/login', [
            'email' => 'staff1@gmail.com',
            'password' => ''
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }
    
    // 登録内容と一致しない場合、バリデーションメッセージが表示されるか
    public function test_unregistered_user_cannot_login() {
        $response = $this->post('/login', [
            'email' => 'notexist@gmail.com',
            'password' => '123456789'
        ]);
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません'
        ]);
    }
}
