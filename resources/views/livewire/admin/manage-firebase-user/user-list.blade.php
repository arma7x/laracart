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
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Email:</h5>
                        <h5 style="font-weight:bold;">{{ $user['email'] }}[{{ $user['emailVerified'] ? __('Verified') : __('Unverified') }}]</h5>
                    </div>
                    @endif
                    @if ($user['displayName'] !== null)
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Display Name:</h5>
                        <h5 style="font-weight:bold;">{{ $user['displayName'] }}</h5>
                    </div>
                    @endif
                    @if ($user['phoneNumber'] !== null)
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Phone Number:</h5>
                        <h5 style="font-weight:bold;"> {{ $user['phoneNumber'] }}</h5>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Created At:</h5>
                        <h5 style="font-weight:bold;"><span class="user-date" data-date="{{ $user['metadata']->createdAt->format(DateTimeInterface::RFC3339) }}"></span></h5>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Last Login At:</h5>
                        <h5 style="font-weight:bold;"><span class="user-date" data-date="{{ $user['metadata']->lastLoginAt->format(DateTimeInterface::RFC3339) }}"></span></h5>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Last Refresh At:</h5>
                        <h5 style="font-weight:bold;"><span class="user-date" data-date="{{ $user['metadata']->lastRefreshAt->format(DateTimeInterface::RFC3339) }}"></span></h5>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Tokens Valid After Time:</h5>
                        <h5 style="font-weight:bold;"><span class="user-date" data-date="{{ $user['tokensValidAfterTime'] }}"></span></h5>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h5 style="margin-bottom: -2px!important;">Provider:</h5>
                        <h5 style="font-weight:bold;">{{ strtoupper($user['providerData'][0]->providerId) }}</h5>
                    </div>
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
