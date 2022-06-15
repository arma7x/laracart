<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Access\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('manage-user', function (User $user) {
            if ($user->access_level <= 0 && $user->read_permission === 1 && $user->write_permission === 1)
                return Response::allow();
            return Response::deny('You must be an administrator.');
        });
    }
}
