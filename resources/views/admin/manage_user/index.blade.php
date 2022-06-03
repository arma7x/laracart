@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Manage User') }}</div>
                <div class="card-body">
                    <livewire:admin.manage-user.user-list />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
