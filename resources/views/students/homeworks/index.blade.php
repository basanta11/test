@extends('layouts.main')
@section('title','Homeworks | '. config("app.name"))
@section('homeworks','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Homeworks"),
        'crumbs' => [
            [
                'name'=>__('Homeworks'),
                'url'=> url()->current(),
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
					{{ __("Homeworks") }}
				</h3>
            </div>
            
		</div>
		<div class="kt-portlet__body">

			<!--begin: Search Form -->
			<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
				<div class="row align-items-center">
					<div class="col-xl-8 order-2 order-xl-1">
						<div class="row align-items-center">
							<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
								<div class="kt-input-icon kt-input-icon--left">
									<input type="text" class="form-control" placeholder="{{ __('Search') }}..." id="generalSearch">
									<span class="kt-input-icon__icon kt-input-icon__icon--left">
										<span><i class="la la-search"></i></span>
									</span>
								</div>
							</div>
							{{-- <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
								<div class="kt-form__group kt-form__group--inline">
									<div class="kt-form__label">
										<label>{{ __("Status") }}:</label>
									</div>
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_form_status">
											<option value="">{{ __("All") }}</option>
											
											<option value="11">{{ __("Active") }}</option>
											<option value="10">{{ __("Inactive") }}</option>
										</select>
									</div>
								</div>
							</div> --}}
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
	var dataJSONArray = @json($homeworks);
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

		rows:{
			beforeTemplate: function (row, data, index) {
				if(data.status==10){
					row.addClass('cutsom-bg-danger-light')
					row.data('status',data.status)
				}
			}
		},

		// columns definition
		columns: [
			{
				field:'sn',
				title:'{{ __("SN") }}'
			},
			{
				field: 'title',
				title: '{{ __("Title") }}',
				
			},

			

			{field:'id',title:'ID',visible:false,width:0},
			{
				field: 'course_title',
				title: '{{ __("Course Name") }}'
			},
			

			{
				field: 'due_date',
				title: '{{ __("Date") }}'
			},
			
			{
				field: 'status',
				title: '{{ __("Status") }}',
				
			}, 
			{
				field: 'marked',
				title: '{{ __("Marking") }}',
				
			}, 
			{
				field: 'Actions',
				title: '{{ __("Actions") }}',
				sortable: false,
				width: 110,
				overflow: 'visible',
				autoHide: false,
				template: function(data) {
					var html="";
					console.log(data.marked);
					if(data.hasHomework){
						if(data.marked==='Not Marked')
							html='<a class="dropdown-item" href="/my-homeworks/'+data.id+'/edit"><i class="la la-edit"></i> Edit</a>'
						else
							html='<a class="dropdown-item" href="/my-homeworks/'+data.id+'"><i class="la la-eye"></i> View</a>'
					}
					else{
						html='<a class="dropdown-item" href="/my-homeworks/'+data.id+'/create"><i class="la la-file"></i> Upload Submission</a>'
					}
					return '\
					<div class="dropdown">\
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
							<i class="la la-ellipsis-h"></i>\
						</a>\
						<div class="dropdown-menu dropdown-menu-right">\
							'+html+'\
						</div>\
					</div>\
				';
				},
			}
		],
	});

    $('#kt_form_status').on('change', function() {
      datatable.search($(this).val(), 'status');
    });

    $('#kt_form_status,#kt_form_type').selectpicker();

</script>
@endpush