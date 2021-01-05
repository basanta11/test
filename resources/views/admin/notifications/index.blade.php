@extends('layouts.main')
@section('title','Notifications | '. config("app.name"))
@section('dashboard','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Dashboard'),
        'crumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => '/app'
            ],
            [
                'name' => __('Notifications'),
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
						{{ __('Notifications') }}
						{{-- <small>initialized from remote json file</small> --}}
					</h3>
				</div>
				
			</div>
			{{-- <div class="kt-portlet__body">

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
								
						</div>
					</div>
				</div>
			</div> --}}
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

    let url = '{{ url("/api/notifications") }}';

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
		

			// columns definition
			columns: [
				
				{
					field: 'created_at',
					title: '{{ __("Date") }}',
				}, 
				{field:'id',title:'ID',visible:false,width:0},
				
				{
					field: 'notification',
					title: '{{ __("Notification") }}',
					template:function(data){
						return `<a href="${data.link}"><span>${data.notification}</span></a>`
					}
				}, 

				
			],

		};

		
		var datatable = $('.kt-datatable').KTDatatable(options);

		
</script>
@endpush