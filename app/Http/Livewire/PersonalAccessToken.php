<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PersonalAccessToken extends Component
{

    public function removeToken($tokenId) {
        Auth::user()->tokens()->where('id', $tokenId)->delete();
        $this->emit('removed');
    }

    public function removeTokens() {
        Auth::user()->tokens()->delete();
        $this->emit('removed');
    }

    /*
     * required name, password
     * validate password
     * emit error-generate validation OR
     * emit token-generated
     */
    public function generateToken($password = '', $name = 'QR-Code') {
        $rules = ['name' => 'required|max:255', 'password' => 'required|min:8'];
        $inputs = ['name' => $name, 'password' => $password];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $this->emit('error-generate', $validator->errors());
            return;
        }
        $this->emit('token-generated', '1212');
        // $token = Auth::user()->createToken($name);
        // $token->plainTextToken
    }

    public function render()
    {
        return view('livewire.personal-access-token', [
            'tokens' => Auth::user()->tokens,
        ]);
    }
}
