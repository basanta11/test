<section id="teachers">

    <div class="container">
      <div class="section-title mb-10">
        <div class="row">
          <div class="col-md-8">
            <h2
              class="mt-0 text-uppercase font-28 line-bottom line-height-1"
            >
              Our
              <span class="text-theme-color-2 font-weight-400"
                >Teachers</span
              >
            </h2>
          </div>
        </div>
      </div>
        
      <div class="section-content">
        <div class="row">
          <div class="col-md-12">
            <div class="owl-carousel-4col" data-dots="true" data-nav="true">
              <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="item">
                <div class="service-block bg-white">
                  <div class="thumb">
                    <img
                      alt="featured project"
                      src="<?php echo e($teacher['image']); ?>"
                      class="img-fullwidth custom-image"
                    />
                  </div>
                  <div class="content text-left flip p-25 pt-0">
                    <h4 class="line-bottom mb-10">
                      <?php echo e($teacher['name']); ?>

                    </h4>
                    <p>
                      <?php echo e($teacher['email']); ?>

                    </p>
                  </div>
                </div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        </div>
      </div>
    
  </section><?php /**PATH E:\_CODING\_develop\tinel\resources\views/frontend/render/teachers.blade.php ENDPATH**/ ?>