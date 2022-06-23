<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Facades\Helpers\FirebaseHelper as Firebase;
use Illuminate\Http\Request;

class ManageFirebaseUser extends Controller
{
    public function index()
    {
        return response()->json(['users' => iterator_to_array(Firebase::auth()->listUsers())]);
        // return view('admin.manage_user.index');
    }
}
