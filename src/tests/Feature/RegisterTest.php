<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // 名前が未入力の場合、バリデーションメッセージが表示されるか
    public function test_name_is_required() {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください'
        ]);
    }
    
    // メールアドレスが未入力の場合、バリデーションメッセージが表示されるか
    public function test_email_is_required() {
        $response = $this->post('register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    // パスワードが8文字未満の場合、バリデーションメッセージが表示されるか
    public function test_password_must_be_at_least_8_characters() {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@gmail.com',
            'password' => '1234',
            'password_confirmation' => '1234'
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください'
        ]);
    }

    // パスワードが一致しない場合、バリデーションメッセージが表示されるか
    public function test_password_confirmation_must_match() {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '111111111'
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません'
        ]);
    }

    // パスワードが未入力の場合、バリデーションメッセージが表示されるか
    public function test_password_id_required() {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@gmail.com',
            'password' => '',
            'password_confirmation' => ''
        ]);
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    // フォームに内容が入力されていた場合、データが正常に保存されるか
    public function test_user_can_register_and_redirect_to_login() {
        $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test@gmail.com',
        ]);
    }
}
