
<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
        <div class="kt-header__brand-logo">
            <a href="/app">
                <img alt="Logo" class="custom-logo" src="<?php echo e(global_asset('assets/media/logo.png')); ?>" />
            </a>
        </div>
    </div>
</div>

<!-- end:: Header Mobile -->

<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav ">
            <li class="kt-menu__item kt-menu__item--rel <?php echo $__env->yieldContent('dashboard'); ?>" >
                <a href="/app" class="kt-menu__link <?php echo $__env->yieldContent('dashboard'); ?>"> <span class="kt-menu__link-text"><i class="fas fa-home align-self-center"></i> <?php echo e(__('Dashboard')); ?></span>
                </a>
            </li>
            <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD administrators')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('administrators'); ?>" >
                    <a href="/administrators" class="kt-menu__link <?php echo $__env->yieldContent('administrators'); ?>"><span class="kt-menu__link-text"><i class="fas fa-user-secret" aria-hidden="true"></i><?php echo e(__('Administrators')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD teachers')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('teachers'); ?>" >
                    <a href="/teachers" class="kt-menu__link <?php echo $__env->yieldContent('teachers'); ?>"><span class="kt-menu__link-text"><i class="fas fa-chalkboard-teacher align-self-center"></i> <?php echo e(__('Teachers')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD students')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('students'); ?>" >
                    <a href="/students" class="kt-menu__link <?php echo $__env->yieldContent('students'); ?>"><span class="kt-menu__link-text"><i class="fas fa-user-graduate align-self-center"></i> <?php echo e(__('Students')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD courses')): ?>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('courses'); ?>" >
                <a href="/courses" class="kt-menu__link <?php echo $__env->yieldContent('courses'); ?>"><span class="kt-menu__link-text"><i class="fas fa-book align-self-center"></i> <?php echo e(__('Courses')); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('canany', 'CRUD classrooms','CRUD sections')): ?>
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('classrooms'); ?>" >
                <a href="/classrooms" class="kt-menu__link <?php echo $__env->yieldContent('courses'); ?>">
                    <span class="kt-menu__link-text"><i class="fas fa-book align-self-center"></i><?php echo app('translator')->get('navs.classrooms'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD assigned courses')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('assigned-courses'); ?>" >
                    <a href="/assigned-courses" class="kt-menu__link <?php echo $__env->yieldContent('assigned-courses'); ?>"><span class="kt-menu__link-text"><i class="fas fa-book align-self-center"></i><?php echo e(__('Assigned Courses')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('can', 'Student assigned courses')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('assigned-courses-students'); ?>" >
                    <a href="/student/assigned-courses" class="kt-menu__link <?php echo $__env->yieldContent('assigned-courses-students'); ?>"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> <?php echo e(__('Assigned Courses')); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (\Illuminate\Support\Facades\Blade::check('plan', 'regular')): ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD schedules')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('schedules'); ?>" >
                        <a href="/schedules" class="kt-menu__link <?php echo $__env->yieldContent('schedules'); ?>"><span class="kt-menu__link-text"><i class="fas fa-clock align-self-center"></i> <?php echo e(__("Schedules")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(auth()->user()->hasRole('Teacher') || auth()->user()->hasRole('Student')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('schedules'); ?>" >
                        <a href="/my-schedule" class="kt-menu__link <?php echo $__env->yieldContent('schedules'); ?>"><span class="kt-menu__link-text"><i class="fas fa-clock align-self-center"></i> <?php echo e(__("Schedules")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD exams')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('exams'); ?>" >
                        <a href="/exams" class="kt-menu__link <?php echo $__env->yieldContent('exams'); ?>"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> <?php echo e(__("Exams")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD exams teachers')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('exams'); ?>" >
                        <a href="/exam-teachers" class="kt-menu__link <?php echo $__env->yieldContent('exams'); ?>"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> <?php echo e(__("Exams")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'Student exams')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('exams'); ?>" >
                        <a href="/exam-students" class="kt-menu__link <?php echo $__env->yieldContent('exams'); ?>"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> <?php echo e(__("Exams")); ?></span>
                        </a>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('results'); ?>" >
                        <a href="/results" class="kt-menu__link <?php echo $__env->yieldContent('results'); ?>"><span class="kt-menu__link-text"><i class="fas fa-book-open align-self-center"></i> <?php echo e(__("Results")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('plan', 'large')): ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'CRUD events')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('events'); ?>" >
                        <a href="/events" class="kt-menu__link <?php echo $__env->yieldContent('events'); ?>"><span class="kt-menu__link-text"><i class="fas fa-calendar-alt align-self-center"></i> <?php echo e(__('Events')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'View events')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('events'); ?>" >
                        <a href="/calendar" class="kt-menu__link <?php echo $__env->yieldContent('events'); ?>"><span class="kt-menu__link-text"><i class="fas fa-calendar-alt align-self-center"></i> <?php echo e(__('Events')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'Student applications')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('applications'); ?>" >
                        <a href="/leave-applications" class="kt-menu__link <?php echo $__env->yieldContent('applications'); ?>"><span class="kt-menu__link-text"><i class="fas fa-envelope align-self-center"></i> <?php echo e(__('Leave Applications')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'Leave applications')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('applications'); ?>" >
                        <a href="/applications" class="kt-menu__link <?php echo $__env->yieldContent('applications'); ?>"><span class="kt-menu__link-text"><i class="fas fa-envelope align-self-center"></i> <?php echo e(__('Leave Applications')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if(auth()->user()->hasRole('Teacher')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('meetings'); ?>" >
                        <a href="/meetings" class="kt-menu__link <?php echo $__env->yieldContent('meetings'); ?>"><span class="kt-menu__link-text"><i class="fas fa-users align-self-center"></i> <?php echo e(__("Virtual Classrooms")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(auth()->user()->hasRole('Student')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('meetings'); ?>" >
                        <a href="/my-meetings" class="kt-menu__link <?php echo $__env->yieldContent('meetings'); ?>"><span class="kt-menu__link-text"><i class="fas fa-users align-self-center"></i> <?php echo e(__("Virtual Classrooms")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if(auth()->user()->hasRole('Teacher')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('homeworks'); ?>" >
                    <a href="/homeworks" class="kt-menu__link <?php echo $__env->yieldContent('homeworks'); ?>"><span class="kt-menu__link-text"><i class="fas fa-book-reader align-self-center"></i> <?php echo e(__("Homeworks")); ?></span>
                    </a>
                </li>
                <?php endif; ?>
                <?php if(auth()->user()->hasRole('Student')): ?>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('homeworks'); ?>" >
                        <a href="/my-homeworks" class="kt-menu__link <?php echo $__env->yieldContent('homeworks'); ?>"><span class="kt-menu__link-text"><i class="fas fa-book-reader align-self-center"></i> <?php echo e(__("Homeworks")); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('can', 'View behaviours')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('behaviours'); ?>" >
                    <a href="/guardian/behaviours" class="kt-menu__link <?php echo $__env->yieldContent('behaviours'); ?>"><span class="kt-menu__link-text"><i class="fas fa-smile align-self-center"></i> <?php echo e(__('Behaviours')); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endif; ?>
            

            <?php if (\Illuminate\Support\Facades\Blade::check('can', 'Student assigned courses')): ?>
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('current-class'); ?>" >
                    <a href="/current-class" class="kt-menu__link <?php echo $__env->yieldContent('current-class'); ?>"><span class="kt-menu__link-text"><?php echo e(__('Go to class')); ?> &nbsp; <i class="fa fa-arrow-right"></i> </span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel <?php echo $__env->yieldContent('feedbacks'); ?>" >
                <a href="/feedbacks" class="kt-menu__link <?php echo $__env->yieldContent('feedbacks'); ?>"><span class="kt-menu__link-text"><i class="fas fa-sticky-note align-self-center"></i> <?php echo e(__('Feedbacks')); ?></span>
                </a>
            </li>
            
        </ul>
    </div>
</div>

<!-- end:: Aside Menu -->
</div>
<?php /**PATH E:\_CODING\_develop\tinel\resources\views/layouts/partials/nav.blade.php ENDPATH**/ ?>