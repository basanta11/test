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
                'url' => '/tests/'.$lesson['id'],
            ],

            [
                'name' => 'View',
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		
        <!--Begin::Section-->
        <div class="row">
            <div class="col-xl-12">

                <!--begin:: Widgets/Applications/User/Profile3-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__content pl-0">
                                    <div class="kt-widget__head">
                                        <a href="#" class="kt-widget__username">
                                            {{ $test->title }}
                                            @if($test->status!=1) <i class="text-danger flaticon2-exclamation"></i> @else <i class="flaticon2-correct"></i> @endif
                                        </a>
                                        <div class="kt-widget__action">

                                            <a href="/tests/{{ $test->id }}/edit"><button type="button"  class="btn btn-warning btn-sm btn-upper">{{ __("Edit") }}</button></a>&nbsp;
                                            
                                            @if($test->status!=1)
                                                <button type="button" data-status="{{ $test->status }}" href="#" data-id="{{ $test->id }}" class="status-change-course btn btn-label-success btn-sm btn-upper">{{ __("Activate") }}</button>&nbsp;
                                            @else
                                                <button type="button" data-status="{{ $test->status }}" href="#" data-id="{{ $test->id }}" class="status-change-course btn btn-label-danger btn-sm btn-upper">{{ __("Deactivate") }}</button>&nbsp;

                                            @endif
                                        </div>
                                    </div>
                                   
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__text">
                                            <label>{{ __("Lesson") }}: </label>
                                            {{ $lesson->title }}
                                        </div>
                                        
									</div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__head kt-portlet__head--lg">
                            <div class="kt-portlet__head-label">
                                <span class="kt-portlet__head-icon">
                                    <i class="kt-font-brand flaticon2-list-2"></i>
                                </span>
                                <h3 class="kt-portlet__head-title">
                                    {{ __("Sets") }}
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
                                                    <input type="text" class="form-control" placeholder="{{ __("Search") }}..." id="generalSearch">
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

                

                <!--end:: Widgets/Applications/User/Profile3-->
            </div>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script>
    $(document).on('click','.delete-item',function(e){
        e.preventDefault();
        var status=$(this).data('status');
        var id=$(this).data('id');
        var link=message=header="";
        link=`/test-sets/${id}/delete`;
        message="Do you really want to delete this set?";
        header="Delete set";

        makeModal(link,message,header,"DELETE");

    });

    var dataJSONArray = @json($sets);
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
				field: 'title',
				title: '{{ __("Set") }}',
				template:function(data){
					return `<a href="/test-sets/${data.id}"><span>${data.title}</span></a>`
				}
			},
			
			// {
			// 	field: 'status',
			// 	title: '{{ __("Status") }}', width:0, visible:false,
			// 	// callback function support for column rendering
			// 	template: function(row) {
			// 		var status = {
			// 			10: {'title': '{{ __("Inactive") }}', 'class': 'kt-badge--danger'},
			// 			11: {'title': '{{ __("Active") }}', 'class': ' kt-badge--success'}
			// 		};
			// 		return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--inline kt-badge--pill">' + status[row.status].title + '</span>';
			// 	},
			// }, 
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
						    	<a class="dropdown-item" href="/test-sets/'+data.id+'"><i class="la la-eye"></i> {{ __("View") }}</a>\
                                <a class="dropdown-item delete-item" data-id="'+data.id+'" href=""><i class="la la-trash"></i> {{ __("Delete") }}</a>\
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
    
    $('#kt_form_status,#kt_form_type').selectpicker();

	

</script>
@endpush