@extends('layouts.main')
@section('title','Meetings | '. config("app.name"))
@section('meetings','kt-menu__item--open')
@section('meetings','kt-menu__item--open')
@push('styles')
<style>
    .tOoji a{
        display: none !important;
    }
</style>
@endpush
@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Virtual Classrooms"),
	'crumbs' => [
		[
			'name' => __("Virtual Classrooms"),
			'url' => '/meetings',
        ],
        [
            'name'=> __("View Videos"),
            'url'=> url()->current(),
        ]
	]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("View Videos") }}
				</h3>
            </div>
			
			
		</div>
        <div class="kt-portlet__body">

                <!--begin: Datatable -->
                <div class="kt-datatable" id="api_methods"></div>

                <!--end: Datatable -->
            
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
	var dataJSONArray = @json($m);
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
				field:'sn',
				title:'{{ __("SN") }}',
                width:30,
			},
			{
				field: 'video_url',
				title: '{{ __("Video") }}',
				template:function(data)
				{
					return `
  <iframe class="embed-responsive-item" src="${data.video_url}"></iframe>
`
				}
			},

			

			{field:'id',title:'ID',visible:false,width:0},
            {
                field:'created_at',
                title:'{{ __("Recorded at") }}'
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
							<a class="dropdown-item" target="_blank" href="'+data.video_url+'"><i class="la la-eye"></i> View</a>\
							<a data-id="'+data.id+'" class="dropdown-item item-delete" href=""><i class="la la-trash"></i> Delete</a>\
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
		link=`/meetings/${id}/video`;
		message="Do you really want to delete this meeting?";
		header="Delete meeting";
		makeModal(link,message,header,"DELETE");

	})
</script>
@endpush