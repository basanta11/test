@extends('layouts.main')
@section('title','Topics | '. config("app.name"))
@if ( !auth()->user()->hasRole('Teacher') )
    @section('courses','kt-menu__item--open')
@else
    @section('assigned-courses','kt-menu__item--open')
@endif

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Topics'),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses/'.$lesson['course']['id'] : '/assigned-courses/'.$lesson['course']['id'],
            ],
            [
                'name' => $lesson['title'],
                'url' => '/lessons/'.$lesson['id'],
            ],
            [
                'name' => __('View Topic'),
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
                                            {{ $topic->title }}
                                            @if($topic->status!=1) <i class="text-danger flaticon2-exclamation"></i> @else <i class="flaticon2-correct"></i> @endif
                                        </a>
                                        <div class="kt-widget__action">

                                            <a href="/topics/{{ $topic->id }}/edit"><button type="button"  class="btn btn-warning btn-sm btn-upper">{{ __("Edit") }}</button></a>&nbsp;
                                            
                                            @if($topic->status!=1)
                                                <button type="button" data-status="{{ $topic->status }}" href="#" data-id="{{ $topic->id }}" class="status-change-course btn btn-label-success btn-sm btn-upper">{{ __("Activate") }}</button>&nbsp;
                                            @else
                                                <button type="button" data-status="{{ $topic->status }}" href="#" data-id="{{ $topic->id }}" class="status-change-course btn btn-label-danger btn-sm btn-upper">{{ __("Deactivate") }}</button>&nbsp;

                                            @endif
                                        </div>
                                    </div>
                                   
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__text">
                                            <label>{{ __("Lesson") }}: </label>
                                            {{ $topic['lesson']['title'] }}
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>
					</div>
					<div class="kt-portlet">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									{{ __("Resources") }}
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body">
							<div class="kt-notes">
								<div class="kt-notes__items">
									<div class="kt-notes__item pb-5">
										<div class="kt-notes__media">
											<span class="kt-notes__icon">
												<i class="fa fa-video kt-font-brand"></i>
											</span>
										</div>
										<div class="kt-notes__content">
											<div class="kt-notes__section">
												<div class="kt-notes__info">
													<a @if($resources['video'])target="_blank" @endif href="{{ $resources['video'] ? $resources['video']['video'] : 'javascript:void(0);' }}" class="kt-notes__title">
														{{ __("Video") }}
													</a>
													@if($resources['video'])
													<span class="kt-badge kt-badge--primary kt-badge--inline">{{ __("Added") }}</span>
													@else
													<span class="kt-badge kt-badge--danger kt-badge--inline">{{ __("Unavailable") }}</span>
													@endif
												</div>
												<div class="kt-notes__dropdown">
													<a href="#" class="btn btn-sm btn-icon-md btn-icon" data-toggle="dropdown" aria-expanded="false">
														<i class="flaticon-more-1 kt-font-brand"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right" style="">
														<ul class="kt-nav">
															@if($resources['video'])
															<li class="kt-nav__item">
																<a href="/topics/resources/{{ $topic->id }}/video/edit" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-edit"></i>
																	<span class="kt-nav__link-text">{{ __("Edit") }}</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-cross"></i>
																	<span class="kt-nav__link-text custom-del" data-type="video">{{ __("Delete") }}</span>
																</a>
															</li>
															@else
															<li class="kt-nav__item">
																<a href="/topics/resources/create/{{ $topic->id }}/video" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-plus"></i>
																	<span class="kt-nav__link-text">{{ __("Add") }}</span>
																</a>
															</li>
															@endif
														</ul>
													</div>
												</div>
											</div>

											<span class="kt-notes__body">
												@if($resources['video'])
												<div class="video-container mt-4 old-file" style="width: 100%;
												padding-top: 300px;
												object-fit:contain;
												position: relative;">
												@if ($resources['video']['videoType'] == 'video')
													<video id="player" class="video" playsinline controls data-poster="/path/to/poster.jpg">
														<source src="{{ $resources['video']['video'] }}" type="video/mp4" />

														<track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
													</video>
													@else
													
													<iframe class="video" src="{{ $resources['video']['video'] }}" frameborder="0" allowfullscreen></iframe>
												@endif
												</div>
												@endif
											</span>
										</div>
									</div>
									<div class="kt-notes__item pb-5">
										<div class="kt-notes__media">
											<span class="kt-notes__icon">
												<i class="fa fa-music kt-font-success"></i>
											</span>
										</div>
										<div class="kt-notes__content">
											<div class="kt-notes__section">
												<div class="kt-notes__info">
													<a @if($resources['audio'])target="_blank" @endif href="{{ $resources['audio'] ? $resources['audio'] : 'javascript:void(0);' }}" class="kt-notes__title">
														{{ __("Audio") }}
													</a>
													@if($resources['audio'])
													<span class="kt-badge kt-badge--primary kt-badge--inline">{{ __("Added") }}</span>
													@else
													<span class="kt-badge kt-badge--danger kt-badge--inline">{{ __("Unavailable") }}</span>
													@endif
												</div>
												<div class="kt-notes__dropdown">
													<a href="#" class="btn btn-sm btn-icon-md btn-icon" data-toggle="dropdown" aria-expanded="false">
														<i class="flaticon-more-1 kt-font-brand"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right" style="">
														<ul class="kt-nav">
															@if($resources['audio'])
															<li class="kt-nav__item">
																<a href="/topics/resources/{{ $topic->id }}/audio/edit" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-edit"></i>
																	<span class="kt-nav__link-text">{{ __("Edit") }}</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-cross"></i>
																	<span class="kt-nav__link-text custom-del" data-type="audio">{{ __("Delete") }}</span>
																</a>
															</li>
															@else
															<li class="kt-nav__item">
																<a href="/topics/resources/create/{{ $topic->id }}/audio" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-plus"></i>
																	<span class="kt-nav__link-text">{{ __("Add") }}</span>
																</a>
															</li>
															@endif
															
														</ul>
													</div>
												</div>
											</div>

											<span class="kt-notes__body">
												@if (!empty($resources['audio']))
													<div class="old-file" >
													<audio class="w-100" controls src="{{ $resources['audio'] }}">Your browser does not support the
														<code>audio</code> element.</audio>
													</div>
												@endif
											</span>
										</div>
									</div>
									<div class="kt-notes__item pb-5">
										<div class="kt-notes__media">
											<span class="kt-notes__icon">
												<i class="fa fa-image kt-font-primary"></i>
											</span>
										</div>
										<div class="kt-notes__content">
											<div class="kt-notes__section">
												<div class="kt-notes__info">
													<a @if($resources['image'])target="_blank" @endif href="{{ $resources['image'] ? $resources['image'] : 'javascript:void(0);'  }}" class="kt-notes__title">
														{{ __("Image") }}
													</a>
													@if($resources['image'])
													<span class="kt-badge kt-badge--primary kt-badge--inline">{{ __("Added") }}</span>
													@else
													<span class="kt-badge kt-badge--danger kt-badge--inline">{{ __("Unavailable") }}</span>
													@endif
												</div>
												<div class="kt-notes__dropdown">
													<a href="#" class="btn btn-sm btn-icon-md btn-icon" data-toggle="dropdown" aria-expanded="false">
														<i class="flaticon-more-1 kt-font-brand"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right" style="">
														<ul class="kt-nav">
															@if($resources['image'])
															<li class="kt-nav__item">
																<a href="/topics/resources/{{ $topic->id }}/image/edit" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-edit"></i>
																	<span class="kt-nav__link-text">{{ __("Edit") }}</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-cross"></i>
																	<span class="kt-nav__link-text custom-del" data-type="image">{{ __("Delete") }}</span>
																</a>
															</li>
															@else
															<li class="kt-nav__item">
																<a href="/topics/resources/create/{{ $topic->id }}/image" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-plus"></i>
																	<span class="kt-nav__link-text">{{ __("Add") }}</span>
																</a>
															</li>
															@endif
														</ul>
													</div>
												</div>
											</div>
											<span class="kt-notes__body">
												@if (!empty($resources['image']))
													<div class="old-file">
														<div class="image-container">
															<a href="{{ $resources['image'] }}" target="_blank">
																<img style="width:100%; height: 300px; object-fit:cover;" src="{{ $resources['image'] }}">
															</a>
														</div>
													</div>
												@endif
											</span>
										</div>
									</div>
									<div class="kt-notes__item pb-5">
										<div class="kt-notes__media">
											<span class="kt-notes__icon">
												<i class="fa fa-sticky-note kt-font-secondary"></i>
											</span>
										</div>
										<div class="kt-notes__content">
											<div class="kt-notes__section">
												<div class="kt-notes__info">
													<a href="javascript:void(0);" class="kt-notes__title">
														{{ __("Text") }}
													</a>
													@if($resources['text'])
													<span class="kt-badge kt-badge--primary kt-badge--inline">{{ __("Added") }}</span>
													@else
													<span class="kt-badge kt-badge--danger kt-badge--inline">{{ __("Unavailable") }}</span>
													@endif
												</div>
												<div class="kt-notes__dropdown">
													<a href="javascript:void(0);" class="btn btn-sm btn-icon-md btn-icon" data-toggle="dropdown" aria-expanded="false">
														<i class="flaticon-more-1 kt-font-brand"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-right" style="">
														<ul class="kt-nav">
															@if($resources['text'])
															<li class="kt-nav__item">
																<a href="/topics/resources/{{ $topic->id }}/text/edit" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-edit"></i>
																	<span class="kt-nav__link-text">{{ __("Edit") }}</span>
																</a>
															</li>
															<li class="kt-nav__item">
																<a href="#" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-cross"></i>
																	<span class="kt-nav__link-text custom-del" data-type="text">{{ __("Delete") }}</span>
																</a>
															</li>
															@else
															<li class="kt-nav__item">
																<a href="/topics/resources/create/{{ $topic->id }}/text" class="kt-nav__link">
																	<i class="kt-nav__link-icon flaticon2-plus"></i>
																	<span class="kt-nav__link-text">{{ __("Add") }}</span>
																</a>
															</li>
															@endif
														</ul>
													</div>
												</div>
											</div>
											<span class="kt-notes__body">
												{!! $topic->text !!}
											</span>
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
                                    {{ __("References") }}
                                    {{-- <small>initialized from remote json file</small> --}}
                                </h3>
							</div>
						</div>

                        <div class="kt-portlet__body">
							@if($topic->reference_links)
							@foreach(json_decode($topic->reference_links) as $rl)
							<div class="kt-list-timeline">
								<div class="kt-list-timeline__items">
									<div class="kt-list-timeline__item">
										<span class="kt-list-timeline__badge"></span>
									<span class="kt-list-timeline__text"><a target="_blank" href="{{ $rl }}">{{ $rl }}</a></span>
										
									</div>
								</div>
							</div>
							@endforeach
							@else
							<span>{{ __("No records found") }}.</span>
							@endif
                        </div>
					</div>
                    <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__head kt-portlet__head--lg">
                            <div class="kt-portlet__head-label">
                                <span class="kt-portlet__head-icon">
                                    <i class="kt-font-brand flaticon2-list-2"></i>
                                </span>
                                <h3 class="kt-portlet__head-title">
                                    {{ __("Attachments") }}
                                    {{-- <small>initialized from remote json file</small> --}}
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-wrapper">
                                    <div class="kt-portlet__head-actions">
                                        
                                            <a href="/topics/attachments/create/{{ $topic->id }}" class="btn btn-brand btn-elevate btn-icon-sm">
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
                                                    <input type="text" class="form-control" placeholder="{{ __("Search") }}..." id="generalSearch">
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
	const topicId='{{ $topic->id }}';
    $(document).on('click','.status-change-course',function(e){
        e.preventDefault();
        var status=$(this).data('status');
        var id=$(this).data('id');
        var link=message=header="";
        if(status==1){
			link=`/topics/change-status/${id}/0`;
			message="Do you really want to deactivate this topic?";
			header="Deactivate topic";
		}else{
			link=`/topics/change-status/${id}/1`;
			message="Do you really want to activate this topic?";
			header="Activate topic";
		}

        makeModal(link,message,header,"PATCH");

    });

	$('.custom-del').on('click',function(e){
		e.preventDefault();
		var type=$(this).data('type');
		var link=`/topics/resources/${topicId}/`;
		var message=`Do you really want to delete this ${type}?`;
		var header=`Delete ${type}`;
		var input=new Array(
			`<input name="type" type="text" hidden value="${type}">`
		);
		makeModal(link,message,header,"DELETE",input);
	})

    var dataJSONArray = @json($attachments);
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
				title: '{{ __("Attachment") }}',
				template:function(data){
					return `<a target="_blank" href="${data.link}"><span>${data.title}</span></a>`
				}
			}, 
			{
				field:'id',title:'ID',width:0,
				visible:false,
			},
            {
                field:'type',
                title:'{{ __("Type") }}',
            },
			{
				field: 'status',visible:false, width:0,
				title: '{{ __("Status") }}',
				// callback function support for column rendering
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
					if (data.status==11){
						status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-times"></i> {{ __("Deactivate") }}</a>`;
					}else{
						status=`<a class="dropdown-item status-change" data-status="${data.status}" href="#" data-id="${data.id}" ><i class="la la-check"></i> {{ __("Activate") }}</a>`;
					}
					return '\
					<div class="dropdown">\
						<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
							<i class="la la-ellipsis-h"></i>\
						</a>\
						<div class="dropdown-menu dropdown-menu-right">\
						    	<a class="dropdown-item" target="_blank" href="'+data.link+'"><i class="la la-eye"></i> {{ __("View") }}</a>\
							<a class="dropdown-item" href="/topics/attachments/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit Details") }}</a>\
							'+status+'\
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

    $('#kt_form_status').on('change', function() {
      	datatable.search($(this).val().toLowerCase(), 'status');
    });

    $('#kt_form_status,#kt_form_type').selectpicker();

	// status change
	$(document).on('click','.status-change',function(e){
		e.preventDefault();
		var status=$(this).data('status');
		var id=$(this).data('id');
		var link=message=header="";
		if(status==11){
			link=`/topics/change-status-attach/${id}/0`;
			message="Do you really want to deactivate this attachment?";
			header="Deactivate attachment";
		}else{
			link=`/topics/change-status-attach/${id}/1`;
			message="Do you really want to activate this attachment?";
			header="Activate attachment";
		}

		makeModal(link,message,header,"PATCH");

	});

	$(document).on('click','.custom-activate', function() {
		
		var link=message=header="";
		
		link=`/topics/change-status-bulk-attach/1`;
		message="Do you really want to activate these attachments?";
		header="Activate attachment(s)";
		

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

	$(document).on('click','.custom-deactivate', function() {
		var link=message=header="";
		
		link=`/topics/change-status-bulk-attach/0`;
		message="Do you really want to deactivate these attachments?";
		header="Deactivate attachment(s)";
		

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

	$(document).on('click','.custom-select-all', function() {
		// datatable.setActiveAll(true);
		$('.kt-datatable').KTDatatable('setActiveAll', true);
	});

	$(document).on('click','.custom-unselect-all', function() {
		// datatable.setActiveAll(false);
		$('.kt-datatable').KTDatatable('setActiveAll', false);
	});


</script>
@endpush