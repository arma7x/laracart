<div class="card">
    <div class="card-header d-flex flex-row justify-content-between">
        <h3>{{ __('Manage Firebase User') }}</h3>
        <input id="searchInput" type="text" class="form-control w-25" wire:model.debounce.500ms="search" placeholder="{{ __('Search user') }}">
    </div>
    <div class="card-body">
        {{ var_dump($user) }}
    </div>
</div>
@push('scripts-firebase-user-list')
<script>
    document.addEventListener('livewire:load', function () {})
</script>
@endpush
@stack('scripts-firebase-user-list')
