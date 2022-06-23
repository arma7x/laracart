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
        $this->searchUser();
    }

    public function searchUser()
    {
        try {
            if ($this->search == null || trim($this->search) == '') {
                $this->user = null;
            } else if (trim($this->search)[0] == '+') {
                $this->user = Firebase::auth()->getUserByPhoneNumber(trim($this->search))->jsonSerialize();
            } else if (Validator::make(['email' => trim($this->search)], ['email' => 'required|email'])->fails() === false) {
                $this->user = Firebase::auth()->getUserByEmail(trim($this->search))->jsonSerialize();
            } else {
                $this->user = Firebase::auth()->getUser(trim($this->search))->jsonSerialize();
            }
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            $this->user = $e->getMessage();
        } catch(\Exception $e) {
            $this->user = null;
        }
    }

    // TODO
    private function getCustomUserClaims($uid)
    {
        return Firebase::auth()->getUser($uid)->customClaims;
    }

    // TODO
    public function setCustomUserClaims($uid, $claims = [])
    {

    }

    // TODO
    public function disableUser($uid)
    {

    }

    // TODO
    public function enableUser($uid)
    {

    }

    // TODO
    public function revokeRefreshTokens($uid)
    {

    }

    // TODO
    public function deleteUser($uid)
    {

    }

    public function render()
    {
        $this->searchUser();
        return view('livewire.admin.manage-firebase-user.user-list');
    }
}
