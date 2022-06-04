<div>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <td>{{ __('Id') }}</td>
                    <td>{{ __('Device') }}</td>
                    <td>{{ __('Abilities') }}</td>
                    <td>{{ __('Last Used At') }}</td>
                    <td>{{ __('Created At') }}</td>
                    <td>{{ __('Updated At') }}</td>
                    <td>{{ __('Action') }}</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($tokens as $token)
                <tr>
                    <td>{{ $token->id }}</td>
                    <td>{{ $token->name }}</td>
                    <td>{!! implode(', ', $token->abilities) !!}</td>
                    <td>{{ $token->last_used_at }}</td>
                    <td>{{ $token->created_at }}</td>
                    <td>{{ $token->updated_at }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onClick="deleteToken({{ $token->id }})">{{ __('Remove') }}</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            @this.on('removed', () => {
                alert("{{ __('The operation was successful') }}")
                setTimeout(() => location.reload(), 100);
            });
        });

        function deleteToken(id) {
            const conf = confirm("{{ __('Are you sure to continue this operation ?') }}");
            if (!conf)
                return;
            @this.removeToken(id);
        }
    </script>
    @endpush
    @stack('scripts')
</div>
