<div>
    <div class="row">
    @foreach ($user_list as $user)
        <div>{{ $user->id }}, {{ $user->name }}, {{ $user->email }}, {{ $user->access_level }}, {{ $user->read_permission }}, {{ $user->write_permission }}</div>
        <div>{{ $user->toJson() }}</div>
        <button class="btn btn-sm btn-info" onClick="onSetCursor({{ $user->toJson() }})">Set Cursor</button>
    @endforeach
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
