<?php

namespace App\Providers;

use App\Facades\AccessLevelPermission;
use Illuminate\Support\ServiceProvider;

class AccessLevelPermissionProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('alp',function(){
            return new AccessLevelPermission();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
