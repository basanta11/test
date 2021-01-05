@extends('layouts.main')
@section('title','Meetings | '. config("app.name"))
@section('meetings','kt-menu__item--open')
@section('meetings','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Virtual Classrooms"),
	'crumbs' => [
		[
			'name' => __("Virtual Classrooms"),
			'url' => url()->current(),
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
					{{ __("Virtual Classrooms") }}
				</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        
						<a href="/meetings/create" class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            {{ __('Add') }}
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
	var dataJSONArray = @json($meetings);
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
				template:function(data)
				{
					return `<a href="/meetings/${data.id}"><span>${data.title}</span></a>`
				}
			},

			

			{field:'id',title:'ID',visible:false,width:0},
			{
				field: 'course',
				title: '{{ __("Course Name") }}'
			},
			{
				field: 'classroom',
				title: '{{ __("Class") }}'
			},
			{
				field: 'date',
				title: '{{ __("Date") }}'
			},
			{
				field: 'time',
				title: '{{ __("Time") }}'
			},
			{
				field: 'token',
				title: '{{ __("Password") }}',	
			},
			
			{
				field: 'status',visible:false, width:0,
				title: '{{ __("Status") }}',
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
				title: '{{ __("Actions") }}',				sortable: false,
				width: 110,
				overflow: 'visible',
				autoHide: false,
				template: function(data) {
					
					return '\
					<div class="dropdown">\
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
							<i class="la la-ellipsis-h"></i>\
						</a>\
						<div class="dropdown-menu dropdown-menu-right">\
							<a class="dropdown-item" href="/meetings/'+data.id+'"><i class="la la-eye"></i> {{ __("View") }}</a>\
							<a class="dropdown-item" href="/meetings/'+data.id+'/saved-videos"><i class="la la-video-camera"></i> {{ __("Videos") }}</a>\
							<a class="dropdown-item " href="/meetings/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit") }}</a>\
							<a data-id="'+data.id+'" class="dropdown-item item-delete" href=""><i class="la la-trash"></i> {{ __("Delete") }}</a>\
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

	// status change
	$(document).on('click','.status-change',function(e){
		e.preventDefault();
		var status=$(this).data('status');
		var id=$(this).data('id');
		var link=message=header="";
		if(status==11){
			link=`/meetings/change-status/${id}/0`;
			message="Do you really want to deactivate this exam?";
			header="Deactivate exam";
		}else{
			link=`/meetings/change-status/${id}/1`;
			message="Do you really want to activate this exam?";
			header="Activate exam";
		}

		makeModal(link,message,header,"PATCH");

	});

	$('#kt_datatable_activate').on('click', function() {
		
		var link=message=header="";
		
		link=`/classrooms/change-status-bulk/1`;
		message="Do you really want to activate these classroom?";
		header="Activate classroom(s)";
		

		var arr= new Array();
		var dt=(datatable.getSelectedRecords());

		$.each(datatable.getRecord().getColumn('id').API.value, function(i,k){
			arr.push(k.children[0].childNodes[0].data);
		})

		var input=new Array(
			`<input name="list" hidden multiple value="[${arr}]">`
		);
		makeModal(link,message,header,"PATCH",input);
		
	});

	$('#kt_datatable_deactivate').on('click', function() {
		var link=message=header="";
		
		link=`/classrooms/change-status-bulk/0`;
		message="Do you really want to deactivate these classroom?";
		header="Deactivate classroom(s)";
		

		var arr= new Array();
		var dt=(datatable.getSelectedRecords());

		$.each(datatable.getRecord().getColumn('id').API.value, function(i,k){
			arr.push(k.children[0].childNodes[0].data);
		})

		var input=new Array(
			`<input name="list" hidden multiple value="[${arr}]">`
		);
		makeModal(link,message,header,"PATCH",input);
	});

	$('#kt_datatable_reload').on('click', function() {
		// datatable.reload();
		$('.kt-datatable').KTDatatable('reload');
	});

	$('#kt_datatable_check_all').on('click', function() {
		// datatable.setActiveAll(true);
		$('.kt-datatable').KTDatatable('setActiveAll', true);
	});

	$('#kt_datatable_uncheck_all').on('click', function() {
		// datatable.setActiveAll(false);
		$('.kt-datatable').KTDatatable('setActiveAll', false);
	});

	$(document).on('click','.item-delete',function(e){
		e.preventDefault();
		var id=$(this).data('id');	
		link=`/meetings/${id}`;
		message="Do you really want to delete this meeting?";
		header="Delete meeting";
		makeModal(link,message,header,"DELETE");

	})
</script>
@endpush