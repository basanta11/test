@extends('layouts.main')
@section('title','Behaviours | '. config("app.name"))
@section('behaviours','kt-menu__item--open')

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
			</div>
			<div class="kt-portlet__body">

				<!--begin: Search Form -->
				<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
					<div class="row align-items-center">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center">
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-input-icon kt-input-icon--left">
										<input type="text" class="form-control" placeholder="{{ __('Search') }}..." id="generalSearch">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="la la-search"></i></span>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--end: Search Form -->
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">

				<!--begin: Datatable -->
				<div class="kt-datatable" id="api_methods"></div>

				<!--end: Datatable -->
			</div>

            <!--end:: Widgets/Blog-->
        </div>
     
    </div>

@endsection

@push('scripts')
<script>
	function bevdelete(id) {
		$('#exampleModal').modal();
		$('#delform').attr('action', '/behaviours/'+id);
	}
	
	$(document).ready(function() {

		var dataJSONArray = @json($behaviours);
		var datatable = $('.kt-datatable').KTDatatable({
			// datasource definition
			data: { 
				type: 'local',
				source: dataJSONArray,
				pageSize: 10,
			},

			// layout definition
			layout: {
				scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
				footer: false // display/hide footer
			},

			// column sorting
			sortable: true,

			pagination: true,

			search: {
				input: $('#generalSearch')
			},

			// columns definition
			columns: [
				{
					field: 'student',
					title: '{{ __("Student") }}'
				},
				{
					field: 'teacher',
					title: '{{ __("Teacher") }}'
				},
				{
					field: 'classroom',
					title: '{{ __("Classroom") }}'
				},
				{
					field: 'behaviour',
					title: '{{ __("Behaviour") }}'
				},
				{
					field: 'marks',
					title: '{{ __("Marks") }}'
				},
				{
					field:'id',title:'ID',width:0,
					visible:false,
				}
			],
		});
	});
</script>
@endpush