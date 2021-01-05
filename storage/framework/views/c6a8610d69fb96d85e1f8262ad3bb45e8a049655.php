<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                <?php echo e($breadTitle); ?> </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="/app" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
    
                <?php $last = end($crumbs); ?>
                <?php $__currentLoopData = $crumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="<?php echo e($crumb['url']); ?>" class="kt-subheader__breadcrumbs-link <?php if($crumb == $last): ?> kt-subheader__breadcrumbs-link--active <?php endif; ?>"><?php echo e($crumb['name']); ?> </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    
</div><?php /**PATH E:\_CODING\_develop\tinel\resources\views/layouts/partials/breadcrumbs.blade.php ENDPATH**/ ?>