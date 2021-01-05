@extends('layouts.auth.app')

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
            <div class="kt-login__actions">
                {{-- <button type="button" id="kt_login_signup" class="btn btn-outline-brand btn-pill">Get An Account</button> --}}
                <div class="kt-login__actions">
                    <h5 class="kt-login__title">Select language: </h5>
                    <a type="button" href="/language/en" class="btn btn-outline-success btn-pill">English</a>
                    <a type="button" href="/language/th" class="btn btn-outline-success btn-pill">Thai</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="kt-login__divider">
    <div></div>
</div>
<div class="kt-login__right">
    <div class="kt-login__wrapper">
        

        <div class="kt-login__signin">
            <div class="kt-login__head">
                <h3 class="kt-login__title">{{ __('Login to your account') }}</h3>
            </div>
            <div class="kt-login__form">
                <form class="kt-form" action="/login" method="POST">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="{{ __('Email') }}..." name="email" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-last" type="password" placeholder="{{ __('Password') }}..." name="password">
                    </div>
                    <div class="row kt-login__extra">
                        <div class="col kt-align-left">
                            <label class="kt-checkbox">
                                <input type="checkbox" name="remember"> {{ __('Remember me') }}
                                <span></span>
                            </label>
                        </div>
                        <div class="col kt-align-right">
                            <a href="javascript:;" id="kt_login_forgot" class="kt-link">{{ __('Forget Password') }} ?</a>
                        </div>
                    </div>
                    <div class="kt-login__actions">
                        <button id="kt_login_signin_submit" type='submit' class="btn btn-brand btn-pill btn-elevate">{{ __('Sign in') }}</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- <div class="kt-login__signup">
            <div class="kt-login__head">
                <h3 class="kt-login__title">Sign Up</h3>
                <div class="kt-login__desc">Enter your details to create your account:</div>
            </div>
            <div class="kt-login__form">
                <form class="kt-form" action="/register" method='POST'>
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Fullname" name="name">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" placeholder="Password" name="password">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-last" type="password" placeholder="Confirm Password" name="password_confirmation">
                    </div>
                    <div class="row kt-login__extra">
                        <div class="col kt-align-left">
                            <label class="kt-checkbox">
                                <input type="checkbox" name="agree">I Agree the <a href="#" class="kt-link kt-login__link kt-font-bold">terms and conditions</a>.
                                <span></span>
                            </label>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                    <div class="kt-login__actions">
                        <button id="kt_login_signup_submit" type='submit' class="btn btn-brand btn-pill btn-elevate">Sign Up</button>&nbsp;&nbsp;
                        <button id="kt_login_signup_cancel" class="btn btn-outline-brand btn-pill">Cancel</button>
                    </div>
                </form>
            </div>
        </div> --}}
        <div class="kt-login__forgot">
            <div class="kt-login__head">
                <h3 class="kt-login__title">{{ __("Forgotten Password ?") }}</h3>
                <div class="kt-login__desc">{{ __("Enter your email to reset your password") }}:</div>
            </div>
            <div class="kt-login__form">
                <form class="kt-form" id="form-reset" method="POST" action="{{ route('password.email') }}">
                    <div class="err"></div>
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="{{ __("Email") }}" name="email" id="kt_email" autocomplete="off">
                    </div>
                    <div class="kt-login__actions">
                        <button id="kt_login_forgot_submit" class="btn btn-brand btn-pill btn-elevate">{{ __("Request") }}</button>
                        <button id="kt_login_forgot_cancel" class="btn btn-outline-brand btn-pill">{{ __("Cancel") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $(document).on('click','#kt_login_forgot_submit',function(){
                const btn=$(this);
                const email=$('#kt_email').val();
                console.log(email)
                $.ajax({
                    url: '/api/checkEmail/'+email,
                    dataType:'JSON',
                    type:'GET',
                    success:function(data){
                        console.log(data);
                        if(data.result==false){
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light');
                            btn.removeAttr('disabled');
                            $('.err').html(`<div class="kt-alert kt-alert--outline alert alert-danger alert-dismissible animated fadeIn" role="alert">           <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>           <span>Email  is not registered.</span>      </div>`)
                        }else{
                            $('#form-reset').submit();
                        }
                    }
                })
            });
            var showErrorMsg = function(form, type, msg) {
                var alert = $('<div class="alert alert-' + type + ' alert-dismissible" role="alert">\
                    <div class="alert-text">'+msg+'</div>\
                    <div class="alert-close">\
                        <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
                    </div>\
                </div>');
                form.find('.alert').remove();
                alert.prependTo(form);
            };
            @if(Session::has('success'))
                showErrorMsg($('.kt-login__form .kt-form'), 'success', '{{ Session::pull('success') }}');
            @endif
            @if(Session::has('error'))
                showErrorMsg($('.kt-login__form .kt-form'), 'danger', '{{ Session::pull('error') }}');
            @endif
        })
         
    </script>
@endpush