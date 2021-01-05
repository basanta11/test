@extends('layouts.main')
@section('title','Schedules | '. config("app.name"))
@section('schedules','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Schedules'),
        'crumbs' => [
            [
                'name' => __('Schedules'),
                'url' => '/schedules'
            ],
            [
                'name' => __('View Schedule'),
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
					{{ __('View Schedule') }}
                    <small>{{ __('Section') }}: {{ $section->title }}. {{ __('Classroom') }}: {{ $section['classroom']['title'] }}</small>
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						
                    <a href="/schedules/{{ $section->id }}/create" class="btn btn-brand btn-elevate btn-icon-sm">
							<i class="la la-plus"></i>
							{{ __('Add') }}
						</a>
					</div>
				</div>
			</div>
		</div>
            <div class="kt-portlet__body">
                <ul class="nav nav-tabs  nav-tabs-line" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Sunday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_sunday" role="tab" aria-selected="true">Sunday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $today=='Monday' ? 'active' : '' }}" data-toggle="tab" href="#kt_tabs_monday" role="tab" aria-selected="true">Monday</a>
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
                                {{ __("No schedule has been added to this day.") }}
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
                                                        {{-- <div class="dropdown inline" style="z-index:999"> --}}
                                                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                                                <i class="la la-ellipsis-h"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="/schedules/{{ $s['id'] }}/edit"><i class="la la-eye"></i> {{ __("Edit") }}</a>

                                                                <a class="dropdown-item status-delete" data-id="{{ $s['id'] }}" href="#"><i class="la la-times"></i> {{ __("Delete") }}</a>
                                                            </div>
                                                        {{-- </div> --}}
                                                    </span>
                                                    </span>
                                                    <div class="kt-timeline-v1__item-content">
                                                        @if($s['type']==0)
                                                        <h2>Break</h2>
                                                        @else

                                                        <div class="kt-timeline-v1__item-title">
                                                            {{ $s['course']['title'] }}
                                                        </div>
                                                        <div class="kt-timeline-v1__item-body">
                                                            <div class="kt-list-pics kt-margin-b-10">
                                                                @foreach($s['section']['course_details'] as $sc)
                                                                @if(isset($sc['user']->first()->id) && $sc['course']['id']== $s['course']['id'])
                                                                <a href="/teachers/{{ $sc['user']->id }}">
                                                                    <img 
                                                                    src="{{ $sc['user']->image && Storage::disk(config('app.storage_driver'))->exists('user/'.$sc['user']->image) 
                                                                    ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$sc['user']->image) 
                                                                    : global_asset('assets/media/users/default.jpg') }}" 
                                                                    title=""
                                                                    class="custom-image"
                                                                    >
                                                                    <span>&nbsp;&nbsp; {{ $sc['user']->name }} </span>
                                                                </a>
                                                                @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                                {{-- <div class="kt-timeline-v1__item kt-timeline-v1__item--right">
                                                    <div class="kt-timeline-v1__item-circle">
                                                        <div class="kt-bg-danger"></div>
                                                    </div>
                                                    <span class="kt-timeline-v1__item-time kt-font-brand">02:50<span>PM</span></span>
                                                    <div class="kt-timeline-v1__item-content">
                                                        <div class="kt-timeline-v1__item-title">
                                                            New Members Joined!
                                                        </div>
                                                        <div class="kt-timeline-v1__item-body">
                                                            <div class="kt-widget4">
                                                                <div class="kt-widget4__item">
                                                                    <div class="kt-widget4__pic">
                                                                        <img src="assets/media/users/100_4.jpg" alt="">
                                                                    </div>
                                                                    <div class="kt-widget4__info">
                                                                        <a href="#" class="kt-widget4__username">
                                                                            Anna Strong
                                                                        </a>
                                                                        <p class="kt-widget4__text">
                                                                            Visual Designer, Google Inc
                                                                        </p>
                                                                    </div>
                                                                    <a href="#" class="btn btn-sm btn-label-success btn-bold">Check</a>
                                                                </div>
                                                                <div class="kt-widget4__item">
                                                                    <div class="kt-widget4__pic">
                                                                        <img src="assets/media/users/100_5.jpg" alt="">
                                                                    </div>
                                                                    <div class="kt-widget4__info">
                                                                        <a href="#" class="kt-widget4__username">
                                                                            Nick Nelson
                                                                        </a>
                                                                        <p class="kt-widget4__text">
                                                                            Project Manage, Apple Inc
                                                                        </p>
                                                                    </div>
                                                                    <a href="#" class="btn btn-sm btn-label-success btn-bold">Check</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="kt-timeline-v1__item-actions">
                                                            <a href="#" class="btn btn-sm btn-brand">Check all</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                 --}}
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
                    <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged
                    </div>
                </div>
            </div>  
    </div>
</div>

@endsection
@push('scripts')
<script>
    $(document).on('click','.status-delete',function(e){
        e.preventDefault();
		var id=$(this).data('id');
		makeModal('/schedules/'+id,'Are you sure you want to delete the schedule?','Delete Schedule?',"DELETE");

	});
</script>
@endpush