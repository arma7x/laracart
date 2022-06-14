<?php

namespace App\Providers;

use App\Facades\Firebase;
use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('firebase', function() {
            $twoWeek = 1209600;
            return new Firebase(base_path('firebase-adminsdk.json'), 'firebase_token', $twoWeek);
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
