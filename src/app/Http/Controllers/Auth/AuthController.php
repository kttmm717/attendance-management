<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function store(AdminLoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません'
            ]);
        }
        return redirect('/admin');
    }
}
