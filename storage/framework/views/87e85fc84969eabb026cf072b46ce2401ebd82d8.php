<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta
      name="description"
      content="StudyPress | Education & Courses HTML Template"
    />
    <meta
      name="keywords"
      content="academy, course, education, education html theme, elearning, learning,"
    />
    <meta name="author" content="ThemeMascot" />

    <!-- Page Title -->
    <title><?php echo e(config("app.name")); ?></title>

    <!-- Favicon and Touch Icons -->
    <link href="<?php echo e(global_asset('favicon.ico')); ?>" rel="shortcut icon" type="image/ico" />

    <link rel="manifest" href="/manifest.json" />

    <!-- Stylesheet -->
    <link href="<?php echo e(global_asset('assets/frontend/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(global_asset('assets/frontend/css/jquery-ui.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(global_asset('assets/frontend/css/animate.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(global_asset('assets/frontend/css/css-plugin-collections.css')); ?>" rel="stylesheet" />
    <!-- CSS | menuzord megamenu skins -->
    <link
      id="menuzord-menu-skins"
      href="<?php echo e(global_asset('assets/frontend/css/menuzord-skins/menuzord-rounded-boxed.css')); ?>"
      rel="stylesheet"
    />
    <!-- CSS | Main style file -->
    <link href="<?php echo e(global_asset('assets/frontend/css/style-main.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- CSS | Preloader Styles -->
    <link href="<?php echo e(global_asset('assets/frontend/css/preloader.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- CSS | Custom Margin Padding Collection -->
    <link
      href="<?php echo e(global_asset('assets/frontend/css/custom-bootstrap-margin-padding.css')); ?>"
      rel="stylesheet"
      type="text/css"
    />
    <!-- CSS | Responsive media queries -->
    <link href="<?php echo e(global_asset('assets/frontend/css/responsive.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
    <link href="<?php echo e(global_asset('assets/frontend/css/style.css')); ?>" rel="stylesheet" type="text/css">

    <!-- Revolution Slider 5.x CSS settings -->
    <link
      href="<?php echo e(global_asset('assets/frontend/js/revolution-slider/css/settings.css')); ?>"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="<?php echo e(global_asset('assets/frontend/js/revolution-slider/css/layers.css')); ?>"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="<?php echo e(global_asset('assets/frontend/js/revolution-slider/css/navigation.css')); ?>"
      rel="stylesheet"
      type="text/css"
    />

    <!-- CSS | Theme Color -->
    <link
      href="<?php echo e(global_asset('assets/frontend/css/colors/theme-skin-color-set-1.css')); ?>"
      rel="stylesheet"
      type="text/css"
    />
    <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Roboto&display=swap" rel="stylesheet"> 

    <style>
      body,p,h1,h2,h3,h4,h5,h6,span{
        font-family: 'Kanit', sans-serif !important;
      }
      /* primary */
      .bg-theme-colored {
          background-color: <?php echo e($data['color']['primary']); ?> !important;
      }

      /* secondary */
      .bg-theme-color-2, .line-bottom::after, .line-bottom-center::after {
          background: <?php echo e($data['color']['secondary']); ?>!important;
      }
      .text-theme-color-2, .widget .twitter-feed li::after, .work-gallery .gallery-bottom-part .title {
          color: <?php echo e($data['color']['secondary']); ?>!important;
      }
      .border-left-theme-color-2-4px, .border-left-theme-color-2-6px {
          border-left: 4px solid <?php echo e($data['color']['secondary']); ?>!important; 
      }
      .border-right-theme-color-2-6px, .border-right-theme-color-2-6px  {
          border-right: 6px solid <?php echo e($data['color']['secondary']); ?>!important;
      }
      .border-bottom-theme-color-2-1px {
          border-bottom: 1px solid <?php echo e($data['color']['secondary']); ?> !important;
      }
      .bg-deep{
        background-color: <?php echo e($data['color']['card']); ?> !important;
      }
      .custom-about-image{
        height: 197px !important;
        object-fit: cover;
      }
      .custom-description{
        white-space: pre-line !important;
      }
      .tp-bgimg {
        opacity: 0.5 !important;
        background-color: #000000 !important;
      }
      .slotholder {
        background: #000000;
      } 
      .custom-image{
        height: 273.75px !important;
        object-fit: cover;
        
      }
      .custom-content{
        border-bottom: none !important; 
        text-align: justify !important;
      }
      .custom-item, .custom-service{
        height: 550.667px !important;
      }
      /*  */
    </style>

    <!-- external javascripts -->
    <script src="<?php echo e(global_asset('assets/frontend/js/jquery-2.2.4.min.js')); ?>"></script>
    <script src="<?php echo e(global_asset('assets/frontend/js/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(global_asset('assets/frontend/js/bootstrap.min.js')); ?>"></script>
    <!-- JS | jquery plugin collection for this theme -->
    <script src="<?php echo e(global_asset('assets/frontend/js/jquery-plugin-collection.js')); ?>"></script>

    <!-- Revolution Slider 5.x SCRIPTS -->
    <script src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/jquery.themepunch.tools.min.js')); ?>"></script>
    <script src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/jquery.themepunch.revolution.min.js')); ?>"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') }}"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js') }}"></script>
    <![endif]-->
  </head>
  <body class="">
    <div id="wrapper" class="clearfix">
      <!-- preloader -->
      <div id="preloader">
        <div id="spinner">
          <div class="preloader-dot-loading">
            <div class="cssload-loading"><i></i><i></i><i></i><i></i></div>
          </div>
        </div>
        <!-- <div id="disable-preloader" class="btn btn-default btn-sm">
          Disable Preloader
        </div> -->
      </div>

      <!-- Header -->
      <header id="header" class="header">
        
        <div class="header-middle p-0 bg-lightest xs-text-center">
          <div class="container pt-0 pb-0">
            <div class="row">
              <div class="col-xs-12 col-sm-4 col-md-5">
                <div class="widget no-border m-0">
                  <a
                    class="menuzord-brand pull-left flip xs-pull-center mb-15"
                    href="javascript:void(0)"
                    ><img src="<?php echo e(global_asset('assets/media/logo.png')); ?>" alt=""
                  /></a>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6 col-md-7">
                <div
                  class="widget no-border pull-right sm-pull-none sm-text-center mt-10 mb-10 m-0"
                >
                  <ul class="list-inline">
                    <li>
                      <i
                        class="fa fa-phone-square text-theme-colored font-36 mt-5 sm-display-block"
                      ></i>
                    </li>
                    <li>
                      <a href="<?php echo e($data['contacts']['phone']!='' ? 'tel:'.$data['contacts']['phone'] : '#'); ?>" class="font-12 text-gray text-uppercase"
                        >Call us today!</a
                      >
                      <h5 class="font-14 m-0"><?php echo e($data['contacts']['phone']!='' ? $data['contacts']['phone'] : 'N/A'); ?></h5>
                    </li>
                  </ul>
                </div>
              </div>
              
            </div>
          </div>
        </div>
        <div class="header-nav">
          <div
            class="header-nav-wrapper navbar-scrolltofixed bg-theme-colored border-bottom-theme-color-2-1px"
          >
            <div class="container">
              <nav
              id="menuzord"
              class="menuzord bg-theme-colored pull-left flip menuzord-responsive"
              >
                <div class="visible-xs-block visible-sm-block">
                  <a
                    target="_blank"
                    class="btn btn-colored btn-flat bg-theme-color-2 text-white font-14 bs-modal-ajax-load mt-0 pt-15 pr-15 pl-15"
                    data-toggle="modal"
                    href="/login"
                    >Login</a
                  >
                </div>
                <ul class="menuzord-menu">
                  <li class="custom-nav active">
                    <a href="#home"
                      >Home</a
                    >
                  </li>

                  <li class="custom-nav">
                    <a href="#about">About</a>
                  </li>
                  <li class="custom-nav">
                    <a href="#courses">Courses</a>
                  </li>
                  <li class="custom-nav">
                    <a href="#teachers">Teachers</a>
                  </li>
                  
                  <li class="custom-nav">
                    <a href="#contact">Contact Us</a>
                  </li>
                </ul>
                <ul class="pull-right flip hidden-sm hidden-xs">
                  <li>
                    <!-- Modal: Book Now Starts -->
                    <a
                      target="_blank"
                      class="btn btn-colored btn-flat bg-theme-color-2 text-white font-14 bs-modal-ajax-load mt-0 p-25 pr-15 pl-15"
                      data-toggle="modal"
                      href="/login"
                      >Login</a
                    >
                    <!-- Modal: Book Now End -->
                  </li>
                </ul>
                <div id="top-search-bar" class="collapse">
                  <div class="container">
                    <form
                      role="search"
                      action="#"
                      class="search_form_top"
                      method="get"
                    >
                      <input
                        type="text"
                        placeholder="Type text and press Enter..."
                        name="s"
                        class="form-control"
                        autocomplete="off"
                      />
                      <span class="search-close"
                        ><i class="fa fa-search"></i
                      ></span>
                    </form>
                  </div>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </header>

      <!-- Start main-content -->
      <div class="main-content">
        <!-- Section: home -->
        
        <?php echo $__env->make('frontend.render.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Section: Mission Vision Goal -->
        <?php echo $__env->make('frontend.render.about', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Section: COURSES -->
        <?php echo $__env->make('frontend.render.courses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Section: team -->
        <?php echo $__env->make('frontend.render.teachers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Section: blog -->
        
        <?php echo $__env->make('frontend.render.news', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        

        <?php echo $__env->make('frontend.render.contact', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- end main-content -->
      </div>
      

      <!-- Footer -->
      <footer
        id="footer"
        class="footer divider layer-overlay overlay-dark-9"
        data-bg-img="<?php echo e(global_asset('assets/frontend/images/bg/bg2.jpg')); ?>"
      >
        <div class="container">
          <div class="row ">
            <div class="col-sm-6 col-md-4">
              <div class="widget dark">
                <img
                  class="mt-5 mb-20"
                  alt=""
                  src="<?php echo e(global_asset('assets/media/logo.png')); ?>"
                />
              </div>
              
            </div>
            
            <div class="col-sm-6 col-md-4">
              <p>
                <?php echo e($data['contacts']['address']!='' ? $data['contacts']['address'] : 'N/A'); ?>

              </p>
              <ul class="list-inline mt-5">
                <li class="m-0 pl-10 pr-10">
                  <i class="fa fa-phone text-theme-color-2 mr-5"></i>
                  <a class="text-gray" href="<?php echo e($data['contacts']['phone']!='' ? 'tel:'.$data['contacts']['phone'] : '#'); ?>"><?php echo e($data['contacts']['phone']!='' ? $data['contacts']['phone'] : 'N/A'); ?></a>
                </li>
                <li class="m-0 pl-10 pr-10">
                  <i class="fa fa-envelope-o text-theme-color-2 mr-5"></i>
                  <a class="text-gray" href="<?php echo e($data['contacts']['email']!='' ? 'mailto:'.$data['contacts']['email'] : '#'); ?>"
                    ><?php echo e($data['contacts']['email']!='' ? $data['contacts']['email'] : 'N/A'); ?></a
                  >
                </li>
                <li class="m-0 pl-10 pr-10">
                  <i class="fa fa-globe text-theme-color-2 mr-5"></i>
                  <a class="text-gray" 
                    href="
                    <?php (strpos($data['contacts']['website'], 'http') !== false 
                      ? $data['contacts']['website'] 
                      : '//'. $data['contacts']['website'])
                    ?>">
                    <?php echo e($data['contacts']['website']!='' ? $data['contacts']['website'] : 'N/A'); ?></a>
                </li>
              </ul>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="widget dark">
                <h5 class="widget-title mb-10">Connect With Us</h5>
                <ul class="styled-icons icon-bordered icon-sm">
                  <?php if($data['social-links']['facebook']): ?>
                    <li>
                      <a target="_blank" href="<?php echo e($data['social-links']['facebook']); ?>"><i class="fa fa-facebook"></i></a>
                    </li>
                  <?php endif; ?>
                  <?php if($data['social-links']['twitter']): ?>
                    <li>
                      <a target="_blank" href="<?php echo e($data['social-links']['twitter']); ?>"><i class="fa fa-twitter"></i></a>
                    </li>
                  <?php endif; ?>
                  <?php if($data['social-links']['skype']): ?>
                  <li>
                    <a target="_blank" href="<?php echo e($data['social-links']['skype']); ?>"><i class="fa fa-skype"></i></a>
                  </li>
                  <?php endif; ?>
                  <?php if($data['social-links']['youtube']): ?>
                  <li>
                    <a target="_blank" href="<?php echo e($data['social-links']['youtube']); ?>"><i class="fa fa-youtube"></i></a>
                  </li>
                  <?php endif; ?>
                  <?php if($data['social-links']['instagram']): ?>
                  <li>
                    <a target="_blank" href="<?php echo e($data['social-links']['instagram']); ?>"><i class="fa fa-instagram"></i></a>
                  </li>
                  <?php endif; ?>
                  <?php if($data['social-links']['line']): ?>
                  <li>
                    <a class="d-flex align-items-center" style="display: flex;
                    justify-content: center;
                    align-items: center;" target="_blank" href="<?php echo e($data['social-links']['line']); ?>"><svg aria-hidden="true" style="height: 16px !important; width: 16px !important" focusable="false" data-prefix="fab" data-icon="line" class="svg-inline--fa fa-line fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M272.1 204.2v71.1c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.1 0-2.1-.6-2.6-1.3l-32.6-44v42.2c0 1.8-1.4 3.2-3.2 3.2h-11.4c-1.8 0-3.2-1.4-3.2-3.2v-71.1c0-1.8 1.4-3.2 3.2-3.2H219c1 0 2.1.5 2.6 1.4l32.6 44v-42.2c0-1.8 1.4-3.2 3.2-3.2h11.4c1.8-.1 3.3 1.4 3.3 3.1zm-82-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 1.8 1.4 3.2 3.2 3.2h11.4c1.8 0 3.2-1.4 3.2-3.2v-71.1c0-1.7-1.4-3.2-3.2-3.2zm-27.5 59.6h-31.1v-56.4c0-1.8-1.4-3.2-3.2-3.2h-11.4c-1.8 0-3.2 1.4-3.2 3.2v71.1c0 .9.3 1.6.9 2.2.6.5 1.3.9 2.2.9h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.7-1.4-3.2-3.1-3.2zM332.1 201h-45.7c-1.7 0-3.2 1.4-3.2 3.2v71.1c0 1.7 1.4 3.2 3.2 3.2h45.7c1.8 0 3.2-1.4 3.2-3.2v-11.4c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2V234c0-1.8-1.4-3.2-3.2-3.2H301v-12h31.1c1.8 0 3.2-1.4 3.2-3.2v-11.4c-.1-1.7-1.5-3.2-3.2-3.2zM448 113.7V399c-.1 44.8-36.8 81.1-81.7 81H81c-44.8-.1-81.1-36.9-81-81.7V113c.1-44.8 36.9-81.1 81.7-81H367c44.8.1 81.1 36.8 81 81.7zm-61.6 122.6c0-73-73.2-132.4-163.1-132.4-89.9 0-163.1 59.4-163.1 132.4 0 65.4 58 120.2 136.4 130.6 19.1 4.1 16.9 11.1 12.6 36.8-.7 4.1-3.3 16.1 14.1 8.8 17.4-7.3 93.9-55.3 128.2-94.7 23.6-26 34.9-52.3 34.9-81.5z"></path></svg></a>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
            
          </div>
        </div>
        <div class="footer-bottom bg-black-333">
          <div class="container pt-20 pb-20">
            <div class="row">
              <div class="col-md-6">
                <p class="font-11 text-black-777 m-0">
                  Copyright &copy;2020 <?php echo e(config("app.name")); ?>. All Rights Reserved
                </p>
              </div>
              <!-- <div class="col-md-6 text-right">
                <div class="widget no-border m-0">
                  <ul class="list-inline sm-text-center mt-5 font-12">
                    <li>
                      <a href="#">FAQ</a>
                    </li>
                    <li>|</li>
                    <li>
                      <a href="#">Help Desk</a>
                    </li>
                    <li>|</li>
                    <li>
                      <a href="#">Support</a>
                    </li>
                  </ul>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </footer>
      <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
    </div>
    <!-- end wrapper -->

    <!-- Footer Scripts -->
    <!-- JS | Custom script for all pages -->
    <script src="<?php echo e(global_asset('assets/frontend/js/custom.js')); ?>"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  
      (Load Extensions only on Local File Systems ! 
       The following part can be removed on Server for On Demand Loading) -->
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.actions.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.carousel.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.migration.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.parallax.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js')); ?>"
    ></script>
    <script
      type="text/javascript"
      src="<?php echo e(global_asset('assets/frontend/js/revolution-slider/js/extensions/revolution.extension.video.min.js')); ?>"
    ></script>

    <script>
      $(document).on('click','.custom-nav ',function(e){
        $('.custom-nav').removeClass('active');
        $(this).addClass('active');
      })
    </script>
  </body>
</html><?php /**PATH E:\_CODING\_develop\tinel\resources\views/frontend/index.blade.php ENDPATH**/ ?>