@extends('layouts.main')
@section('title','Dashboard | '. config("app.name"))
@section('dashboard','kt-menu__item--open')

@section('content')
@php
    $colors = ['kt-font-brand', 'kt-font-info', 'kt-font-dark', 'kt-font-success', 'kt-font-danger'];
@endphp

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
    
    <div class="kt-portlet">
        <div class="kt-portlet__body  kt-portlet__body--fit">
            <div class="row row-no-padding row-col-separator-xl">

                @foreach($behaviours as $behaviour)
                    <div class="col-md-12 col-lg-6 col-xl-3">

                        <div class="kt-widget24">
                            <div class="kt-widget24__details">
                                <div class="kt-widget24__info">
                                    <h4 class="kt-widget24__title">
                                        Total Profit
                                    </h4>
                                    <span class="kt-widget24__desc">
                                        All Customs Value
                                    </span>
                                </div>
                                <span class="kt-widget24__stats {{ $colors[mt_rand(0, count($colors) - 1)] }}">
                                    $18M
                                </span>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
    </div>

    @if ( tenant()->plan == 'large' )    
        <div class="row">
            
            <div class="col-xl-4">

                <!--begin:: Widgets/Authors Profit-->
                <div class="kt-portlet kt-portlet--bordered-semi kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title kt-font-success">
                                Upcoming Events
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
                                    <span class="kt-widget4__number kt-font-brand">{{ date('jS M, Y g:i a', strtotime($event->event_date)) }}</span>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Authors Profit-->
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

        </div>
    @endif

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
