<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Facades\Helpers\FirebaseHelper as Firebase;

class FirebaseGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        try {
            $user = Firebase::user();
            if ($user == null) {
                return $next($request);
            } else {
                if ($request->is('api/*')) {
                    abort(response()->json(['message' => __('Forbidden')], 403));
                }
                Session::flash('has_warning', __('Forbidden'));
                return redirect('/');
            }
        } catch (\Exception $e) {
            if ($request->is('api/*')) {
                abort(response()->json(['message' => $e->getMessage()], 403));
            }
            Session::flash('has_warning', __('Forbidden'));
            return redirect('/');
        }
    }
}
