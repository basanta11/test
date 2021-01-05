
    <!-- begin:: Header -->
    <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed " data-ktheader-minimize="on">
        <div class="kt-header__top">
            <div class="kt-container ">

                <!-- begin:: Brand -->
                <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
                    <div class="kt-header__brand-logo">
                        
                    </div>
                </div>

                <!-- end:: Brand -->

                <!-- begin:: Header Topbar -->
                <div class="kt-header__topbar">
                    
                    <?php if (\Illuminate\Support\Facades\Blade::check('plan', 'large')): ?>
                    <notification :unreads="<?php echo e(auth()->user()->unreadNotifications); ?>" :notifications="<?php echo e(auth()->user()->notifications); ?>" :user="<?php echo e(auth()->user()->id); ?>">
                    </notification>
                    <?php endif; ?>

                    <div class="kt-header__topbar-item kt-header__topbar-item--langs">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                            <span class="kt-header__topbar-icon">
                                <img class="" src="<?php if( App::isLocale('en') ): ?> <?php echo e(global_asset('assets/media/flags/226-united-states.svg')); ?> <?php else: ?> <?php echo e(global_asset('assets/media/flags/238-thailand.svg')); ?> <?php endif; ?>" alt="">
                            </span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
                            <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                                <li class="kt-nav__item <?php if( App::isLocale('en') ): ?> kt-nav__item--active <?php endif; ?>">
                                    <a href="/language/en" class="kt-nav__link">
                                        <span class="kt-nav__link-icon"><img src="<?php echo e(global_asset('assets/media/flags/226-united-states.svg')); ?>" alt=""></span>
                                        <span class="kt-nav__link-text">English</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item <?php if( App::isLocale('th') ): ?> kt-nav__item--active <?php endif; ?>">
                                    <a href="/language/th" class="kt-nav__link">
                                        <span class="kt-nav__link-icon"><img src="<?php echo e(global_asset('assets/media/flags/238-thailand.svg')); ?>" alt=""></span>
                                        <span class="kt-nav__link-text">Thai</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!--begin: Search -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--search dropdown kt-hidden-desktop" id="kt_quick_search_toggle">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,10px">
                            <span class="kt-header__topbar-icon kt-header__topbar-icon--success">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>

                                <!--<i class="flaticon2-search-1"></i>-->
                            </span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-lg">
                            <div class="kt-quick-search kt-quick-search--dropdown kt-quick-search--result-compact" id="kt_quick_search_dropdown">
                                <form method="get" class="kt-quick-search__form">
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="flaticon2-search-1"></i></span></div>
                                        <input type="text" class="form-control kt-quick-search__input" placeholder="Search...">
                                        <div class="input-group-append"><span class="input-group-text"><i class="la la-close kt-quick-search__close"></i></span></div>
                                    </div>
                                </form>
                                <div class="kt-quick-search__wrapper kt-scroll" data-scroll="true" data-height="325" data-mobile-height="200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end: Search -->

                    

                    <!--begin: User bar -->
                    <div class="kt-header__topbar-item kt-header__topbar-item--user">
                        <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,10px">
                            <span class="kt-hidden kt-header__topbar-welcome">Hi,</span>
                            <span class="kt-hidden kt-header__topbar-username">Nick</span>
                            <img class="kt-hidden-" alt="Pic" 
                            src="<?php echo e(auth()->user()->image &&  Storage::disk(config('app.storage_driver'))->exists('users/'.auth()->user()->image) ? 
                                Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.auth()->user()->image) 
                                : global_asset('assets/media/users/default.jpg')); ?>" />
                            <span class="kt-header__topbar-icon kt-header__topbar-icon--brand kt-hidden"><b>S</b></span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

                            <!--begin: Head -->
                            <div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x">
                                <div class="kt-user-card__avatar">
                                    <img class="kt-hidden-" alt="Pic" 
                                    src="<?php echo e(auth()->user()->image &&  Storage::disk(config('app.storage_driver'))->exists('users/'.auth()->user()->image) ? 
                                    Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.auth()->user()->image) 
                                    : global_asset('assets/media/users/default.jpg')); ?>" />

                                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                    <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden">S</span>
                                </div>
                                <div class="kt-user-card__name">
                                    <?php echo e(auth()->user()->name); ?>

                                </div>
                            </div>

                            <!--end: Head -->

                            <!--begin: Navigation -->
                            <div class="kt-notification">
                                
                                <a href="/change-password/" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-user-1 kt-font-warning"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            <?php echo e(__('Change Password')); ?>

                                        </div>
                                        <div class="kt-notification__item-time">
                                            <?php echo e(__('Update your current password')); ?>

                                        </div>
                                    </div>
                                </a>
                                <?php if(auth()->user()->hasRole('Principal') ): ?>
                                <a href="/settings/frontend" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-website kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            <?php echo e(__('Website Setting')); ?>

                                        </div>
                                        <div class="kt-notification__item-time">
                                            <?php echo e(__('Update your website settings')); ?>

                                        </div>
                                    </div>
                                </a>
                                <a href="/settings/backend" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="
                                        flaticon2-rocket kt-font-danger"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title kt-font-bold">
                                            <?php echo e(__('Theme Setting')); ?>

                                        </div>
                                        <div class="kt-notification__item-time">
                                            <?php echo e(__('Update your theme settings')); ?>

                                        </div>
                                    </div>
                                </a>
                                <?php endif; ?>
                                
                                <?php if (\Illuminate\Support\Facades\Blade::check('plan', 'large')): ?>
                                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD behaviours')): ?>
                                    <a href="/behaviours" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-user kt-font-info"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title kt-font-bold">
                                                <?php echo e(__('Student Behaviours')); ?>

                                            </div>
                                            <div class="kt-notification__item-time">
                                                <?php echo e(__('Update your student\'s behaviours and marks')); ?>

                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD behaviour types')): ?>
                                    <a href="/behaviour-types" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-user kt-font-info"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title kt-font-bold">
                                                <?php echo e(__('Behaviour Types')); ?>

                                            </div>
                                            <div class="kt-notification__item-time">
                                                <?php echo e(__('Update your behaviour types')); ?>

                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'Leave applications')): ?>
                                    <a href="/applications" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-mail kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title kt-font-bold">
                                                <?php echo e(__('Leave Requests')); ?>

                                            </div>
                                            <div class="kt-notification__item-time">
                                                <?php echo e(__('Update your student\'s leave requests')); ?>

                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'Student applications')): ?>
                                    <a href="/leave-applications" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-mail kt-font-danger"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title kt-font-bold">
                                                <?php echo e(__('Leave Applications')); ?>

                                            </div>
                                            <div class="kt-notification__item-time">
                                                <?php echo e(__('Show your list of leave applications')); ?>

                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <?php if( auth()->user()->hasRole('Teacher') || auth()->user()->hasRole('Student') ): ?>
                                    <a href="/chat" class="kt-notification__item">
                                        <div class="kt-notification__item-icon">
                                            <i class="flaticon2-chat kt-font-success"></i>
                                        </div>
                                        <div class="kt-notification__item-details">
                                            <div class="kt-notification__item-title kt-font-bold">
                                                <?php echo e(__('Chat')); ?>

                                            </div>
                                            <div class="kt-notification__item-time">
                                                <?php echo e(__('Chat for teachers and students')); ?>

                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <?php endif; ?>

                                <div class="kt-notification__custom kt-space-between">
                                    <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-clean btn-sm btn-bold"><?php echo e(__('Sign Out')); ?></a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </div>

                            <!--end: Navigation -->
                        </div>
                    </div>

                    <!--end: User bar -->
                </div>


                <!-- end:: Header Topbar -->
            </div>
        </div>
        
    </div>

    <!-- end:: Header --><?php /**PATH E:\_CODING\_develop\tinel\resources\views/layouts/partials/header.blade.php ENDPATH**/ ?>