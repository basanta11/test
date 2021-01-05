@extends('layouts.main')
@section('title','Leave Applications | '. config("app.name"))
@section('applications','kt-menu__item--open')
@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Leave Applications'),
        'crumbs' => [
            [
                'name' => __('Leave Applications'),
                'url' => '/leave-applications'
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
						{{ __('Leave Applications') }}
					</h3>
				</div>
				@hasrole('Student')
					<div class="kt-portlet__head-toolbar">
						<div class="kt-portlet__head-wrapper">
							<div class="kt-portlet__head-actions">
								<div class="btn-group show" role="group">
									<a  class="btn btn-brand btn-elevate btn-icon-sm" href="/leave-applications/create">
										<i class="la la-plus"></i>&nbsp; {{ __('Add') }}
									</a>
								</div>
							</div>
						</div>
					</div>
				@endhasrole
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
												<option value="10">{{ __('Pending') }}</option>
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
				field: 'body',
				title: '{{ __("Message") }}'
			},
            {
				field: 'date',
				title: '{{ __("Leave Date") }}'
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
                        return `<span class="badge badge-warning">{{ __("Pending") }}</span>`;
                    }
                    else if (data.status == 11) {
                        return `<span class="badge badge-success">{{ __("Approved") }}</span>`;
                    }
                    else {
                        return `<span class="badge badge-danger">{{ __("Rejected") }}</span>`;
                    }
                }
			},
			{
				field:'id',title:'ID',width:0,
				visible:false,
			},
		],
	});

    $('#kt_form_status').selectpicker();

    $('#kt_form_status').on('change',function(e){
        datatable.search($(this).val(), 'status');
    })
</script>
@endpush