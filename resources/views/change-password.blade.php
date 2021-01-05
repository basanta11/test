@extends('layouts.main')
@section('title','Change Password | '. config("app.name"))
@section('dashboard','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Dashboard'),
        'crumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => '/app'
            ],
            [
                'name' => __('Update Password'),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __('Update Password') }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" action="/change-password" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>{{__('Old Password')}}</label> 
                        <input type="password" name="old_password" class="form-control" placeholder="{{ __('Enter old password') }}...">
                    </div>
                    <div class="form-group">
                        <label>{{__('New Password')}}</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="{{ __('Enter new password') }}...">
                    </div>
                    <div class="form-group">
                        <label>{{__('Confirm Password')}}</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('Enter confirm password') }}...">
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        <a href="/app"><button type="button" class="btn btn-secondary">{{ __('Cancel') }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script>
    $('.kt-form').on('submit',function(e) {
            
        var btn = $('#kt_login_request_submit');
        var form = $(this);

        form.validate({
            rules: {
                old_password:{
                    required:true,
                },
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
            return false;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            async: false,
            url:'/is-password-matched/',
            dataType:'JSON',
            type:'POST',
            data:{
                old_password:$('input[name="old_password"').val(),
            },
            success:function(data){
                var elem=$('input[name="old_password"');
                if(data==false){

                    e.preventDefault();
                    elem.parent().addClass('is-invalid')
                    if($("#" + 'password-error-custom').length != 0) {
                        $('#password-error-custom').html('Your old password did not match. Please try again.');
                    }else{
                        elem.parent().append(`<div id="password-error-custom" class="error invalid-feedback">Your old password did not match. Please try again.</div>`)
                    }
                    elem.addClass('is-invalid')
                    elem.val("");
                }else{
                    return true;
                }
            }
        })

        return true;
    });
    $('input[name="old_password"]').on('focus',function(e){
        if($("#" + 'password-error-custom').length != 0) {
            $('input[name="old_password"]').removeClass('is-invalid')
            $("#password-error-custom").remove();
        }
    })

</script>
@endpush
