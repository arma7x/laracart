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
@push('scripts-user-list-lw')
<script>

    let debounce = -1;
    let isUpdate = false;

    let userSearchInput;
    let formFields;
    let userListTable;
    let updateUserModal;
    let userUpdateButton;
    let targetUser;

    document.addEventListener('livewire:load', function () {

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

        userSearchInput = document.getElementById('userSearchInput');
        userSearchInput.focus();
        userSearchInput.selectionStart = userSearchInput.selectionEnd = userSearchInput.value.length;
        userSearchInput.addEventListener('input', (evt) => {
            if (isUpdate)
                return
            if (debounce > -1) {
                clearTimeout(debounce);
                debounce = -1;
            }
            debounce = setTimeout(() => {
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(window.location.search);
                if (evt.target.value)
                    urlParams.set('search', evt.target.value);
                else
                    urlParams.delete('search');
                if (urlParams.toString()) {
                    const url = `${document.location.protocol}//${document.location.host}${document.location.pathname}?${urlParams.toString()}`
                    window.location.href = url;
                } else {
                  window.location.href = `${document.location.protocol}//${document.location.host}${document.location.pathname}`
                }
            }, 500)
        });
    })

    function toLocalDateString() {
        let userdates = document.getElementsByClassName('user-date');
        Object.keys(userdates).forEach((i) => {
            userdates[i].innerText = window._globalEloquentDateToLocal(userdates[i].getAttribute("data-date"));
        })
    }

    function toggleUpdateVisibility() {
        if (isUpdate) {
            if (userListTable)
                userListTable.classList.add('d-none');
            if (updateUserModal)
                updateUserModal.classList.remove('d-none');
        } else {
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
@stack('scripts-user-list-lw')
