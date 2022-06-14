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
                    <td class="token-date" data-date="{{ $token->last_used_at }}"></td>
                    <td class="token-date" data-date="{{ $token->created_at }}"></td>
                    <td class="token-date" data-date="{{ $token->updated_at }}"></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onClick="removeToken({{ $token->id }})">{{ __('Remove') }}</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @include('widgets.qrcode-modal')
    </div>
    @push('scripts-manage-token-lw')
    <script>
        document.addEventListener('livewire:load', function () {
            @this.on('removed', () => {
                alert("{{ __('The operation was successful') }}");
                toLocalDateString();
            });

            @this.on('error-generate', (errors) => {
                console.log(errors)
                if (errors.password)
                    alert(errors.password[0]);
                toLocalDateString();
            });

            @this.on('token-generated', (token) => {
                generateTokenQrCode(token);
                toLocalDateString();
            });

            toLocalDateString();
        });

        function toLocalDateString() {
            let userdates = document.getElementsByClassName('token-date');
            Object.keys(userdates).forEach((i) => {
                const offset = -(new Date().getTimezoneOffset() * 60 * 1000);
                const d = new Date(userdates[i].getAttribute("data-date"));
                d.setTime(d.getTime() + offset);
                userdates[i].innerText = d.toLocaleString();
            })
        }

        function removeToken(id) {
            const conf = confirm("{{ __('Are you sure to continue this operation ?') }}");
            if (!conf)
                return;
            @this.removeToken(id);
        }

        function removeTokens() {
            const conf = confirm("{{ __('Are you sure to continue this operation ?') }}");
            if (conf) {
                @this.removeTokens();
            }
        }

        function generateToken() {
            const password = prompt("{{ __('Please enter your password') }}");
            if (!password)
                return
            const device = prompt("{{ __('Please enter device name') }}", "{{ config('app.name', 'Laravel') }}");
            @this.generateToken(password, device);
        }

    </script>
    @endpush
    @stack('scripts-manage-token-lw')
</div>
