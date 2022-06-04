@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between">
                    <h3>{{ __('Manage Personal Access Tokens') }}</h3>
                    <div>
                        <a class="btn btn-warning" href="{{ route('remove-tokens') }}"
                           onclick="event.preventDefault();document.getElementById('remove-tokens-form').submit();">
                            {{ __('Remove All') }}
                        </a>

                        <form id="remove-tokens-form" action="{{ route('remove-tokens') }}" method="POST" class="d-none">
                            @csrf
                        </form>
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
