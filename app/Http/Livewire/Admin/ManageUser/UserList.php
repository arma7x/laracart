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

    public $user_cursor = [];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['user-updated' => 'functionName'];

    public function functionName($user)
    {
        $this->user_cursor = $user;
    }

    public function updateUser($user)
    {
        $this->emit('user-updated', $user);
    }

    public function render()
    {
        return view('livewire.admin.manage-user.user-list', [
            'user_list' => UserModel::where('id', '!=', Auth::user()->id)->orderBy('access_level', 'asc')->paginate(15),
        ]);
    }
}
