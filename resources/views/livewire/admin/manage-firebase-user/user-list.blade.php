<div class="card">
    <div class="card-header d-flex flex-row justify-content-between">
        <h3>{{ __('Manage Firebase User') }}</h3>
        <input id="searchInput" type="text" class="form-control w-25" wire:model.debounce.1000ms="search" placeholder="{{ __('Search user') }}">
    </div>
    <div class="card-body">
        @if ($user === null)
        <h5 class="text-center">{{ __('Search user by uid, email or phone number') }}</h5>
        @elseif (gettype($user) === 'string')
        <h5 class="text-center font-weight-bold">{{ $user }}</h5>
        @else
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h4 class="modal-title font-weight-bold" id="modalLabel">{{ $user['uid'] }}</h4>
                </div>
                <div class="modal-body">
                    @if ($user['email'] !== null)
                        <h5>Email: {{ $user['email'] }}[{{ $user['emailVerified'] ? __('Verified') : __('Unverified') }}]</h5>
                    @endif
                    @if ($user['displayName'] !== null)
                        <h5>Display Name: {{ $user['displayName'] }}</h5>
                    @endif
                    @if ($user['phoneNumber'] !== null)
                        <h5>Phone Number: {{ $user['phoneNumber'] }}</h5>
                    @endif
                    <h5>Created At: <span class="user-date" data-date="{{ $user['metadata']->createdAt->format(DateTimeInterface::RFC3339) }}"></span></h5>
                    <h5>Last Login At: <span class="user-date" data-date="{{ $user['metadata']->lastLoginAt->format(DateTimeInterface::RFC3339) }}"></span></h5>
                    <h5>Last Refresh At: <span class="user-date" data-date="{{ $user['metadata']->lastRefreshAt->format(DateTimeInterface::RFC3339) }}"></span></h5>
                    <h5>Tokens Valid After Time: <span class="user-date" data-date="{{ $user['tokensValidAfterTime'] }}"></span></h5>
                    <h5>Provider: {{ strtoupper($user['providerData'][0]->providerId) }}</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm {{ $user['disabled'] ? 'btn-success' : 'btn-secondary' }}" onClick="setStatus('{{ $user['uid'] }}', {{ $user['disabled'] }})">
                        {{ $user['disabled'] ? __('Enable') : __('Disable') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" onClick="revokeRefreshTokens('{{ $user['uid'] }}')">
                        {{ __('Revoke Token') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onClick="deleteUser('{{ $user['uid'] }}')">
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@push('scripts-firebase-user-list')
<script>
    document.addEventListener('livewire:load', function () {

        toLocalDateString();

        Livewire.hook('message.processed', component => {
            toLocalDateString();
        })
    });

    function toLocalDateString() {
        let userdates = document.getElementsByClassName('user-date');
        Object.keys(userdates).forEach((i) => {
            userdates[i].innerText = new Date(userdates[i].getAttribute("data-date")).toLocaleString();
        })
    }

    function setStatus(uid, disabled) {
        if (disabled) {
            @this.enableUser(uid);
        } else {
            @this.disableUser(uid);
        }
    }

    function revokeRefreshTokens(uid) {
        @this.revokeRefreshTokens(uid);
    }

    function deleteUser(uid) {
        const conf = confirm("{{ __('Are you sure to continue this operation ?') }}");
        if (!conf)
            return;
        @this.deleteUser(uid);
    }
</script>
@endpush
@stack('scripts-firebase-user-list')
