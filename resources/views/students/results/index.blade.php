@extends('layouts.main')
@section('title','Results | '. config("app.name"))
@section('results','kt-menu__item--open')
@section('results','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Results"),
	'crumbs' => [
		[
			'name' => __("Results"),
			'url' => '/results'
		],
	]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="row">
		@foreach($results as $result)
			<div class="col-sm-6 col-md-4 col-lg-3">
				<a href="/results/{{ $result['id'] }}">
					<div class="kt-portlet kt-portlet--mobile btn custom-hoverable">
						<div class="kt-portlet__head w-100 no-border-bottom no-padding">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									<strong>{{ $result['course_title'] }} </strong>
								</h3>
							</div>
							<div class="kt-portlet__head-toolbar">
								<div class="dropdown dropdown-inline">
									<span type="span" class="btn btn-sm btn-circle btn-brand btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										{{ $result['exam_type'] }}
									</button>
									
								</div>
							</div>
						</div>
						<div class="kt-portlet__body w-100 text-left no-padding minus-margin">
							<div class="row">
								<div class="col-12 kt-align-left">
									<span class="kt-margin-left-10">
										{{ $result['set'] }}	
									</span>
								</div>
							</div>
						</div>
						<div class="kt-portlet__foot w-100 no-border-top mt-2 mb-3 no-padding">
							<div class="row">
								<div class="col-lg-6 kt-align-left">
									<div>
										<span class="kt-margin-left-10 smaller-font">{{ __("Full Marks") }}:
											<b class="kt-font-brand">{{ $result['total'] }}</b>
										</span>
									</div>
									<div>
										<span class="kt-margin-left-10 smaller-font">{{ __("Pass Marks") }}: 
											<b class="kt-font-brand">{{ $result['pass_marks'] }}</b>
										</span>
									</div>
								</div>
								<div class="col-lg-6 kt-align-right">
									<span class="kt-margin-left-10 smaller-font"><div>{{ __("Obtained Marks") }}:</div>
										<b class="kt-font-brand">{{ $result['obtained_marks'] }}/{{ $result['total'] }}</b>
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