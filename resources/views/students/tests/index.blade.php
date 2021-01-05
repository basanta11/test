@extends('layouts.main')
@section('title','Assigned Courses | '. config("app.name"))
@section('assigned-courses-students','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Assigned Courses"),
	'crumbs' => [
		[
			'name' => __("Assigned Courses"),
			'url' => '/student/assigned-courses'
		],
		[
			'name' => __("View Course"),
			'url' => '/student/assigned-courses/'.$id,
		],
		[
			'name' => 'View Test',
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
					{{ __("Tests") }}
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						
							<a href="/tests-results/{{ $id }}/index" class="btn btn-brand btn-elevate btn-icon-sm">
							<i class="la la-list"></i>
							{{ __('View Results') }} 
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
	var dataJSONArray = @json($tests);
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
				title: '{{ __("Title") }}',
				template:function(data)
				{
					return `<a href="/tests-students/${data.id}/view"><span>${data.title}</span></a>`
				}
			},

			{field:'id',title:'ID',visible:false,width:0},
            {
				field: 'type',
				title: '{{ __("Type") }}'
			},
			{
				field: 'lesson',
				title: '{{ __("Lesson") }}'
			},
			{
				field: 'test_start',
				title: '{{ __("Date") }}'
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
				field: 'is_finished',
				title: '{{ __("Status") }}',
				template: function(row) {
					if (row.is_finished == 10) {
						return 'Incomplete';
					}
					else if (row.is_finished == 11) {
						return 'Finished';
					}
					else {
						return 'Not Started';
					}
				}
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
					var result="";
					if(data.set_id){
						result='<a class="dropdown-item" href="/tests-students/'+data.set_id+'/result"><i class="la la-list-alt"></i> Result</a>';
					}
					return '\
					<div class="dropdown">\
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
							<i class="la la-ellipsis-h"></i>\
						</a>\
						<div class="dropdown-menu dropdown-menu-right">\
							<a class="dropdown-item" href="/tests-students/'+data.id+'/view"><i class="la la-eye"></i> View</a>\
							'+result+'\
						</div>\
					</div>\
				';
				},
			}
		],
	});

  
</script>
@endpush