<div class="card">
    <div class="card-header d-flex flex-row justify-content-between">
        <h3>{{ __('Manage User') }}</h3>
        <input id="searchInput" type="text" class="form-control w-25" wire:model.debounce.500ms="search" placeholder="{{ __('Search user') }}">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <div id="userListTable" >
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <td>{{ __('Id') }}</td>
                            <td>{{ __('Name') }}</td>
                            <td>{{ __('Email') }}</td>
                            <td>{{ __('Access Level') }}</td>
                            <td>{{ __('Read Permission') }}</td>
                            <td>{{ __('Write Permission') }}</td>
                            <td>{{ __('Email Verified At') }}</td>
                            <td>{{ __('Created At') }}</td>
                            <td>{{ __('Updated At') }}</td>
                            <td>{{ __('Action') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userList as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ $user->access_level }}</td>
                            <td class="text-center">{{ $user->read_permission }}</td>
                            <td class="text-center">{{ $user->write_permission }}</td>
                            @if (is_null($user->email_verified_at) || !$user->email_verified_at)
                            <td class="text-center font-weight-bold text-danger">!</td>
                            @else
                            <td class="user-date" data-date="{{ $user->email_verified_at }}"></td>
                            @endif
                            <td class="user-date" data-date="{{ $user->created_at }}"></td>
                            <td class="user-date" data-date="{{ $user->updated_at }}"></td>
                            <td>
                                <button class="btn btn-sm btn-secondary" onClick="resetForm({{ $user->toJson() }})" >{{ __('Update') }}</button>
                                <button class="btn btn-sm btn-danger" onClick="deleteUser({{ $user->toJson() }})">{{ __('Delete') }}</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $userList->links() }}
            </div>
            <div id="updateUserModal" tabindex="-1" class="d-none" aria-labelledby="updateUserModalLabel" aria-hidden="false">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">{{ __('Please fill in the form') }}</h5>
                    <button type="button" class="btn-close" onClick="cancelUpdate()" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form>
                        <input type="hidden" type="text" class="form-control update-user" data-id="id">
                        <label class="form-label">{{ __('Name') }}</label>
                        <div class="col mb-3">
                            <input type="text" class="form-control update-user" data-id="name">
                            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <label class="form-label">{{ __('Email') }}</label>
                        <div class="mb-3">
                            <input type="text" class="form-control update-user" data-id="email">
                            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <label class="form-label">{{ __('Access Level') }}</label>
                        <div class="mb-3">
                            <input type="number" class="form-control update-user" data-id="access_level" min="0" max="255">
                            @error('access_level') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <label class="form-label">{{ __('Read Permission') }}</label>
                        <div class="mb-3">
                            <input type="number" class="form-control update-user" data-id="read_permission" min="0" max="1">
                            @error('read_permission') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <label class="form-label">{{ __('Write Permission') }}</label>
                        <div class="mb-3">
                            <input type="number" class="form-control update-user" data-id="write_permission" min="0" max="1">
                            @error('write_permission') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button id="userUpdateButton" type="button" class="btn btn-sm btn-primary" onClick="updateUser()">{{ __('Save Changes') }}</button>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@push('scripts-user-list')
<script>

    let isUpdate = false;
    let searchInput;
    let formFields;
    let userListTable;
    let updateUserModal;
    let userUpdateButton;
    let targetUser;

    document.addEventListener('livewire:load', function () {

        searchInput = document.getElementById('searchInput');
        formFields = document.getElementsByClassName('update-user');
        userListTable = document.getElementById('userListTable');
        updateUserModal = document.getElementById('updateUserModal');
        userUpdateButton = document.getElementById('userUpdateButton');

        toLocalDateString()

        Livewire.hook('message.sent', component => {
            userUpdateButton.disabled = true;
        })

        Livewire.hook('message.processed', component => {
            if (targetUser != null) {
                populateForm(targetUser);
            }
            userUpdateButton.disabled = false;
            toLocalDateString();
            toggleUpdateVisibility();
        })

        @this.on('reseted-populated', (user) => {
            targetUser = user;
            populateForm(targetUser);
            isUpdate = true;
            toggleUpdateVisibility();
        })

        @this.on('updated', () => {
            isUpdate = false;
            toggleUpdateVisibility();
            alert("{{ __('The operation was successful') }}");
        })

        @this.on('deleted', () => {
            alert("{{ __('The operation was successful') }}");
        })
    })

    function toLocalDateString() {
        let userdates = document.getElementsByClassName('user-date');
        Object.keys(userdates).forEach((i) => {
            userdates[i].innerText = window._globalEloquentDateToLocal(userdates[i].getAttribute("data-date"));
        })
    }

    function toggleUpdateVisibility() {
        if (isUpdate) {
            searchInput.classList.add('d-none');
            if (userListTable)
                userListTable.classList.add('d-none');
            if (updateUserModal)
                updateUserModal.classList.remove('d-none');
        } else {
            searchInput.classList.remove('d-none');
            if (userListTable)
                userListTable.classList.remove('d-none');
            if (updateUserModal)
                updateUserModal.classList.add('d-none');
        }
    }

    function populateForm(user) {
        Object.keys(formFields).forEach((i) => {
            const key = formFields[i].getAttribute("data-id");
            formFields[i].value = user[key];
        });
    }

    function resetForm(user) {
        @this.resetErrorPopulate(user);
    }

    function updateUser() {
        let user = {};
        Object.keys(formFields).forEach((i) => {
            const key = formFields[i].getAttribute("data-id");
            user[key] = formFields[i].value;
        });
        const conf = confirm("{{ __('Are you sure to continue this operation ?') }}");
        if (!conf)
            return;
        @this.updateUser(user)
    }

    function cancelUpdate() {
        targetUser = null;
        isUpdate = false;
        toggleUpdateVisibility();
    }

    function deleteUser(user) {
        const conf = confirm("{{ __('Are you sure to continue this operation ?') }}");
        if (!conf)
            return;
        @this.deleteUser(user);
    }
</script>
@endpush
@stack('scripts-user-list')
