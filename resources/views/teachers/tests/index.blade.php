@extends('layouts.main')
@section('title','Tests | '. config("app.name"))
@section('assigned-courses','kt-menu__item--open')
@section('assigned-courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Tests"),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' => '/assigned-courses/'.$lesson['course']['id'],
			],
			
            [
                'name' => $lesson['title'],
                'url' => '/lessons/'.$lesson['id'],
            ],
            [
                'name' => 'Tests',
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
					{{ __("Tests") }}
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        
						<a href="/tests/create/{{ $lesson->id }}" class="btn btn-brand btn-elevate btn-icon-sm">
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
				field:'sn',
				title:'{{ __("SN") }}'
			},
			{
				field: 'title',
				title: '{{ __("Title") }}',
				template:function(data)
				{
					return `<a href="/tests/${data.id}/view"><span>${data.title}</span></a>`
				}
			},

			{
				field: 'sets',
				title: '{{ __("Sets") }}',
				sortable: false,
				template: function(data) {
					let sets = "";
					sets += `
						<div class="kt-list-timeline">
							<div class="kt-list-timeline__items">
					`;

					$.each(data.sets, function( index, value ) {
						sets += `
							<div class="kt-list-timeline__item">
								<span class="kt-list-timeline__badge kt-list-timeline__badge--primary"></span>
								<span class="kt-list-timeline__text">`+value.title+`</span>
							</div>
						`;
					});

					sets += `
							</div>
						</div>
					`;

					return sets;
				}
			},

			{field:'id',title:'ID',visible:false,width:0},
			{
				field: 'lesson',
				title: '{{ __("Lesson Name") }}'
			},
			{
				field: 'type',
				title: '{{ __("Test Type") }}',
				width: 60,
			},

			{
				field: 'test_start',
				title: '{{ __("Date") }}'
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
					// console.log(data.result)
					if(data.result==1){
						result='<a class="dropdown-item disable-result-item" data-id="'+data.id+'" href="#"><i class="la la-folder"></i> {{ __("Disable Result") }}</a>'
					}else{
						result='<a class="dropdown-item enable-result-item" data-id="'+data.id+'" href="#"><i class="la la-folder-open"></i> {{ __("Enable Result") }}</a>'
					}
					return '\
					<div class="dropdown">\
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
							<i class="la la-ellipsis-h"></i>\
						</a>\
						<div class="dropdown-menu dropdown-menu-right">\
							<a class="dropdown-item" href="/tests/'+data.id+'/view"><i class="la la-eye"></i> {{ __("View") }}</a>\
							<a class="dropdown-item" href="/tests/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit") }}</a>\
							<a class="dropdown-item" href="/tests/submissions/'+data.id+'"><i class="la la-folder"></i> {{ __("View Submission") }}</a>\
							<a class="dropdown-item delete-item" data-id="'+data.id+'" href="#"><i class="la la-trash"></i> {{ __("Delete") }}</a>\
							'+result+'\
						</div>\
					</div>\
				';
				},
			}
		],
	});

    
    $('#kt_form_status,#kt_form_type').selectpicker();
	$(document).on('click','.delete-item',function(e){
		e.preventDefault();
		var id=$(this).data('id');
		var link=message=header="";
		link=`/tests/${id}`;
		message="Do you really want to delete this test?";
		header="Delete test?";
		

		makeModal(link,message,header,"DELETE");
	});
	$(document).on('click','.disable-result-item',function(e){
		e.preventDefault();
		var id=$(this).data('id');
		var link=message=header="";
		link=`/tests/${id}/change-result/0`;
		message="Do you really want to disable result of this test?";
		header="Disable result?";
		makeModal(link,message,header,"PATCH");
	})
	$(document).on('click','.enable-result-item',function(e){
		e.preventDefault();
		var id=$(this).data('id');
		var link=message=header="";
		link=`/tests/${id}/change-result/1`;
		message="Do you really want to enable result of this test?";
		header="Enable result?";
		makeModal(link,message,header,"PATCH");
	})
	
</script>
@endpush