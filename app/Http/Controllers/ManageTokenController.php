<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageTokenController extends Controller
{
    public function index()
    {
        return view('manage-token');
    }
}
