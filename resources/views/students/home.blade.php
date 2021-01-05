@extends('layouts.main')
@section('title','Dashboard | '. config("app.name"))
@section('dashboard','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Dashboard'),
        'crumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => '/app'
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    
	@include('layouts.partials.flash-message')

    <!--Begin::Dashboard 3-->

    <!--Begin::Row-->
    <div class="row">
       
        <div class="col-xl-4">

            <!--begin:: Widgets/Applications/User/Profile1-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head  kt-portlet__head--noborder">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fit-y">

                    <!--begin::Widget -->
                    <div class="kt-widget kt-widget--user-profile-1">
                        <div class="kt-widget__head">
                            <div class="kt-widget__media">
                                <img src="{{ $image }}" alt="image">
                            </div>
                            <div class="kt-widget__content">
                                <div class="kt-widget__section">
                                    <a href="#" class="kt-widget__username disabled-link">
                                        {{ auth()->user()->name }}
                                        <i class="flaticon2-correct kt-font-success"></i>
                                    </a>
                                    <span class="kt-widget__subtitle">
                                        {{ __("Student") }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget__body">
                            <div class="kt-widget__content">
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">{{ __("Email") }}:</span>
                                    <span class="kt-widget__data">{{ auth()->user()->email }}</span>
                                </div>
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">{{ __("Phone") }}:</span>
                                    <span class="kt-widget__data">{{ auth()->user()->phone }}</span>
                                </div>
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">{{ __("Address") }}:</span>
                                    <span class="kt-widget__data">{{ auth()->user()->address }}</span>
                                </div>
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">{{ __("Classroom") }}:</span>
                                    <span class="kt-widget__data">{{ auth()->user()->student_detail->classroom->title }}</span>
                                </div>
                                <div class="kt-widget__info">
                                    <span class="kt-widget__label">{{ __("Section") }}:</span>
                                    <span class="kt-widget__data">{{ auth()->user()->student_detail->section->title }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--end::Widget -->
                </div>
            </div>

            <!--end:: Widgets/Applications/User/Profile1-->
        </div>

        <div class="col-xl-4">

            <!--begin:: Widgets/New Users-->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-primary">
                            {{ __("My Teachers") }}
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget4_tab1_content">
                            <div class="kt-widget4">

                                @foreach($teachers as $teacher)
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__pic kt-widget4__pic--pic">
                                            <img src="{{ $teacher['image'] }}" alt="">
                                        </div>
                                        <div class="kt-widget4__info">
                                            <a class="kt-widget4__username disabled-link">
                                                {{ $teacher['name'] }}
                                            </a>
                                            <p class="kt-widget4__text">
                                                @foreach($teacher['courses'] as $course)
                                                    {{ $course['course']['title'] }} @if(!$loop->last) | @endif
                                                @endforeach
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/New Users-->
        </div>

        <div class="col-xl-4">

            <!--begin:: Widgets/Authors Profit-->
            <div class="kt-portlet kt-portlet--bordered-semi kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-success">
                            {{ __("Time Table") }}
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="/current-class" class="btn btn-label-brand btn-bold btn-sm">
                            {{ __("Go To Class") }} <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget4">

                        @foreach ($schedule as $s)
                            <div class="kt-widget4__item">
                                <div class="kt-widget4__info">
                                    <a class="kt-widget4__title">
                                        @if($s->type == 1) 
                                            {{ $s->course->title }}
                                        @else
                                            Break
                                        @endif
                                    </a>
                                </div>
                                <span class="kt-widget4__number kt-font-brand">{{ date('g:i A', strtotime($s->start_time)) }} - {{ date('g:i A', strtotime($s->end_time)) }}</span>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Authors Profit-->
        </div>

        <div class="col-xl-4">

            <!--begin:: Widgets/New Users-->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-danger">
                            {{ __("My Classmates") }}
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget4_tab1_content">
                            <div class="kt-widget4">

                                @foreach($classmates as $cm)
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__pic kt-widget4__pic--pic">
                                            <img src="{{ $cm['image'] }}" alt="">
                                        </div>
                                        <div class="kt-widget4__info">
                                            <a class="kt-widget4__username disabled-link">
                                                {{ $cm['name'] }}
                                            </a>
                                            <p class="kt-widget4__text">
                                                {{ $cm['email'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/New Users-->
        </div>

        @if ( tenant()->plan == 'large' )    
            <div class="col-xl-4">

                <!--begin:: Widgets/Authors Profit-->
                <div class="kt-portlet kt-portlet--bordered-semi kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title kt-font-success">
                                {{ __("Upcoming Events") }}
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget4">
                            
                            @foreach ($events as $event)
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--logo">
                                        <i class="flaticon-event-calendar-symbol"></i>
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a class="kt-widget4__title disabled-link">
                                            {{ $event->title }}
                                        </a>
                                    </div>
                                    <span class="kt-widget4__number kt-font-brand">{{ date('jS M, Y', strtotime($event->event_date)) }}</span>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Authors Profit-->
            </div>
        @endif
     
    </div>

    <!--End::Row-->

    <!--End::Dashboard 3-->
</div>
@endsection
@push('scripts')
 <!--begin::Page Vendors(used by this page) -->
 <script src="{{ global_asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
 <script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
 <script src="{{ global_asset('assets/plugins/custom/gmaps/gmaps.js') }}" type="text/javascript"></script>

 <!--end::Page Vendors -->

 <!--begin::Page Scripts(used by this page) -->
 <script src="{{ global_asset('assets/js/pages/dashboard.js') }}" type="text/javascript"></script>
 
 <!--end::Page Scripts -->
 <script>
     $(document).ready(function(){
        @if(!auth()->user()->status)
        
        link=`/change-password`;
        message=`{{ __("It looks like you haven't updated your password after first login.") }}<br> {{ __("Please click on proceed to update your password.") }}`;
        header=`{{ __('Update Password') }}`;
    

        makeModal(link,message,header,"GET");

        @endif

    });
 </script>
@endpush
