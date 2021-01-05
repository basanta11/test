<?php $__env->startSection('content'); ?>
<div class="kt-login__left">
    <div class="kt-login__wrapper">
        <div class="kt-login__content">
            <a class="kt-login__logo" href="/">
                <img alt="Logo" class="custom-logo" src="<?php echo e(global_asset('assets/media/logo.png')); ?>" />
            </a>
            <h3 class="kt-login__title"><?php echo e(config("app.name")); ?></h3>
            <span class="kt-login__desc">
                <span class="font-italic">“Tell me and I forget. Teach me and I remember. Involve me and I learn.”</span> –Benjamin Franklin
            </span>
            <div class="kt-login__actions">
                
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
                <h3 class="kt-login__title"><?php echo e(__('Login to your account')); ?></h3>
            </div>
            <div class="kt-login__form">
                <form class="kt-form" action="/login" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="<?php echo e(__('Email')); ?>..." name="email" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-last" type="password" placeholder="<?php echo e(__('Password')); ?>..." name="password">
                    </div>
                    <div class="row kt-login__extra">
                        <div class="col kt-align-left">
                            <label class="kt-checkbox">
                                <input type="checkbox" name="remember"> <?php echo e(__('Remember me')); ?>

                                <span></span>
                            </label>
                        </div>
                        <div class="col kt-align-right">
                            <a href="javascript:;" id="kt_login_forgot" class="kt-link"><?php echo e(__('Forget Password')); ?> ?</a>
                        </div>
                    </div>
                    <div class="kt-login__actions">
                        <button id="kt_login_signin_submit" type='submit' class="btn btn-brand btn-pill btn-elevate"><?php echo e(__('Sign in')); ?></button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="kt-login__forgot">
            <div class="kt-login__head">
                <h3 class="kt-login__title"><?php echo e(__("Forgotten Password ?")); ?></h3>
                <div class="kt-login__desc"><?php echo e(__("Enter your email to reset your password")); ?>:</div>
            </div>
            <div class="kt-login__form">
                <form class="kt-form" id="form-reset" method="POST" action="<?php echo e(route('password.email')); ?>">
                    <div class="err"></div>
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="<?php echo e(__("Email")); ?>" name="email" id="kt_email" autocomplete="off">
                    </div>
                    <div class="kt-login__actions">
                        <button id="kt_login_forgot_submit" class="btn btn-brand btn-pill btn-elevate"><?php echo e(__("Request")); ?></button>
                        <button id="kt_login_forgot_cancel" class="btn btn-outline-brand btn-pill"><?php echo e(__("Cancel")); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
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
            <?php if(Session::has('success')): ?>
                showErrorMsg($('.kt-login__form .kt-form'), 'success', '<?php echo e(Session::pull('success')); ?>');
            <?php endif; ?>
            <?php if(Session::has('error')): ?>
                showErrorMsg($('.kt-login__form .kt-form'), 'danger', '<?php echo e(Session::pull('error')); ?>');
            <?php endif; ?>
        })
         
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.auth.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\_CODING\_develop\tinel\resources\views/auth/login.blade.php ENDPATH**/ ?>