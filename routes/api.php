<?php

use Illuminate\Support\Facades\Route;
use App\Models\User as UserModel;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/ping', function () {
    $name = request()->query('name') ?: 'World';
    return ['php' => "Hello $name from PHP v" . PHP_VERSION];
});

Route::middleware('auth:sanctum')->get('/user', function () {
    return request()->user();
});

Route::post('/tokens/create', function () {
    $credentials = request()->validate([
        'email' => 'required|email|max:255',
        'password' => 'required|min:8',
    ]);
    if (Auth::attempt($credentials)) {
        $user = UserModel::where('email', request()->post('email'))->firstOrFail();
        return ['token' => $user->createToken(request()->header('user-agent') ?: 'Unknown')->plainTextToken];
    }
    return Response::json([
        'error' => __('The provided credentials do not match our records.')
    ], 400);
});
