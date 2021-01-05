<?php $__env->startPush('scripts'); ?>
<?php if(Session::has('success')): ?>
    <script type="text/javascript">
        toastr.success('<?php echo e(Session::pull('success')); ?>')
    </script>
<?php endif; ?>

<?php if(Session::has('error')): ?>
    <script type="text/javascript">
        toastr.error('<?php echo e(Session::pull('error')); ?>')
    </script>
<?php endif; ?>
<?php $__env->stopPush(); ?>
<?php if($errors->any()): ?>
    <div class="alert alert-light alert-elevate mb-0" role="alert">

        <div class="alert alert-danger w-100" role="alert">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>

    </div>
<?php endif; ?><?php /**PATH E:\_CODING\_develop\tinel\resources\views/layouts/partials/flash-message.blade.php ENDPATH**/ ?>