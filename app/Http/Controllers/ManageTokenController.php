<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageTokenController extends Controller
{
    public function index()
    {
        return view('manage-token');
    }

    public function removeTokens()
    {
        request()->user()->tokens()->delete();
        return redirect('manage-token');
    }
}
