<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    public function create(Request $request, CreateNewUser $creator) {
        event(new Registered($user = $creator->create($request->all())));
        session()->put('unauthenticated_user', $user);
        return redirect()->route('verification.notice');
    }
}
