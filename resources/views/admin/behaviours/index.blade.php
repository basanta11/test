@extends('layouts.main')
@section('title','Behaviours | '. config("app.name"))
@section('content')
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="delform" method="POST">
					@csrf
					@method('DELETE')

					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Delete behaviour</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Are you sure you want to delete?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Confirm</button>
					</div>
				</form>
			</div>
		</div>
	</div>
  
	@include('layouts.partials.breadcrumbs', [
		'breadTitle' => __('Behaviours'),
		'crumbs' => [
			[
				'name' => __('Behaviours'),
				'url' => '/behaviours'
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
						{{ __('Behaviours') }}
					</h3>
				</div>

				@if($is_classTeacher)
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
                            <a href="/class-teacher/behaviour-types/assign" class="btn btn-brand btn-elevate btn-icon-sm">
								<i class="la la-file-o"></i>
								{{ __("Assign") }}
							</a>
						</div>
					</div>
				</div>
				@endif
			</div>
			<div class="kt-portlet__body">
				<div class="row">

				@foreach($class_sections as $section)
				<div class="col-sm-2">

					
					<a href="/behaviours/{{ $section->id }}"><button class="btn btn-primary w-100">{{ $section['classroom']['title'] }} - {{ $section->title }}</button></a>
				</div>
				@endforeach
				</div>
				<!--end: Search Form -->
			</div>
		
            <!--end:: Widgets/Blog-->
        </div>
     
    </div>

@endsection

@push('scripts')
@endpush