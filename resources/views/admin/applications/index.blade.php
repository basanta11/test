@extends('layouts.main')
@section('title','Leave Applications | '. config("app.name"))
@section('applications','kt-menu__item--open')
@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Leave Applications'),
        'crumbs' => [
            [
                'name' => __('Leave Applications'),
                'url' => '/applications'
            ],
        ]
	])

	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST" class="custom-form">
					@csrf
					@method('PATCH')

					<div class="modal-header">
						<h5 class="modal-title" id="confirmModalLabel">{{ __("Approve Request?") }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>{{ __("Are you sure you want to approve?") }}</p>
						<div class="form-group">
							<label>{{ __("Note") }}: </label>
							<input type="hidden" name="status" value="1">
							<textarea name="note" cols="20" rows="10" class="form-control"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
						<button type="submit" class="btn btn-primary">{{ __("Confirm") }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form method="POST" class="custom-form">
					@csrf
					@method('PATCH')

					<div class="modal-header">
						<h5 class="modal-title" id="rejectModalLabel">{{ __("Reject Request?") }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>{{ __("Are you sure you want to reject?") }}</p>
						<div class="form-group">
							<label>{{ __("Note") }}: </label>
							<input type="hidden" name="status" value="2">
							<textarea name="note" cols="20" rows="10" class="form-control"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
						<button type="submit" class="btn btn-primary">{{ __("Confirm") }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="kt-container  kt-grid__item kt-grid__item--fluid">
		@include('layouts.partials.flash-message')
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-list-2"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Leave Applications') }}
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
								<div class="col-md-2 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>{{ __('Status') }}:</label>
										</div>
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_form_status">
												<option value="" selected>{{ __('All') }}</option>
												<option value="10" selected>{{ __('Pending') }}</option>
												<option value="11">{{ __('Approved') }}</option>
												<option value="12">{{ __('Rejected') }}</option>
											</select>
										</div>
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
	function statusAction(appid, status) {
		if (status == 1) {
			$('#confirmModal').modal();
			$('.custom-form').attr('action', '/application-status/'+appid);
		}
		else {
			$('#rejectModal').modal();
			$('.custom-form').attr('action', '/application-status/'+appid);
		}
	}

	$(document).ready(function() {
		var dataJSONArray = @json($applications);
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
					field: 'section',
					title: '{{ __("Section") }}'
				},
				{
					field: 'body',
					title: '{{ __("Message") }}'
				},
				{
					field: 'note',
					title: '{{ __("Note") }}'
				},
				{
					field: 'status',
					title: '{{ __("Status") }}',
					template: function(data) {
						if (data.status == 10) {
							return `<span class="badge badge-warning">Pending</span>`;
						}
						else if (data.status == 11) {
							return `<span class="badge badge-success">Approved</span>`;
						}
						else {
							return `<span class="badge badge-danger">Rejected</span>`;
						}
					}
				},
				{
					field:'id',title:'ID',width:0,
					visible:false,
				},
				{
					field: 'Actions',
					title: '{{ __("Actions") }}',
					sortable: false,
					width: 110,
					overflow: 'visible',
					autoHide: false,
					template: function(data) {
						if (data.status <= 10) {
							return `
								<div class="dropdown">
									<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
										<i class="la la-ellipsis-h"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<button class="dropdown-item custom-btn" data-status="1" data-id="${data.id}" onclick="statusAction(${data.id},1)"><i class="la la-check"></i> Approve</button>\
										<button class="dropdown-item custom-btn" data-status="2" data-id="${data.id}" onclick="statusAction(${data.id},2)"><i class="la la-times"></i> Reject</button>\
									</div>
								</div>
							`;
						}
						else {
							return `N/A`;
						}
					},
				}
			],
		});

		$('#kt_form_status').selectpicker();
		datatable.search($('#kt_form_status').val(), 'status');
		
		$('#kt_form_status').on('change',function(e){
			datatable.search($(this).val(), 'status');
		});

		// $('.dropdown-menu').on('click', '.custom-btn', function() {
		// 	alert('test');

		// 	var getId = $(this).attr('data-id');
		// 	var getStatus = $(this).attr('data-status');
		// 	console.log(getId);

		// 	$(".custom-form").attr('action', '/application-status/' + getId + '/' + getStatus);
		// });
	});
</script>
@endpush