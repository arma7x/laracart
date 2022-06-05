@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between">
                    <h3>{{ __('Manage Personal Access Tokens') }}</h3>
                    <div class="d-flex">
                        <button class="btn btn-info me-1" onclick="generateToken();">{{ __('Generate Token') }}</button>
                        <button class="btn btn-danger" onclick="removeTokens();">{{ __('Remove All') }}</button>
                    </div>
                </div>
                <div class="card-body">
                    <livewire:personal-access-token />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
