<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BreakTimeController;
use App\Http\Controllers\RequestController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 新規会員登録ルート
Route::post('/register', [RegisteredUserController::class,'create']);

Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->name('verification.notice');

Route::post('/email/verify', function() {
    session()->get('unauthenticated_user')->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました！');
});

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request) {
    $request->fulfill();
    session()->forget('unauthenticated_user');
    return redirect('/staff');
})->name('verification.verify');

// ログアウトボタン押された時のルート
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

// 管理者ログアウトルート(デフォルトのコントローラーに追加)、一般ログアウトルートはララベルデフォルトのログインページへ
Route::get('/admin/login', [AuthenticatedSessionController::class, 'create']);

// 管理者ログインしたときのルート
Route::post('/admin/login', [AuthController::class, 'store']);



// 管理者ルート
Route::middleware('auth')->group(function() {
    Route::get('/admin', [AttendanceController::class, 'adminView']);
});

// 従業員ルート
Route::middleware('auth','verified')->group(function() {
    Route::get('/staff', [AttendanceController::class, 'staffView']);
    Route::post('/clock/in', [AttendanceController::class, 'clockIn']);
    Route::post('/break/start', [BreakTimeController::class, 'breakStart']);
    Route::post('/break/end', [BreakTimeController::class, 'breakEnd']);
    Route::post('/clock/out', [AttendanceController::class, 'clockOut']);
    Route::get('/attendance/list', [AttendanceController::class, 'index']);
    Route::get('/attendance/{id}', [AttendanceController::class, 'detail']);
    Route::get('/stamp_correction_request/list', [RequestController::class, 'requestList']);
    Route::post('/request/{id}', [RequestController::class, 'request']);
});
