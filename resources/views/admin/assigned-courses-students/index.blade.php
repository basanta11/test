@extends('layouts.main')
@section('title','Assigned Courses | '. config("app.name"))
@section('assigned-courses-students','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Assigned Courses"),
        'crumbs' => [
            [
                'name' => __("Assigned Courses"),
                'url' => '/student/assigned-courses'
            ],
        ]
	])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')

	<div class="row">
		@foreach($courses as $course)
			<div class="col-sm-6 col-md-4 col-lg-3">
				<a href="/student/assigned-courses/{{ $course['id'] }}">
					<div class="kt-portlet kt-portlet--mobile btn custom-hoverable">
						<div class="kt-portlet__head w-100 no-border-bottom no-padding">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									<strong>{{ $course['title'] }} </strong>
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body w-100 text-left no-padding minus-margin smaller-font" title="{{ $course['learn_what'] }}">
							{{ mb_strimwidth($course['learn_what'], 0, 80, "...") }}
						</div>
						<div class="kt-portlet__foot w-100 no-border-top mt-3 mb-3 no-padding">
							<div class="row">
								<div class="col-lg-7 kt-align-left">
									<span class="kt-margin-left-10 smaller-font"><div>{{ __("Taught By") }}</div>
										<b>{{ $course['teacher'] }}</b>
									</span>
								</div>
								<div class="col-lg-5 kt-align-right">
									<span class="kt-margin-left-10 smaller-font"><div>{{ __("Credit Hours") }}</div> 
										<b class="kt-font-brand">{{ $course['credit_hours'] }}</b>
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