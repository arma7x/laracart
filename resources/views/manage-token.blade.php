@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between">
                    <h3>{{ __('Manage Personal Access Tokens') }}</h3>
                    <button class="btn btn-danger" onclick="removeTokens();">{{ __('Remove All') }}</button>
                </div>
                <div class="card-body">
                    <livewire:personal-access-token />
                </div>
            </div>
        </div>
    </div>
    @push('scripts-manage-token')
    <script>
        function removeTokens() {
            const conf = confirm("{{ __('Are you sure to continue this operation ?') }}");
            if (conf) {
                axios.post("{{ route('remove-tokens') }}")
                .finally(() => {
                    location.reload()
                });
            }
        }
    </script>
    @endpush
    @stack('scripts-manage-token')
</div>
@endsection
