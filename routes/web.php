<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); // ['verify' => true] + middleware('verified');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/manage-token', [App\Http\Controllers\ManageTokenController::class, 'index'])->name('manage-token');

Route::get('/password/change', [App\Http\Controllers\Auth\ChangePasswordController::class, 'index'])->name('ui-change-password');
Route::post('/password/change', [App\Http\Controllers\Auth\ChangePasswordController::class, 'store'])->name('change-password');

Route::post('/firebase-login', [App\Http\Controllers\FirebaseSessionController::class, 'createSession'])->name('firebase-login');
Route::post('/firebase-logout', [App\Http\Controllers\FirebaseSessionController::class, 'destroySession'])->name('firebase-logout');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/manage-user', [App\Http\Controllers\Admin\ManageUserController::class, 'index'])
        ->middleware('alp:access_level@0,read,write')
        ->name('admin.manage-user');
});
