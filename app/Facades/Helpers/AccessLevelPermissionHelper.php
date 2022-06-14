<?php
namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

class AccessLevelPermissionHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alp'; // same as bind method in service provider
    }
}
