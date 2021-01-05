<section id="courses" class="bg-lighter">
    <div class="container pb-60">
      <div class="section-title mb-10">
        <div class="row">
          <div class="col-md-8">
            <h2
              class="mt-0 text-uppercase font-28 line-bottom line-height-1"
            >
              Our
              <span class="text-theme-color-2 font-weight-400"
                >COURSES</span
              >
            </h2>
          </div>
        </div>
      </div>
      <div class="section-content">
        <div class="row">
          <div class="col-md-12">
            <div class="owl-carousel-4col" data-dots="true" data-nav="true"> 
              <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="/login">
                <div class="item custom-item">
                  <div class="service-block custom-service bg-white">
                    <div class="thumb">
                      <img
                        alt="featured project"
                        src="<?php echo e($course["image"]); ?>"
                        class="img-fullwidth custom-image"
                      />
                      <h4 class="text-white mt-0 mb-0">
                        <span class="price"><?php echo e($course["credit_hours"]); ?> hours</span>
                      </h4>
                    </div>
                    <div class="content custom-content pb-0 text-left flip p-25 pt-0">
                      <h4 class="line-bottom mb-10">
                        <?php echo e($course["title"]); ?>

                      </h4>
                      <p>
                        <?php echo e(mb_strimwidth($course['learn_what'], 0, 247, "...")); ?>

                      </p>
                    </div>
                  </div>
                </div>
              </a>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section><?php /**PATH E:\_CODING\_develop\tinel\resources\views/frontend/render/courses.blade.php ENDPATH**/ ?>