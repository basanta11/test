<section id="contact" class="divider">
  <div class="container">
    <div class="row pt-30">
      <div class="col-md-4">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="icon-box left media bg-deep p-30 mb-20">
              <a class="media-left pull-left" href="#">
                <i class="pe-7s-map-2 text-theme-colored"></i
              ></a>
              <div class="media-body">
                <strong>OUR OFFICE LOCATION</strong>
                <p><?php echo e($data['contacts']['address']!='' ? $data['contacts']['address'] : 'N/A'); ?></p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-12">
            <div class="icon-box left media bg-deep p-30 mb-20">
              <a class="media-left pull-left" href="<?php echo e($data['contacts']['phone']!='' ? 'tel:'.$data['contacts']['phone'] : '#'); ?>">
                <i class="pe-7s-call text-theme-colored"></i
              ></a>
              <div class="media-body">
                <strong>OUR CONTACT NUMBER</strong>
                <p><?php echo e($data['contacts']['phone']!='' ? $data['contacts']['phone'] : 'N/A'); ?></p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6 col-md-12">
            <div class="icon-box left media bg-deep p-30 mb-20">
              <a class="media-left pull-left" href="<?php echo e($data['contacts']['email']!='' ? 'mailto:'.$data['contacts']['email'] : '#'); ?>">
                <i class="pe-7s-mail text-theme-colored"></i
              ></a>
              <div class="media-body">
                <strong>OUR CONTACT E-MAIL</strong>
                <p><?php echo e($data['contacts']['email']!='' ? $data['contacts']['email'] : 'N/A'); ?></p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      <div class="col-md-8">
        <h3 class="line-bottom mt-0 mb-20">
          Interested in discussing?
        </h3>
        
        <!-- Contact Form -->
        <form
          id="contact_form"
          name="contact_form"
          class=""
          action="/mail"
          method="post"
        >
          <?php echo csrf_field(); ?>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <input
                  name="name"
                  class="form-control"
                  type="text"
                  placeholder="<?php echo e(__("Enter name")); ?>"
                  required=""
                />
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <input
                  name="email"
                  class="form-control required email"
                  type="email"
                  placeholder="Enter Email"
                />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <input
                  name="subject"
                  class="form-control required"
                  type="text"
                  placeholder="Enter Subject"
                />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <input
                  name="phone"
                  class="form-control required"
                  type="text"
                  placeholder="Enter Phone"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <textarea
              name="message"
              class="form-control required"
              rows="5"
              placeholder="Enter Message"
            ></textarea>
          </div>
          <div class="form-group">
            <input
              name="form_botcheck"
              class="form-control"
              type="hidden"
              value=""
            />
            <button
              type="submit"
              class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px"
              data-loading-text="Please wait..."
            >
              Send your message
            </button>
            <button
              type="reset"
              class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px"
            >
              Reset
            </button>
          </div>
        </form>

        <!-- Contact Form Validation-->
        <script type="text/javascript">
          $("#contact_form").validate({
            submitHandler: function (form) {
              var form_btn = $(form).find('button[type="submit"]');
              var form_result_div = "#form-result";
              $(form_result_div).remove();
              form_btn.before(
                '<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>'
              );
              var form_btn_old_msg = form_btn.html();
              form_btn.html(
                form_btn.prop("disabled", true).data("loading-text")
              );
              $(form).ajaxSubmit({
                dataType: "json",
                success: function (data) {
                  if (data.status) {
                    $(form).find(".form-control").val("");
                    $('#form-result').addClass(`alert alert-success`)
                  }else{

                    $('#form-result').addClass(`alert alert-danger`)
                  }
                  form_btn
                    .prop("disabled", false)
                    .html(form_btn_old_msg);
                  $(form_result_div).html(data.message).fadeIn("slow");
                  setTimeout(function () {
                    $(form_result_div).fadeOut("slow");
                  }, 6000);
                },
              });
            },
          });
        </script>
      </div>
    </div>
  </div>
</section>

<!-- Divider: Google Map -->
<section>
  <div class="container-fluid p-0">
    <div class="row">
      <!-- Google Map HTML Codes -->
      <?php echo $data['map']; ?>

      
    </div>
  </div>
</section><?php /**PATH E:\_CODING\_develop\tinel\resources\views/frontend/render/contact.blade.php ENDPATH**/ ?>