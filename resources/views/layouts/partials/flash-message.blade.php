@push('scripts')
@if(Session::has('success'))
    <script type="text/javascript">
        toastr.success('{{ Session::pull('success') }}')
    </script>
@endif

@if(Session::has('error'))
    <script type="text/javascript">
        toastr.error('{{ Session::pull('error') }}')
    </script>
@endif
@endpush
@if ($errors->any())
    <div class="alert alert-light alert-elevate mb-0" role="alert">

        <div class="alert alert-danger w-100" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
@endif