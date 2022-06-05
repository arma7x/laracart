<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\MatchOldPassword;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('auth.passwords.change');
    }

    public function store()
    {
        request()->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        Auth::user()->forceFill(['password' => Hash::make(request()->post('new_password'))])->save();
        Session::flash('has_success', __('Successfully change your password'));
        return redirect(RouteServiceProvider::HOME);
    }
}
