@extends('layouts.main')
@section('title','Assigned Courses | '. config("app.name"))
@section('assigned-courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Assigned Courses"),
        'crumbs' => [
            [
                'name' => __("Assigned Courses"),
                'url' => '/assigned-courses'
            ],
        ]
	])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="row">
		@foreach($courses as $course)
			<div class="col-sm-6 col-md-4 col-lg-3">
				<a href="/assigned-courses/{{ $course['id'] }}">
					<div class="kt-portlet kt-portlet--mobile btn custom-hoverable">
						<div class="kt-portlet__head w-100 no-border-bottom no-padding">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									<strong>{{ $course['title'] }} </strong>
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body w-100 text-left no-padding minus-margin smaller-font" title="{{ $course['learn_what'] }}">
							{{ mb_strimwidth($course['learn_what'], 0, 80, "...") }}
						</div>
						<div class="kt-portlet__foot w-100 no-border-top mt-3 mb-3 no-padding">
							<div class="row">
								<div class="col-lg-7 kt-align-left">
									<span class="kt-margin-left-10 smaller-font"><div>{{ __("Classroom") }}</div>
										<b>{{ $course['classroom'] }} <small>(@foreach($course['sections'] as $sec) {{ $sec->title }} @if(!$loop->last) , @endif @endforeach)</small></b>
									</span>
								</div>
								<div class="col-lg-5 kt-align-right">
									<span class="kt-margin-left-10 smaller-font"><div>{{ __("Credit Hours") }}</div> 
										<b class="kt-font-brand">{{ $course['credit_hours'] }}</b>
									</span>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
		@endforeach
	</div>
</div>	
@endsection
@push('scripts')
<script>
	// var dataJSONArray = @json($courses);
    // var datatable = $('.kt-datatable').KTDatatable({
	// 	// datasource definition
	// 	data: { 
	// 		type: 'local',
	// 		source: dataJSONArray,
	// 		pageSize: 10,
	// 	},

	// 	// layout definition
	// 	layout: {
	// 		scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
	// 		footer: false // display/hide footer
	// 	},

	// 	// column sorting
	// 	sortable: true,

	// 	pagination: true,

	// 	search: {
	// 		input: $('#generalSearch')
	// 	},
	// 	rows:{
	// 		beforeTemplate: function (row, data, index) {
	// 			if(data.status==10){
	// 				row.addClass('cutsom-bg-danger-light')
	// 				row.data('status',data.status)
	// 			}
	// 		}
	// 	},

	// 	// columns definition
	// 	columns: [
			
	// 		{
	// 			field: 'title',
	// 			title: '{{ __("Course") }}',
	// 			template:function(data){
	// 				return `<a href="/assigned-courses/${data.id}"><span>${data.title}</span></a>`
	// 			}
	// 		}, 
	// 		{
	// 			field: 'classroom',
	// 			title: '{{ __("Classroom") }}'
	// 		},

	// 		{
	// 			field: 'sections',
	// 			title: '{{ __("Sections") }}',
	// 			sortable: false,
	// 			template: function(data) {
	// 				let sections = "";
	// 				sections += `
	// 					<div class="kt-list-timeline">
	// 						<div class="kt-list-timeline__items">
	// 				`;

	// 				$.each(data.sections, function( index, value ) {
	// 					sections += `
	// 						<div class="kt-list-timeline__item">
	// 							<span class="kt-list-timeline__badge kt-list-timeline__badge--primary"></span>
	// 							<span class="kt-list-timeline__text">`+value.title+`</span>
	// 						</div>
	// 					`;
	// 				});

	// 				sections += `
	// 						</div>
	// 					</div>
	// 				`;

	// 				return sections;
	// 			}
	// 		},
	// 		{
	// 			field: 'credit_hours',
	// 			title: '{{ __("Credit Hours") }}'
	// 		},

	// 		{
	// 			field: 'status',width:0,visible:false,
	// 			title: '{{ __("Status") }}',
	// 			// callback function support for column rendering
	// 			template: function(row) {
	// 				var status = {
	// 					10: {'title': '{{ __("Inactive") }}', 'class': 'kt-badge--danger'},
	// 					11: {'title': '{{ __("Active") }}', 'class': ' kt-badge--success'}
	// 				};
	// 				return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--inline kt-badge--pill">' + status[row.status].title + '</span>';
	// 			},
	// 		}, 
	// 		{
	// 			field: 'Actions',
	// 			title: '{{ __("Actions") }}',
	// 			sortable: false,
	// 			width: 110,
	// 			overflow: 'visible',
	// 			autoHide: false,
	// 			template: function(data) {
	// 				return '\
	// 				<div class="dropdown">\
	// 					<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
	// 						<i class="la la-ellipsis-h"></i>\
	// 					</a>\
	// 					<div class="dropdown-menu dropdown-menu-right">\
	// 						<a class="dropdown-item" href="/assigned-courses/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit Details") }}</a>\
	// 					</div>\
	// 				</div>\
	// 			';
	// 			},
	// 		}
	// 	],
	// });

    // $('#kt_form_status').on('change', function() {
    //   	datatable.search($(this).val().toLowerCase(), 'status');
    // });

    // $('#kt_form_status,#kt_form_type').selectpicker();
</script>
@endpush