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
			'url' => '/exams'
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
					{{ __("Exams") }}
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						
						<a href="/exams/create" class="btn btn-brand btn-elevate btn-icon-sm">
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
	var dataJSONArray = @json($exams);
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
				field: 'title',
				title: '{{ __("Title") }}'
			},

			{field:'id',title:'ID',visible:false,width:0},
			{
				field: 'course',
				title: '{{ __("Course") }}'
			},
			{
				field: 'exam_start',
				title: '{{ __("Exam Date") }}'
			},
			{
				field: 'duration',
				title: '{{ __("Duration") }}'
			},
			{
				field: 'full_marks',
				title: '{{ __("Full Marks") }}',
				width: 60,
			},
			{
				field: 'pass_marks',
				title: '{{ __("Pass Marks") }}',
				width: 60,
			},
			{
				field: 'classroom',
				title: '{{ __("Classroom") }}',
				template: function(data) {
					return `<span>${data.classroom.title}</span>`;
				}
			},
			{
				field: 'sections',
				title: '{{ __("Sections") }}',
				sortable: false,
				template: function(data) {
					let sections = "";
					sections += `
						<div class="kt-list-timeline">
							<div class="kt-list-timeline__items">
					`;

					$.each(data.sections, function( index, value ) {
						sections += `
							<div class="kt-list-timeline__item">
								<span class="kt-list-timeline__badge kt-list-timeline__badge--primary"></span>
								<span class="kt-list-timeline__text">`+value.title+`</span>
							</div>
						`;
					});

					sections += `
							</div>
						</div>
					`;

					return sections;
				}
			},
			{
				field: 'teacher',
				title: '{{ __("Teacher") }}'
			},
			{
				field: 'status', visible:false, width:0,
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
				title: '{{ __("Actions") }}',
				sortable: false,
				width: 110,
				overflow: 'visible',
				autoHide: false,
				template: function(data) {
					var status="";
					var result="";
					
					if(data.result==1){
						result='<a class="dropdown-item disable-result-item" data-id="'+data.id+'" href="#"><i class="la la-folder"></i> Disable Result</a>'
					}else{
						result='<a class="dropdown-item enable-result-item" data-id="'+data.id+'" href="#"><i class="la la-folder-open"></i> Enable Result</a>'
					}
					
					if (data.status==11){
						status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-times"></i>{{ __("Deactivate") }}</a>`;
					}else{
						status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-check"></i>{{ __("Activate") }} </a>`;
					}
					return '\
					<div class="dropdown">\
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
							<i class="la la-ellipsis-h"></i>\
						</a>\
						<div class="dropdown-menu dropdown-menu-right">\
							<a class="dropdown-item" href="/exams/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit Details") }}</a>\
							'+status+'\
							'+result+'\
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
			link=`/exams/change-status/${id}/0`;
			message="Do you really want to deactivate this exam?";
			header="Deactivate exam";
		}else{
			link=`/exams/change-status/${id}/1`;
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

	$(document).on('click','.disable-result-item',function(e){
		e.preventDefault();
		var id=$(this).data('id');
		var link=message=header="";
		link=`/exams/${id}/change-result/0`;
		message="Do you really want to disable result of this exam?";
		header="Disable result?";
		makeModal(link,message,header,"PATCH");
	})
	$(document).on('click','.enable-result-item',function(e){
		e.preventDefault();
		var id=$(this).data('id');
		var link=message=header="";
		link=`/exams/${id}/change-result/1`;
		message="Do you really want to enable result of this exam?";
		header="Enable result?";
		makeModal(link,message,header,"PATCH");
	})
</script>
@endpush