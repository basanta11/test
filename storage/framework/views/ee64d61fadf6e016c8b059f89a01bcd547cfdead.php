<section id="about" class="">
    <div class="container">
      <div class="section-content">
        <div class="row">
          <div class="col-md-5">
            <h6
              class="letter-space-4 text-gray-darkgray text-uppercase mt-0 mb-0"
            >
              All About
            </h6>
            <h2
              class="text-uppercase line-height-1 font-weight-600 mt-0 font-28 line-bottom"
            >
              <?php echo e($data['about-us']['title']); ?>

            </h2>
            <p>
              <?php echo e($data['about-us']['description']); ?>

            </p>
            <a
              href="#contact"
              class="btn btn-default btn-flat btn-lg mt-15"
              >Contact Us</a
            >
          </div>
          <div class="col-md-7">
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="mt-sm-60">
                  <img
                    src="<?php echo e($data['mission']['image']); ?>"
                    class="img-fullwidth custom-about-image"
                    alt=""
                  />
                </div>
                <h5 class="text-theme-color-2">Our Mission</h5>
                <p class="mb-5">
                  <?php echo nl2br($data['mission']['title']); ?>

                </p>
                <!-- <a
                  class="text-theme-colored font-13 font-weight-600"
                  href="#"
                  >View Details →</a
                > -->
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="mt-sm-40">
                  <img
                    src="<?php echo e($data['vision']['image']); ?>"
                    class="img-fullwidth custom-about-image"
                    alt=""
                  />
                </div>
                <h5 class="text-theme-color-2">Our Vision</h5>
                <p class="mb-5">
                  <?php echo nl2br($data['vision']['title']); ?>

                </p>
                <!-- <a
                  class="text-theme-colored font-13 font-weight-600"
                  href="#"
                  >View Details →</a
                > -->
              </div>
              <div class="col-md-4 col-sm-6">
                <div class="mt-sm-40">
                  <img
                    src="<?php echo e($data['goal']['image']); ?>"
                    class="img-fullwidth custom-about-image"
                    alt=""
                  />
                </div>
                <h5 class="text-theme-color-2">Our Goal</h5>
                <p class="mb-5">
                  <?php echo nl2br($data['goal']['title']); ?>

                </p>
                <!-- <a
                  class="text-theme-colored font-13 font-weight-600"
                  href="#"
                  >View Details →</a
                > -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section><?php /**PATH E:\_CODING\_develop\tinel\resources\views/frontend/render/about.blade.php ENDPATH**/ ?>