@extends('layouts.main')
@section('title','Students | '. config("app.name"))
@section('students','kt-menu__item--open')
@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Students'),
        'crumbs' => [
            [
                'name' => __('Students'),
                'url' => '/students'
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
						{{ __('Students') }}
						{{-- <small>initialized from remote json file</small> --}}
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<div class="btn-group show" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="la la-plus"></i>&nbsp; {{ __('Add') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -177px, 0px);" x-placement="top-start">
                                    <a class="dropdown-item" href="/students/create"><i class="fa fa-list"></i>&nbsp;Form</a>
                                    <a class="dropdown-item" href="/students/csv"><i class="fa fa-file-upload"></i>&nbsp;Upload CSV</a>
                                </div>
                            </div>
							
							{{-- <a href="/teachers/create" class="btn btn-brand btn-elevate btn-icon-sm">
								<i class="la la-plus"></i>
								Add
							</a> --}}
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Search Form -->
				<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
					<div class="row align-items-center">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center">
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-input-icon kt-input-icon--left">
										<input type="text" class="form-control" placeholder="{{ __('Search') }}..." id="generalSearch">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="la la-search"></i></span>
										</span>
									</div>
								</div>
								<div class="col-md-2 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>{{ __('Status') }}:</label>
										</div>
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_form_status">
												<option value="" selected>{{ __('All') }}</option>
												<option value="10">{{ __('Invited') }}</option>
												<option value="11">{{ __('Active') }}</option>
												<option value="12">{{ __('Inactive') }}</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>{{ __('Classrooms') }}:</label>
										</div>
										<div class="kt-form__control">
											<select class="form-control bootstrap-select" id="kt_form_classroom">
												<option value="all" selected>{{ __('All') }}</option>
												@foreach($classrooms as $c)
													<option value="{{ $c->id }}">{{ $c->title }}</option>
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
												<option value="all" selected>{{ __('All') }}</option>
												
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

    let url = '{{ url("/api/students-list") }}';

        let options={
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: url,
                        method: 'GET',
                        map: function(raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,	
                // serverSorting: true,
            },

		// layout definition
			layout: {
				scroll: true,
				footer: false,
				spinner:false,
			},

            // column sorting
            sortable: true,

            // Horizontal scroll
            // rows: {
            //     autoHide: !1
            // },

            pagination: true,

			// search: {
			// 	input: $('#generalSearch'),
			// },
			rows:{
				beforeTemplate: function (row, data, index) {
					if(data.status==10){
						row.addClass('cutsom-bg-warning-light')
					}
					if(data.status==12){
						row.addClass('cutsom-bg-danger-light')
					}
					console.log(row);
				}
			},

			// columns definition
			columns: [
				{
					field: 'symbol_number',
					title: '{{ __("Symbol Number") }}',
				},
				{
					field:'image',
					title: ' ',

					width: 50,
					template:function(data){
						return `<a href="/students/${data.id}"><img class="custom-image" src="${data.image}" > </a>`;
					}
				},
				{field:'id',title:'ID',visible:false,width:0},
				{
					field: 'name',
					title: '{{ __("Name") }}',
					template:function(data){
						return `<a href="/students/${data.id}"><span>${data.name}</span></a>`
					}
				}, 

				{
					field: 'email',
					title: '{{ __("Email") }}',
					width: 350,
					
				}, 

				{
					field: 'phone',
					title: '{{ __("Phone Number") }}',
				}, 
				{
					field: 'address',
					title: '{{ __("Address") }}',
				}, 

				{
					field:'classroom',
					title: '{{ __("Classroom") }}'
				},
				{
					field:'created_by',
					title: '{{ __("Created By") }}'
				},
				{
					field: 'status',
					width:0, 
					visible:false,
					title: '{{ __("Status") }}',
					// callback function support for column rendering
					template: function(row) {
						var status = {
							10: {'title': '{{ __("Invited") }}', 'class': 'kt-badge--brand'},
							11: {'title': '{{ __("Active") }}', 'class': ' kt-badge--success'},
							12: {'title': '{{ __("Inactive") }}', 'class': ' kt-badge--danger'},
						};
						return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--inline kt-badge--pill">' + status[row.status].title + '</span>';
					},
				}, 
				{
					field: 'Actions',
					title: "{{ __('Actions') }}",
					sortable: false,
					width: 110,
					overflow: 'visible',
					autoHide: false,
					template: function(data) {
						var status="";
						if (data.status==11){
							status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-times"></i> {{ __('Deactivate') }}</a>`;
						}else{
							status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-check"></i> {{ __('Activate') }}</a>`;
						}
						return '\
						<div class="dropdown">\
							<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
                                <i class="la la-ellipsis-h"></i>\
                            </a>\
						  	<div class="dropdown-menu dropdown-menu-right">\
						    	<a class="dropdown-item" href="/students/'+data.id+'"><i class="la la-eye"></i> {{ __("View") }}</a>\
						    	<a class="dropdown-item" href="/students/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit Details") }}</a>\
						    	'+status+'\
						  	</div>\
						</div>\
					';
					},
				}],

		};

		$("[name='state']").bootstrapSwitch({
			onText: '{{ __("Activate") }}',
			offText: '{{ __("Deactivate") }}',
			state: true,
			onSwitchChange: function(event) {
				if($('#state').val()==0){
					$('#state').val(1);
					$('#state-confirm').removeClass('custom-activate').addClass('custom-deactivate');
				}else{			
					$('#state').val(0);
					$('#state-confirm').removeClass('custom-deactivate').addClass('custom-activate');
				}
			}
		});
		// status change
		$(document).on('click','.status-change',function(e){
			e.preventDefault();
			var status=$(this).data('status');
			var id=$(this).data('id');
			var link=message=header="";
			if(status==11){
				link=`/students/change-status/${id}/2`;
				message="Do you really want to deactivate this account?";
				header="Deactivate account";
			}else{
				link=`/students/change-status/${id}/1`;
				message="Do you really want to activate this account?";
				header="Activate account";
			}

			makeModal(link,message,header,"PATCH");

		});
		// 

		var datatable = $('.kt-datatable').KTDatatable(options);

		// both methods are supported
		// datatable.methodName(args); or $(datatable).KTDatatable(methodName, args);

		$('#kt_datatable_destroy').on('click', function() {
			// datatable.destroy();
			$('.kt-datatable').KTDatatable('destroy');
		});

		$('#kt_datatable_init').on('click', function() {
			datatable = $('.kt-datatable').KTDatatable(options);
		});

		$('#kt_datatable_reload').on('click', function() {
			// datatable.reload();
			$('.kt-datatable').KTDatatable('reload');
		});

		$('#kt_datatable_sort_asc').on('click', function() {
			datatable.sort('Status', 'asc');
		});

		$('#kt_datatable_sort_desc').on('click', function() {
			datatable.sort('Status', 'desc');
		});

		// get checked record and get value by column name
		$('#kt_datatable_get').on('click', function() {
			// select active rows
			datatable.rows('.kt-datatable__row--active');
			// check selected nodes
			if (datatable.nodes().length > 0) {
				// get column by field name and get the column nodes
				var value = datatable.columns('CompanyName').nodes().text();
				console.log(value);
			}
		});

		// record selection
		$('#kt_datatable_check').on('click', function() {
			var input = $('#kt_datatable_check_input').val();
			datatable.setActive(input);
		});

		$(document).on('click','.custom-select-all', function() {
			// datatable.setActiveAll(true);
			$('.kt-datatable').KTDatatable('setActiveAll', true);
		});

		$(document).on('click','.custom-unselect-all', function() {
			// datatable.setActiveAll(false);
			$('.kt-datatable').KTDatatable('setActiveAll', false);
		});

		$(document).on('click','.custom-activate', function() {
	
			var link=message=header="";
			
			link=`/students/change-status-bulk/1`;
			message="Do you really want to activate these account?";
			header="Activate account(s)";
			

			var arr= new Array();
			var dt=(datatable.getSelectedRecords());
	
			$.each(datatable.getRecord().getColumn('id').API.value, function(i,k){
				console.log(k.children[0].childNodes[0].data);
				arr.push(k.children[0].childNodes[0].data);

				// console.log();
			})

			if(arr.length<1){
				toastr.error('Please select at least one user from the table.')
				return false;
			}
		
			var input=new Array(
				`<input name="user_list" hidden multiple value="[${arr}]">`
			);
			makeModal(link,message,header,"PATCH",input);
			
		});

		$(document).on('click','.custom-deactivate', function() {
			var link=message=header="";
			
			link=`/students/change-status-bulk/2`;
			message="Do you really want to deactivate these account?";
			header="Deactivate account(s)";
			

			var arr= new Array();
			var dt=(datatable.getSelectedRecords());
	
			$.each(datatable.getRecord().getColumn('id').API.value, function(i,k){
				console.log(k.children[0].childNodes[0].data);
				arr.push(k.children[0].childNodes[0].data);

				// console.log();
			})

			if(arr.length<1){
				toastr.error('Please select at least one user from the table.')
				return false;
			}
		
			var input=new Array(
				`<input name="user_list" hidden multiple value="[${arr}]">`
			);
			makeModal(link,message,header,"PATCH",input);
		});

		$('#kt_datatable_remove_row').on('click', function() {
			datatable.rows('.kt-datatable__row--active').remove();
		});



		$('#kt_form_status, #kt_form_classroom, #kt_form_section,#kt_form_type').selectpicker();
		
		$('#kt_form_status').on('change',function(e){
			datatable.search($(this).val(),'Status');
		})
		$('#generalSearch').on('change keydown key',function(e){
			
			timeout = setTimeout(function() {
				// console.log(val)
				datatable.search($('#generalSearch').val(),'generalSearch');
			}, 500)
		})

		$('#kt_form_classroom').on('change',function(e){
			var id=$(this).val();
			var s=$('#kt_form_section');
			datatable.search(id,'Classroom');
			$.ajax({
				url:'/api/get-section/'+id,
				dataType:'JSON',
				type: 'GET',
				error:function(res){
					console.log(res)
					s.html('');
					s.append(`<option value="all" selected>All</option>`);
					s.selectpicker('refresh');
				},
				success: function (res) {
					if (res !== []) {
						s.html('');
						s.append('<option selected value="all">All</option>');
						$.each(res, function( index, value ) {
							let html = `<option value="${value.id}">${value.title}</option>`;

							s.append(html);
						});

						s.selectpicker('refresh');
					}
					else {
						s.html('');
						s.selectpicker('refresh');
					}
				}
			});
		})
		$('#kt_form_section').on('change',function(e){
			var id=$(this).val();
			datatable.search(id,'Section');
		});
</script>
@endpush