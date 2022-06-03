<div>
    <div class="table-responsive">
        <div id="userListTable" >
            <table class="table table-sm">
                <thead>
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
                </thead>
                <tbody>
                    @foreach ($user_list as $user)
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
                            <button class="btn btn-sm btn-secondary" onClick="@this.populateUpdateUserModal({{ $user->toJson() }})" >{{ __('Update') }}</button>
                            <button class="btn btn-sm btn-danger" onClick="deleteUser({{ $user->toJson() }})">{{ __('Delete') }}</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $user_list->links() }}
        </div>
        <div id="updateUserModal" tabindex="-1" class="d-none" aria-labelledby="updateUserModalLabel" aria-hidden="false">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">ID: {{ $uid }} | {{ $name }}</h5>
                <button type="button" class="btn-close" onClick="cancelUpdate()" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form wire:submit.prevent="submit">
                    <label class="form-label">{{ __('Name') }}</label>
                    <div class="col mb-3">
                        <input type="text" class="form-control" wire:model.debounce.360s="name">
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <label class="form-label">{{ __('Email') }}</label>
                    <div class="mb-3">
                        <input type="text" class="form-control" wire:model.360s="email">
                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <label class="form-label">{{ __('Access Level') }}</label>
                    <div class="mb-3">
                        <input type="number" class="form-control" wire:model.360s="access_level" min="0" max="255">
                        @error('access_level') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <label class="form-label">{{ __('Read Permission') }}</label>
                    <div class="mb-3">
                        <input type="number" class="form-control" wire:model.360s="read_permission" min="0" max="1">
                        @error('read_permission') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <label class="form-label">{{ __('Write Permission') }}</label>
                    <div class="mb-3">
                        <input type="number" class="form-control" wire:model.360s="write_permission" min="0" max="1">
                        @error('write_permission') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" onClick="@this.updateUser()">Save changes</button>
              </div>
            </div>
          </div>
        </div>
    </div>
    @push('scripts')
    <script>

        let debounce = -1;
        let isUpdate = false;

        let userListTable;
        let updateUserModal;

        document.addEventListener('livewire:load', function () {

            userListTable = document.getElementById('userListTable');
            updateUserModal = document.getElementById('updateUserModal');

            toLocalDateString()

            Livewire.hook('message.processed', component => {
                toLocalDateString();
                toggleUpdateVisibility();
            })

            @this.on('populated', () => {
                isUpdate = true;
                toggleUpdateVisibility()
            })

            @this.on('updated', () => {
                isUpdate = false;
                toggleUpdateVisibility();
                alert("{{ __('Success') }}")
                setTimeout(() => location.reload(), 100);
            })

            // TODO
            document.getElementById('userSearchInput').addEventListener('input', (evt) => {
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
                    if (urlParams.toString())
                      window.location.href = `${document.location.protocol}//${document.location.host}${document.location.pathname}?${urlParams.toString()}`
                    else
                      window.location.href = `${document.location.protocol}//${document.location.host}${document.location.pathname}`
                }, 500)
            });
        })

        function toLocalDateString() {
            let userdates = document.getElementsByClassName('user-date');
            Object.keys(userdates).forEach((i) => {
                const offset = -(new Date().getTimezoneOffset() * 60 * 1000);
                const d = new Date(userdates[i].getAttribute("data-date"));
                d.setTime(d.getTime() + offset);
                userdates[i].innerText = d.toLocaleString();
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

        function cancelUpdate() {
            isUpdate = false;
            toggleUpdateVisibility();
        }

        // TODO
        function deleteUser(user) {
            // @this.updateUser(user);
        }
    </script>
    @endpush
    @stack('scripts')
</div>