<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => '管理者1',
            'email' => 'admin@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー1',
            'email' => 'staff1@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'staff'
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー2',
            'email' => 'staff2@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'staff'
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー3',
            'email' => 'staff3@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'staff'
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー4',
            'email' => 'staff4@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'staff'
        ];
        User::create($param);
    }
}
