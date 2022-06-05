<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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

    public function render()
    {
        return view('livewire.personal-access-token', [
            'tokens' => Auth::user()->tokens,
        ]);
    }
}
