<?php

namespace App\Http\Livewire\Admin\ManageUser;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User as UserModel;

class UserList extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $uid;
    public $name;
    public $email;
    public $access_level;
    public $read_permission;
    public $write_permission;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'uid' => 'required',
        'name' => 'required|min:6',
        'email' => 'required|email',
        'access_level' => 'required|min:0|max:255',
        'read_permission' => 'required|min:0|max:1',
        'write_permission' => 'required|min:0|max:1',
    ];

    public function populateUpdateUserModal($user)
    {
        $this->resetErrorBag();
        $this->uid = $user['id'];
        $user = UserModel::find($user['id']);
        foreach($user->toArray() as $key => $value) {
            if (isset($this->rules[$key])) {
                $this->$key = $value;
            }
        }
        $this->emit('populated');
    }

    public function updateUser()
    {
        $this->validate();
        $user = UserModel::find($this->uid);
        $rules = [...array_keys($this->rules)];
        array_shift($rules);
        foreach($rules as $key) {
            $user->$key = $this->$key;
        }
        $user->save();
        $this->emit('updated');
    }

    public function render()
    {
        return view('livewire.admin.manage-user.user-list', [
            'user_list' => UserModel::where('id', '!=', Auth::user()->id)->orderBy('access_level', 'asc')->paginate(15),
        ]);
    }
}
