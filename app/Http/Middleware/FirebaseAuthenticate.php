<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Facades\FirebaseHelper as Firebase;

class FirebaseAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * usage middleware('alp:access_level@255,read,write')
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        try {
            Firebase::verifySessionCookie(Firebase::getSessionCookie());
        } catch (\Exception $e) {
            if ($request->is('api/*')) {
                abort(response()->json(['message' => $e->getMessage()], 401));
            }
            abort(401, $e->getMessage());
        }
        return $next($request);
    }
}
