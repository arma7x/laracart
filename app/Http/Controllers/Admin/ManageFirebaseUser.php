<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageFirebaseUser extends Controller
{
    public function index()
    {
        return view('admin.manage_firebase_user.index');
    }
}
