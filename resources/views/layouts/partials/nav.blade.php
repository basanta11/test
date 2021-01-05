
<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
        <div class="kt-header__brand-logo">
            <a href="/app">
                <img alt="Logo" class="custom-logo" src="{{ global_asset('assets/media/logo.png') }}" />
            </a>
        </div>
    </div>
</div>

<!-- end:: Header Mobile -->

<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
    <div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
        <ul class="kt-menu__nav ">
            <li class="kt-menu__item kt-menu__item--rel @yield('dashboard')" >
                <a href="/app" class="kt-menu__link @yield('dashboard')"> <span class="kt-menu__link-text"><i class="fas fa-home align-self-center"></i> {{ __('Dashboard') }}</span>
                </a>
            </li>
            @can('CRUD administrators')
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('administrators')" >
                    <a href="/administrators" class="kt-menu__link @yield('administrators')"><span class="kt-menu__link-text"><i class="fas fa-user-secret" aria-hidden="true"></i>{{ __('Administrators') }}</span>
                    </a>
                </li>
            @endcan
            @can('CRUD teachers')
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('teachers')" >
                    <a href="/teachers" class="kt-menu__link @yield('teachers')"><span class="kt-menu__link-text"><i class="fas fa-chalkboard-teacher align-self-center"></i> {{ __('Teachers') }}</span>
                    </a>
                </li>
            @endcan
            @can('CRUD students')
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('students')" >
                    <a href="/students" class="kt-menu__link @yield('students')"><span class="kt-menu__link-text"><i class="fas fa-user-graduate align-self-center"></i> {{ __('Students') }}</span>
                    </a>
                </li>
            @endcan
            @can('CRUD courses')
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('courses')" >
                <a href="/courses" class="kt-menu__link @yield('courses')"><span class="kt-menu__link-text"><i class="fas fa-book align-self-center"></i> {{ __('Courses') }}</span>
                </a>
            </li>
            @endcan
            @canany('CRUD classrooms','CRUD sections')
            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('classrooms')" >
                <a href="/classrooms" class="kt-menu__link @yield('courses')">
                    <span class="kt-menu__link-text"><i class="fas fa-book align-self-center"></i>@lang('navs.classrooms')</span>
                </a>
            </li>
            @endcanany
            @can('CRUD assigned courses')
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('assigned-courses')" >
                    <a href="/assigned-courses" class="kt-menu__link @yield('assigned-courses')"><span class="kt-menu__link-text"><i class="fas fa-book align-self-center"></i>{{ __('Assigned Courses') }}</span>
                    </a>
                </li>
            @endcan
            @can('Student assigned courses')
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('assigned-courses-students')" >
                    <a href="/student/assigned-courses" class="kt-menu__link @yield('assigned-courses-students')"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> {{ __('Assigned Courses') }}</span>
                    </a>
                </li>
            @endcan
            @plan('regular')
                @can('CRUD schedules')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('schedules')" >
                        <a href="/schedules" class="kt-menu__link @yield('schedules')"><span class="kt-menu__link-text"><i class="fas fa-clock align-self-center"></i> {{ __("Schedules") }}</span>
                        </a>
                    </li>
                @endcan
                @if(auth()->user()->hasRole('Teacher') || auth()->user()->hasRole('Student'))
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('schedules')" >
                        <a href="/my-schedule" class="kt-menu__link @yield('schedules')"><span class="kt-menu__link-text"><i class="fas fa-clock align-self-center"></i> {{ __("Schedules") }}</span>
                        </a>
                    </li>
                @endif
                @can('CRUD exams')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('exams')" >
                        <a href="/exams" class="kt-menu__link @yield('exams')"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> {{ __("Exams") }}</span>
                        </a>
                    </li>
                @endcan
                @can('CRUD exams teachers')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('exams')" >
                        <a href="/exam-teachers" class="kt-menu__link @yield('exams')"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> {{ __("Exams") }}</span>
                        </a>
                    </li>
                @endcan
                @can('Student exams')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('exams')" >
                        <a href="/exam-students" class="kt-menu__link @yield('exams')"><span class="kt-menu__link-text"><i class="fas fa-pencil-ruler align-self-center"></i> {{ __("Exams") }}</span>
                        </a>
                    </li>
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('results')" >
                        <a href="/results" class="kt-menu__link @yield('results')"><span class="kt-menu__link-text"><i class="fas fa-book-open align-self-center"></i> {{ __("Results") }}</span>
                        </a>
                    </li>
                @endcan
            @endplan

            @plan('large')
                @can('CRUD events')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('events')" >
                        <a href="/events" class="kt-menu__link @yield('events')"><span class="kt-menu__link-text"><i class="fas fa-calendar-alt align-self-center"></i> {{ __('Events') }}</span>
                        </a>
                    </li>
                @endcan
                @can('View events')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('events')" >
                        <a href="/calendar" class="kt-menu__link @yield('events')"><span class="kt-menu__link-text"><i class="fas fa-calendar-alt align-self-center"></i> {{ __('Events') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Student applications')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('applications')" >
                        <a href="/leave-applications" class="kt-menu__link @yield('applications')"><span class="kt-menu__link-text"><i class="fas fa-envelope align-self-center"></i> {{ __('Leave Applications') }}</span>
                        </a>
                    </li>
                @endcan
                @can('Leave applications')
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('applications')" >
                        <a href="/applications" class="kt-menu__link @yield('applications')"><span class="kt-menu__link-text"><i class="fas fa-envelope align-self-center"></i> {{ __('Leave Applications') }}</span>
                        </a>
                    </li>
                @endcan
                {{-- Meetings --}}
                @if(auth()->user()->hasRole('Teacher'))
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('meetings')" >
                        <a href="/meetings" class="kt-menu__link @yield('meetings')"><span class="kt-menu__link-text"><i class="fas fa-users align-self-center"></i> {{ __("Virtual Classrooms") }}</span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->hasRole('Student'))
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('meetings')" >
                        <a href="/my-meetings" class="kt-menu__link @yield('meetings')"><span class="kt-menu__link-text"><i class="fas fa-users align-self-center"></i> {{ __("Virtual Classrooms") }}</span>
                        </a>
                    </li>
                @endif
                {{-- Homeworks --}}
                @if(auth()->user()->hasRole('Teacher'))
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('homeworks')" >
                    <a href="/homeworks" class="kt-menu__link @yield('homeworks')"><span class="kt-menu__link-text"><i class="fas fa-book-reader align-self-center"></i> {{ __("Homeworks") }}</span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasRole('Student'))
                    <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('homeworks')" >
                        <a href="/my-homeworks" class="kt-menu__link @yield('homeworks')"><span class="kt-menu__link-text"><i class="fas fa-book-reader align-self-center"></i> {{ __("Homeworks") }}</span>
                        </a>
                    </li>
                @endif
                @can('View behaviours')
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('behaviours')" >
                    <a href="/guardian/behaviours" class="kt-menu__link @yield('behaviours')"><span class="kt-menu__link-text"><i class="fas fa-smile align-self-center"></i> {{ __('Behaviours') }}</span>
                    </a>
                </li>
                @endcan
            @endplan
            

            @can('Student assigned courses')
                <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('current-class')" >
                    <a href="/current-class" class="kt-menu__link @yield('current-class')"><span class="kt-menu__link-text">{{ __('Go to class') }} &nbsp; <i class="fa fa-arrow-right"></i> </span>
                    </a>
                </li>
            @endcan

            <li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel @yield('feedbacks')" >
                <a href="/feedbacks" class="kt-menu__link @yield('feedbacks')"><span class="kt-menu__link-text"><i class="fas fa-sticky-note align-self-center"></i> {{ __('Feedbacks') }}</span>
                </a>
            </li>
            
        </ul>
    </div>
</div>

<!-- end:: Aside Menu -->
</div>
