<div>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <td>Id</td>
                <td>Name</td>
                <td>Email</td>
                <td>Email Verified At</td>
                <td>Access Level</td>
                <td>Read Permission</td>
                <td>Write Permission</td>
                <td>Created At</td>
                <td>Updated At</td>
                <td>Action</td>
            </thead>
            <tbody>
                @foreach ($user_list as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ is_null($user->email_verified_at) || !$user->email_verified_at ? "!" : "✔" }}</td>
                    <td>{{ $user->access_level }}</td>
                    <td>{{ $user->read_permission }}</td>
                    <td>{{ $user->write_permission }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->updated_at }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onClick="onSetCursor({{ $user->toJson() }})">Set Cursor</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $user_list->links() }}
    <div class="mt-2">
        <div>Cursor => {{ json_encode($user_cursor) }}</div>
        <button class="btn btn-sm btn-info" onClick="onSetCursor({})">Remove Cursor</button>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            @this.on('user-updated', () => {
                console.log('send req to trigger will functionName on server')
            })
        })

        function onSetCursor(user) {
            @this.updateUser(user);
        }
    </script>
    @endpush
    @stack('scripts')
</div>
