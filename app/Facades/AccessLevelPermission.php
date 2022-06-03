<?php
namespace App\Facades;

use Illuminate\Support\Facades\Auth;

class AccessLevelPermission
{
    public function perm()
    {
        echo Auth::User()->access_level . " " . Auth::User()->read_permission . " " . Auth::User()->write_permission;
    }

    public function accessLevelGranted(int $minimum)
    {
        return Auth::User()->access_level <= $minimum;
    }

    public function canRead()
    {
        return Auth::User()->read_permission === 1;
    }

    public function canWrite()
    {
        return Auth::User()->write_permission === 1;
    }
}
