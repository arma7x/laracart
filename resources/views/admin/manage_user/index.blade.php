@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between">
                    <h3>{{ __('Manage User') }}</h3>
                    <input id="userSearchInput" type="text" class="form-control w-25" value="{{ request()->query('search') }}" placeholder="{{ __('Search user') }}">
                </div>
                <div class="card-body">
                    <livewire:admin.manage-user.user-list />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
