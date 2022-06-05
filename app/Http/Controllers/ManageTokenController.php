<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageTokenController extends Controller
{
    public function index()
    {
        return view('manage-token');
    }

    // TODO: require password
    public function generateToken()
    {
        request()->validate(['name' => 'required|max:255']);
        $token = request()->user()->createToken(request()->post('name') ?: 'QR-Code');
        return response()->json(['token' => $token->plainTextToken]);
    }
}
