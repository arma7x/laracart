<?php
namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

class FirebaseHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'firebase'; // same as bind method in service provider
    }
}
