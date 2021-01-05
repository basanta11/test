@extends('layouts.main')
@section('title','Courses | '. config("app.name"))
@section('courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Courses"),
        'crumbs' => [
            [
                'name' => __("Courses"),
                'url' => '/courses'
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
					{{ __("Courses") }}
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						
						<a href="/courses/create" class="btn btn-brand btn-elevate btn-icon-sm">
							<i class="la la-plus"></i>
							{{ __("Add") }}
						</a>
					</div>
				</div>
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
							<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
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
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--end: Search Form -->

			<!--begin: Search Form -->
			{{-- <div class="kt-form kt-form--label-align-right kt-margin-t-10 kt-margin-b-30">
				<div class="row">
					<div class="col-lg-12">
						<button class="btn btn-secondary" type="button" id="kt_datatable_reload">{{ __("Reload") }}</button>
						<button class="btn btn-secondary custom-select-all" type="button" id="kt_datatable_check_all">{{ __("Select all rows") }}</button>
						<input value="0" data-switch="true" type="checkbox" name="state" checked="checked" data-on-color="success" data-off-color="danger" id="state">
			
						<button class="btn btn-secondary custom-activate" type="button" id="state-confirm"> <i class="fa fa-check p-0"></i></button>
					</div>
				</div>
			</div> --}}

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
	var dataJSONArray = @json($courses);
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
				field:'image',
				title: ' ',
				width: 50,
				template:function(data){
					return `<a href="/courses/${data.id}"><img class="custom-image" src="${data.image}" > </a>`;
				}
			},
			{
				field: 'title',
				title: '{{ __("Course") }}',
				template:function(data){
					return `<a href="/courses/${data.id}"><span>${data.title}</span></a>`
				}
			}, 

			{field:'id',title:'ID',visible:false,width:0},
			{
				field: 'credit_hours',
				title: '{{ __("Credit Hours") }}'
			},
			{
				field: 'classroom',
				title: '{{ __("Classroom") }}'
			},
			{
				field: 'sections',
				title: '{{ __("Sections") }}'
			},
			{
				field: 'status',
				width:0,
				visible:false,
				title: '{{ __("Status") }}',
				// callback function support for column rendering
				template: function(row) {
					var status = {
						10: {'title': '{{ __("Inactive") }}', 'class': 'kt-badge--danger'},
						11: {'title': '{{ __("Active") }}', 'class': ' kt-badge--success'}
					};
					return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--inline kt-badge--pill">' + status[row.status].title + '</span>';
				},
			}, 
			{
				field: 'Actions',
				title: '{{ __("Actions") }}',
				sortable: false,
				width: 110,
				overflow: 'visible',
				autoHide: false,
				template: function(data) {
					var status="";
					if (data.status==11){
						status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-times"></i> {{ __("Deactivate") }}</a>`;
					}else{
						status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-check"></i> {{ __("Activate") }}</a>`;
					}
					return '\
					<div class="dropdown">\
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
							<i class="la la-ellipsis-h"></i>\
						</a>\
						<div class="dropdown-menu dropdown-menu-right">\
						    	<a class="dropdown-item" href="/courses/'+data.id+'"><i class="la la-eye"></i> {{ __("View") }}</a>\
							<a class="dropdown-item" href="/courses/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit Details") }}</a>\
							<a class="dropdown-item" href="/courses/assign-teacher/'+data.id+'"><i class="la la-users"></i> Assign Teacher</a>\
							'+status+'\
						</div>\
					</div>\
				';
				},
			}
		],
	});
	// $("[name='state']").bootstrapSwitch({
	// 	onText: 'Activate',
	// 	offText: 'Deactivate',
	// 	state: true,
	// 	onSwitchChange: function(event) {
	// 		if($('#state').val()==0){
	// 			$('#state').val(1);
	// 			$('#state-confirm').removeClass('custom-activate').addClass('custom-deactivate');
	// 		}else{			
	// 			$('#state').val(0);
	// 			$('#state-confirm').removeClass('custom-deactivate').addClass('custom-activate');
	// 		}
	// 	}
	// });

    $('#kt_form_status').on('change', function() {
      	datatable.search($(this).val().toLowerCase(), 'status');
    });
	
    $('#kt_form_status,#kt_form_type').selectpicker();

	// status change
	$(document).on('click','.status-change',function(e){
		e.preventDefault();
		var status=$(this).data('status');
		var id=$(this).data('id');
		var link=message=header="";
		if(status==11){
			link=`/courses/change-status/${id}/0`;
			message="Do you really want to deactivate this course?";
			header="Deactivate course";
		}else{
			link=`/courses/change-status/${id}/1`;
			message="Do you really want to activate this course?";
			header="Activate course";
		}

		makeModal(link,message,header,"PATCH");

	});

	$(document).on('click','.custom-activate', function() {
		
		var link=message=header="";
		
		link=`/courses/change-status-bulk/1`;
		message="Do you really want to activate these course?";
		header="Activate course(s)";
		

		var arr= new Array();
		var dt=(datatable.getSelectedRecords());

		$.each(datatable.getRecord().getColumn('id').API.value, function(i,k){
			arr.push(k.children[0].childNodes[0].data);
		})
		if(arr.length<1){
			toastr.error('Please select at least one course from the table.')
			return false;
		}

		var input=new Array(
			`<input name="list" hidden multiple value="[${arr}]">`
		);
		makeModal(link,message,header,"PATCH",input);
		
	});

	$(document).on('click','.custom-deactivate', function() {
		var link=message=header="";
		
		link=`/courses/change-status-bulk/0`;
		message="Do you really want to deactivate these course?";
		header="Deactivate course(s)";
		

		var arr= new Array();
		var dt=(datatable.getSelectedRecords());

		$.each(datatable.getRecord().getColumn('id').API.value, function(i,k){
			arr.push(k.children[0].childNodes[0].data);
		})
		if(arr.length<1){
			toastr.error('Please select at least one course from the table.')
			return false;
		}

		var input=new Array(
			`<input name="list" hidden multiple value="[${arr}]">`
		);
		makeModal(link,message,header,"PATCH",input);
	});

	$('#kt_datatable_reload').on('click', function() {
		// datatable.reload();
		$('.kt-datatable').KTDatatable('reload');
	});

	$(document).on('click','.custom-select-all', function() {
		// datatable.setActiveAll(true);
		$('.kt-datatable').KTDatatable('setActiveAll', true);
	});

	$(document).on('click','.custom-unselect-all', function() {
		// datatable.setActiveAll(false);
		$('.kt-datatable').KTDatatable('setActiveAll', false);
	});
</script>
@endpush