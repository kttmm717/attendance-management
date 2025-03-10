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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExportController;


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
Route::middleware('auth', 'admin')->group(function() {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/staff/list', [AdminController::class, 'staffList']);
    Route::get('/admin/attendance/{id}', [AdminController::class, 'attendance']);
    Route::get('/stamp_correction_request/list', [RequestController::class, 'requestList'])->name('request');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [RequestController::class, 'requestDetail']);
    Route::post('/stamp_correction_request/approve/{attendance_correct_request}', [RequestController::class, 'approve']);
    Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'staffAttendance']);
    Route::post('/correction/{id}', [AdminController::class, 'correction']);
    Route::get('/export', [ExportController::class, 'export'])->name('export');
});

// 従業員ルート
Route::middleware('auth','verified')->group(function() {
    Route::get('/staff', [AttendanceController::class, 'staffView']);
    Route::post('/clock/in', [AttendanceController::class, 'clockIn']);
    Route::post('/break/start', [BreakTimeController::class, 'breakStart']);
    Route::post('/break/end', [BreakTimeController::class, 'breakEnd']);
    Route::post('/clock/out', [AttendanceController::class, 'clockOut']);
    Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{id}', [AttendanceController::class, 'detail']);
    Route::get('/stamp_correction_request/list', [RequestController::class, 'requestList'])->name('request');
    Route::post('/request/{id}', [RequestController::class, 'request']);
});
