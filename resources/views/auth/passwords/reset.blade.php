@extends('layouts.auth.app')
{{-- 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

@section('content')
<div class="kt-login__left">
    <div class="kt-login__wrapper">
        <div class="kt-login__content">
            <a class="kt-login__logo" href="/">
                <img alt="Logo" class="custom-logo" src="{{ global_asset('assets/media/logo.png') }}" />
            </a>
            <h3 class="kt-login__title">{{  config("app.name") }}</h3>
            <span class="kt-login__desc">
                <span class="font-italic">“Tell me and I forget. Teach me and I remember. Involve me and I learn.”</span> –Benjamin Franklin
            </span>
            {{-- <div class="kt-login__actions">
                <button type="button" id="kt_login_signup" class="btn btn-outline-brand btn-pill">Get An Account</button>
            </div> --}}
        </div>
    </div>
</div>
<div class="kt-login__divider">
    <div></div>
</div>
<div class="kt-login__right">
    <div class="kt-login__wrapper">
        
        @include('layouts.partials.flash-message')
        <div class="kt-login__signin">
            <div class="kt-login__head">
                <h3 class="kt-login__title">Reset your password</h3>
            </div>
            <div class="kt-login__form">
                <form class="kt-form" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Email..." value="{{ $email }}" readonly name="email" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-last" id="password" type="Password" placeholder="Enter New Password..." name="password">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-last" type="Password" placeholder="Confirm New Password..." name="password_confirmation">
                    </div>

                    <div class="kt-login__actions">
                        <button id="kt_login_request_submit" type='submit' class="btn btn-brand btn-pill btn-elevate">Request</button>
                        <button id="" onclick="location.href='/login'" type="button" class="btn btn-brand btn-pill btn-elevate">Back</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-' + type + ' alert-dismissible" role="alert">\
			<div class="alert-text">'+msg+'</div>\
			<div class="alert-close">\
                <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
            </div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        //alert.animateClass('fadeIn animated');
        KTUtil.animateClass(alert[0], 'fadeIn animated');
        alert.find('span').html(msg);
    }

    $('.kt-form').on('submit',function(e) {
        
        var btn = $('#kt_login_request_submit');
        var form = $(this);

        form.validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                }
            },
            messages: {
                password_confirmation: {
                    equalTo: 'Please enter the same password',
                }
            },

        });

        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();

            return false;
        }

        btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

        return true;
    });
</script>
@endpush