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

Route::get('/manage-token', [App\Http\Controllers\ManageTokenController::class, 'index'])->middleware('auth')->name('manage-token');
Route::post('/generate-token', [App\Http\Controllers\ManageTokenController::class, 'generateToken'])->middleware('auth')->name('generate-token');

Route::prefix('admin')->middleware('alp:access_level@0,read,write')->group(function () {
    Route::get('/manage-user', [App\Http\Controllers\Admin\ManageUserController::class, 'index'])->name('admin.manage-user');
});
