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
			'url' => '/exam-teachers'
		],
		[
			'name' => __("View Submissions"),
			'url' => url()->current()
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
					{{ __("View Submissions") }}
				</h3>
			</div>
			
		</div>
		<div class="kt-portlet__body">

			<!--begin: Search Form -->
			<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
				<div class="row align-items-center">
					<div class="col-xl-12 order-2 order-xl-1">
						<div class="row align-items-center">
							<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
								<div class="kt-input-icon kt-input-icon--left">
									<input type="text" class="form-control" placeholder="{{ __('Search') }}..." id="generalSearch">
									<span class="kt-input-icon__icon kt-input-icon__icon--left">
										<span><i class="la la-search"></i></span>
									</span>
								</div>
							</div>
							<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
								<div class="kt-form__group kt-form__group--inline">
									<div class="kt-form__label">
										<label>{{ __("Status") }}:</label>
									</div>
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_form_status">
											<option value="">{{ __("All") }}</option>
											<option value="11">{{ __("Finished") }}</option>
											<option value="10">{{ __("Incomplete") }}</option>
										</select>
									</div>
								</div>
                            </div>
                            <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
								<div class="kt-form__group kt-form__group--inline">
									<div class="kt-form__label">
										<label>{{ __("Sets") }}:</label>
									</div>
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_form_sets">
                                            <option value="">{{ __("All") }}</option>
                                            @foreach ($sets as $set)
											    <option value="{{ $set->id }}">{{ $set->title }}</option>
                                            @endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
								<div class="kt-form__group kt-form__group--inline">
									<div class="kt-form__label">
										<label>{{ __('Sections') }}:</label>
									</div>
									<div class="kt-form__control">
										<select class="form-control bootstrap-select" id="kt_form_section">
											<option value="" selected>{{ __('All') }}</option>
											@foreach ($sections as $section)
											    <option value="{{ $section->title }}">{{ $section->title }}</option>
                                            @endforeach
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
	var dataJSONArray = @json($setuser);
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
				field: 'set',
				title: '{{ __("Set") }}',
                template: function(row) {
                    return '<a href="/sets/'+row.set_id+'">'+row.set+'</a>';
                }
			},
			{field:'id',title:'ID',visible:false,width:0},
			{field:'section',title:'ID',visible:false,width:0},
			{
				field: 'name',
				title: '{{ __("Student") }}'
			},{
				field: 'roll_number',
				title: '{{ __("Roll Number") }}'
			},
            {
				field: 'class',
				title: '{{ __("Class") }}',
                template: function(row) {
                    return row.classroom + ' ( ' + row.section + ' )';
                }
			},
			{
				field: 'obtained_marks',
				title: '{{ __("Obatained Marks") }}',
				template: function(row) {
					return row.obtained_marks+'/'+row.total;
				}
			},
            {
				field: 'is_finished',
				title: '{{ __("Status") }}',
                template: function(row) {
                    return row.is_finished == 10 ? 'Incomplete' : 'Finished';
                }
			},
            {
				field: 'teacher_checking',
				title: '{{ __("Checked") }}',
                template: function(row) {
                    if (row.teacher_checking == 1) {
						return 'Not checked';
					}
					else if (row.teacher_checking == 2) {
						return 'Checking';
					}
					else {
						return 'Checked';
					}
                }
			},
			{
				field:'created_at',
				title:'{{ __("Submitted Date") }}'
			},
			{
				field: 'Actions',
				title: '{{ __("Actions") }}',
				sortable: false,
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
							<a class="dropdown-item" href="/submissions/'+data.id+'/show"><i class="la la-eye"></i> {{ __("View") }}</a>\
							<a class="dropdown-item" href="/submissions/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Give Marks") }}</a>\
						</div>\
					</div>\
				';
				},
			}
		],
	});

    $('#kt_form_status').on('change', function() {
      datatable.search($(this).val(), 'is_finished');
    });
    
    $('#kt_form_sets').on('change', function() {
      datatable.search($(this).val(), 'set_id');
    });
	
	$('#kt_form_section').on('change', function() {
      datatable.search($(this).val(), 'section');
    });

    $('#kt_form_status,#kt_form_sets,#kt_form_section').selectpicker();

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
</script>
@endpush