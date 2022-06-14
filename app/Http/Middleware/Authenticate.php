<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->is('api/*')) {
            abort(response()->json(['message' => __('Unauthorized')], 401));
        }

        if (! $request->expectsJson()) {
            Session::flash('has_warning', __('Unauthorized'));
            return route('login');
        }
    }
}
