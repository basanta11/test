@extends('layouts.auth.app')

@section('content')
<div class="container my-auto">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }} {{ __('Principal') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="citizen_number" class="col-md-4 col-form-label text-md-right">{{ __('Citizenship Number') }}</label>

                            <div class="col-md-6">
                                <input id="citizen_number" type="test" class="form-control @error('citizen_number') is-invalid @enderror" name="citizen_number" value="{{ old('citizen_number') }}" autocomplete="citizen_number">

                                @error('citizen_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                <select name="gender" class="form-control" required>
                                    <option value="" selected disabled>{{ __("Select gender") }}...</option>
                                    <option value="0">{{ __("Male") }}</option>
                                    <option value="1">{{ __("Female") }}</option>
                                    <option value="2">{{ __("Other") }}</option>
                                </select>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" min="1" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <input id="address" autocomplete="off" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address">

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="school_name" class="col-md-4 col-form-label text-md-right">{{ __('School Name') }}</label>

                            <div class="col-md-6">
                                <input id="school_name" type="text" class="form-control @error('school_name') is-invalid @enderror" name="school_name" value="{{ old('school_name') }}" required autocomplete="school_name">

                                @error('school_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Photo') }}</label>

                            <div class="col-md-6">
                                <div class="custom-file">
                                    <input accept="image/*" type="file" name="photo" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ global_asset('assets/js/thai-validator-function.min.js') }}" type="text/javascript"></script>
{{-- thailand address script --}}
<link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script src="{{ global_asset('assets/js/thai-address/JQL.min.js') }}" type="text/javascript"></script>
<script src="{{ global_asset('assets/js/thai-address/jquery.Thailand.min.js') }}" type="text/javascript"></script>
<script>
    $.Thailand({
        $search: $('input[name="address"]'),
        $full_address: $('input[name="address'),
        
        onDataFill: function(data){
            full_address=`${data.district}, ${data.amphoe}, ${data.province}, ${data.zipcode}`;
        },
    });   
</script>
{{-- end --}}
<script>
 $('input[name="citizen_number"]').inputmask({
        "mask": "9-9999-99999-99-9",
    }); 
    $('input[name="citizen_number"]').on('focusout',function(e){
        var elem=$(this)
        var unmasked=Inputmask.unmask($(this).val(), { alias: "9-9999-99999-99-9"})
        if(!ThaiNationalID(unmasked)){
            // console.log()
            toastr.error('Invalid citizen number.');
            elem.val('')
        }
        $.ajax({
            url:'/api/citizen-number-exists/',
            dataType:'JSON',
            type:'GET',
            data:{
                citizen_number:elem.val(),
            },
            success:function(data){
                console.log(data);
                if(data.status){
                    toastr.error('Citizen number already exists.')
                    elem.val('')
                }
            }
        })
    });

</script>
@endpush