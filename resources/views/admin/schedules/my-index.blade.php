@extends('layouts.main')
@section('title','Schedules | '. config("app.name"))
@section('schedules','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Schedules"),
        'crumbs' => [
            [
                'name' => __("My Schedule"),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-list-2"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					{{ __("View Schedule") }}
				</h3>
			</div>
		</div>
            <div class="kt-portlet__body">
                <ul class="nav nav-tabs  nav-tabs-line" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Sunday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_sunday" role="tab" aria-selected="true">Sunday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Monnday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_monday" role="tab" aria-selected="true">Monday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Tuesday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_tuesday" role="tab" aria-selected="true">Tuesday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Wednesday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_wednesday" role="tab" aria-selected="true">Wednesday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Thurday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_thursday" role="tab" aria-selected="true">Thursday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Friday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_friday" role="tab" aria-selected="true">Friday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Saturday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_saturday" role="tab" aria-selected="true">Saturday</a>
                    </li>
                </ul>
                <div class="tab-content">
                    @foreach($days as $key => $day)
                    <div class="tab-pane {{ strtoupper($day)===strtoupper($today) ? 'active' : '' }}" id="kt_tabs_{{ $day }}" role="tabpanel">
                       
                        <div class="kt-portlet__body p-0">
                           
                            @if(!isset($schedule[$day]))
                                {{ __("No schedule has been added to this day") }}.
                            @else
                                <div class="row">
                                    <div class="col-xl-1">
                                    </div>
                                    <div class="col-xl-10">
                                        <div class="kt-timeline-v1">
                                            <div class="kt-timeline-v1__items">
                                                <div class="kt-timeline-v1__marker"></div>
                                                @foreach($schedule[$day] as $key=>$s)
                                                <div class="kt-timeline-v1__item kt-timeline-v1__item--{{ $key%2==0 ? 'left' : 'right' }} kt-timeline-v1__item--{{ $key==0 ? 'first' : '' }}">
                                                    <div class="kt-timeline-v1__item-circle">
                                                        <div class="kt-bg-danger"></div>
                                                    </div>
                                                    <span class="kt-timeline-v1__item-time kt-font-brand">
                                                    <span>
                                                        {{ date('g:i A',strtotime($s->start_time)) }} - {{ date('g:i A',strtotime($s->end_time)) }} 
                                 
                                                    </span>
                                                    </span>
                                                    <div class="kt-timeline-v1__item-content">
                                                        @if($s['type']==0)
                                                            <h2>Break</h2>
                                                        @else

                                                            <div class="kt-timeline-v1__item-title">
                                                                {{ $s['course']['title'] }}
                                                            </div>

                                                            {{-- teacher --}}
                                                            @if(auth()->user()->role_id==3)
                                                            <div class="kt-timeline-v1__item-body">
                                                                <div class="kt-list-pics kt-margin-b-10">
                                                                    Section: {{ $s['section']['title'] }}
                                                                </div>
                                                                <div class="kt-list-pics kt-margin-b-10">
                                                                    Classroom: {{ $s['section']['classroom']['title'] }}
                                                                </div>
                                                            </div>

                                                            {{-- student --}}
                                                            @elseif(auth()->user()->role_id==4)
                                                            @if(isset($s['section']['course_details']->first()['user']->first()->id))
                                                            <a href="/teachers/{{ $s['section']['course_details']->first()['user']->id }}">
                                                                <img 
                                                                src="{{ $s['section']['course_details']->first()['user']->image && Storage::disk(config('app.storage_driver'))->exists('user/'.$s['section']['course_details']->first()['user']->image) 
                                                                ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$s['section']['course_details']->first()['user']->image) 
                                                                : global_asset('assets/media/users/default.jpg') }}" 
                                                                title=""
                                                                class="custom-image"
                                                                >
                                                                <span>&nbsp;&nbsp; {{ $s['section']['course_details']->first()['user']->name }} </span>
                                                            </a>
                                                            @endif
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-xl-1">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>  
    </div>
</div>

@endsection
