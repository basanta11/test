@extends('layouts.main')
@section('title','Exams | '. config("app.name"))
@section('exams','kt-menu__item--open')
@section('exams','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Exams"),
	'crumbs' => [
		[
			'name' => __("Exams"),
			'url' => '/exam-students'
		],
	]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')

	<div class="row">
		@foreach($exams as $exam)
			<div class="col-sm-6 col-md-4 col-lg-3">
				<a href="/exam-students/{{ $exam['id'] }}">
					<div class="kt-portlet kt-portlet--mobile btn custom-hoverable">
						<div class="kt-portlet__head w-100 no-border-bottom no-padding">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									<strong>{{ $exam['course'] }} </strong>
								</h3>
							</div>
							<div class="kt-portlet__head-toolbar">
								<div class="dropdown dropdown-inline">
									<span type="span" class="btn btn-sm btn-circle btn-brand btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										{{ $exam['terminal'] }}
									</button>
									
								</div>
							</div>
						</div>
						<div class="kt-portlet__body w-100 text-left no-padding minus-margin smaller-font">
							<div class="row">
								<div class="col-12 kt-align-left m-1">
									<span class="kt-margin-left-10 smaller-font">
										<i class="fa fa-calendar"></i> &nbsp; {{ $exam['exam_start'] }}	
									</span>
								</div>
								<div class="col-12 kt-align-left m-1">
									<span class="kt-margin-left-10 smaller-font">
										<i class="fa fa-clock"></i> &nbsp; {{ $exam['duration'] }}
									</span>
								</div>
							</div>
						</div>
						<div class="kt-portlet__foot w-100 no-border-top mt-3 mb-3 no-padding">
							<div class="row">
								<div class="col-lg-6 kt-align-left">
									<span class="kt-margin-left-10 smaller-font">{{ __("Full Marks") }}:
										<b class="kt-font-brand">{{ $exam['full_marks'] }}</b>
									</span>
								</div>
								<div class="col-lg-6 kt-align-right">
									<span class="kt-margin-left-10 smaller-font">{{ __("Pass Marks") }}: 
										<b class="kt-font-brand">{{ $exam['pass_marks'] }}</b>
									</span>
								</div>
							</div>
						</div>
						<div class="kt-portlet__foot w-100 no-border-top mb-3 no-padding">
							<div class="row">
								<div class="col-12">
									<span class="kt-margin-left-10 smaller-font"><b>{{ __($exam['is_finished']) }}</b></span>
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