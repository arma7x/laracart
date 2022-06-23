<?php

namespace App\Http\Livewire\Admin\ManageFirebaseUser;

use App\Facades\Helpers\FirebaseHelper as Firebase;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class UserList extends Component
{
    public $user;
    public $search;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        try {
            if ($this->search == null || trim($this->search) == '') {
                $this->user = null;
            } else if (trim($this->search)[0] == '+') {
                $this->user = 'getUserByPhoneNumber';
            } else if (Validator::make(['email' => trim($this->search)], ['email' => 'required|email'])->fails() === false) {
                $this->user = 'getUserByEmail';
            } else {
                $this->user = 'getUser';
            }
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            // $e->getMessage()
            $this->user = null;
        } catch(\Exception $e) {
            $this->user = null;
        }
    }

    public function render()
    {
        return view('livewire.admin.manage-firebase-user.user-list');
    }
}
