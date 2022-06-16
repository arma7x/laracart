<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use App\Facades\Helpers\FirebaseHelper as Firebase;

class FirebaseSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function createSession()
    {
        request()->validate([
            'token' => ['required'],
        ]);
        try {
            $user = Firebase::user();
            if ($user == null) {
                Firebase::createSessionCookie(request()->post('token'));
                return response()->json(['message' => __('Successfully logged-in')], 200);
            } else {
                // @TODO
                // if token $user['exp'] in <= 5days, Firebase::createSessionCookie(request()->post('token'));
                // return response()->json(['message' => __('Successfully refreshed cookies')], 200);
                return response()->json(['message' => __('Forbidden')], 403);
            }
        } catch (\Exception $e) {
            // @TODO proper catch
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroySession()
    {
        Firebase::destroySessionCookie();
        return redirect('/');
    }
}
