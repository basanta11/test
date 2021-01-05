@extends('layouts.main')
@section('title','Schedules | '. config("app.name"))	
@section('schedules','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Schedules"),
        'crumbs' => [
            [
                'name' => __("Schedules"),
                'url' => '/schedules'
            ],
        ]
	])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="row">
		<div class="col-12 mb-4">
			<a href="/schedules/create" class="btn btn-brand"><i class="fa fa-plus"></i>{{ __("Add") }}</a>
		</div>
	</div>
	<div class="row">
		@foreach($schedules as $schedule)
			<div class="col-sm-6 col-md-4 col-lg-3">
				<a href="/schedules/{{ $schedule['section_id'] }}">
					<div class="kt-portlet kt-portlet--mobile btn custom-hoverable">
						<div class="kt-portlet__head w-100 no-border-bottom no-padding">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									<strong>{{ $schedule['classroom'] }} </strong>
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body w-100 text-left no-padding minus-margin mb-3">
							<div class="row">
								<div class="col-12 kt-align-left">
									<span class="kt-margin-left-10 smaller-font">
										{{ $schedule['section'] }}	
									</span>
								</div>
							</div>
						</div>
						
					</div>
				</a>
			</div>
		@endforeach
	</div>
</div>	
@endsection