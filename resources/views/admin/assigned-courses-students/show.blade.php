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
			'url' => url()->current()
		],
	]
])

<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')

	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						
							<a href="/tests-students/{{ $course->id }}" class="btn btn-brand btn-elevate btn-icon-sm">
							<i class="la la-folder"></i>
							{{ __('View Test') }} 
						</a>
					</div>
				</div>
			</div>
			
		</div>
		<div class="kt-portlet__body">
			
			<div class="row course-detail">
				<div class="col-md-8 col-xl-9" id="render-here">
					<div class="kt-portlet">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-warning course-nav" id="ul-head" role="tablist">
									@if (!empty($videoData['video']))
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#" data-target="#kt_tabs_1_1"><i class="fa fa-file-video"></i>{{ __("Video") }}</a>
										</li>
									@endif
									@if (!empty($topic->audio))
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2"><i class="fa fa-file-audio"></i>{{ __("Audio") }}</a>
										</li>
									@endif
									@if (!empty($topic->image))
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#kt_tabs_1_3"><i class="fa fa-file-image"></i>{{ __("Image") }}</a>
										</li>
									@endif
									@if (!empty($topic->text))
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#kt_tabs_1_4"><i class="fa fa-file-alt"></i>{{ __("Text") }}</a>
										</li>
									@endif
								</ul>
							</div>
						</div>
						<div class="tab-content" id="ul-body">
							@if (!empty($videoData['video']))
								<div class="tab-pane" id="kt_tabs_1_1" role="tabpanel">
									<div class="video-container">
										@if ($videoData['videoType'] == 'video')
											<video id="player" class="video" playsinline controls data-poster="/path/to/poster.jpg">
												<source src="{{ $videoData['video'] }}" type="video/mp4" />

												<track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
											</video>
										@else 
											<iframe class="video" src="{{ $videoData['video'] }}" frameborder="0" allowfullscreen></iframe>
										@endif
									</div>
								</div>
							@endif
							@if (!empty($topic->audio))
								<div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
									<audio class="w-100 p-2" controls src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/audios/' . $topic->audio) }}">Your browser does not support the
										<code>audio</code> element.</audio>
								</div>
							@endif
							@if (!empty($topic->image))
								<div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
									<div class="image-container">
										<img src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/images/' . $topic->image) }}">
									</div>
								</div>
							@endif
							@if (!empty($topic->text))
								<div class="tab-pane p-3" id="kt_tabs_1_4" role="tabpanel">
									@php echo $topic->text; @endphp
								</div>
							@endif
						</div>
						
					</div>
					<div class="course-desc">
						<ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-2x nav-tabs-line-success" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#attachments-tab" role="tab">{{ __("Attachments") }}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#references-tab" role="tab">{{ __("References") }}</a>
							</li>
						</ul>
						<div class="tab-content p-3">
							<div class="tab-pane active mobile" id="attachments-tab" role="tabpanel">
								@if(isset($topic->topic_attachments))@if (!$topic->topic_attachments->isEmpty())
									<div class="kt-portlet kt-portlet--height-fluid">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													Download attachments
												</h3>
											</div>
											<div class="kt-portlet__head-toolbar">
												<a href="/student/download-all/{{ $topic->id }}" class="btn btn-label-brand btn-bold btn-sm" data-toggle="">
													Download all
												</a>
												
											</div>
										</div>
										<div class="kt-portlet__body">

											<!--begin::k-widget4-->
											<div class="kt-widget4">
												@foreach ($topic->topic_attachments as $k => $a)

													@php
														switch ($a->type) {
															case 'jpeg':
																$icon = global_asset('assets/media/files/jpg.svg');
																break;

															case 'jpg':
																$icon = global_asset('assets/media/files/jpg.svg');
																break;
																
															case 'png':
																$icon = global_asset('assets/media/files/jpg.svg');
																break;

															case 'pdf':
																$icon = global_asset('assets/media/files/pdf.svg');
																break;

															case 'zip':
																$icon = global_asset('assets/media/files/zip.svg');
																break;

															case 'doc':
																$icon = global_asset('assets/media/files/doc.svg');
																break;
															
															default:
																$icon = global_asset('assets/media/files/doc.svg');
																break;
														}
													@endphp

													<div class="kt-widget4__item">
														<div class="kt-widget4__pic kt-widget4__pic--icon">
															<img src="{{ $icon }}">
														</div>
														<a href="/student/download/{{ $a->id }}" target="_blank" class="kt-widget4__title">
															{{ $a->title }}
														</a>
														<div class="kt-widget4__tools">
															<a href="#" class="btn btn-clean btn-icon btn-sm">
																<i class="flaticon2-download-symbol-of-down-arrow-in-a-rectangle"></i>
															</a>
														</div>
													</div>

												@endforeach
											</div>

											<!--end::Widget 9-->
										</div>
									</div>
									
									@else
										<p> N/A </p>
									@endif
								@else
									<p> N/A </p>
								@endif
							</div>
							<div class="tab-pane" id="references-tab" role="tabpanel">
								@php $links = !empty($topic->reference_links) ? json_decode($topic->reference_links) : null  @endphp
								@if (!empty($links))
									<div class="kt-portlet">
										<div class="kt-portlet__body">

											@foreach ($links as $k => $link)
												<div class="kt-notification kt-notification--fit">
													<a href="@displaylink($link)" class="kt-notification__item" target="_blank">
														<div class="kt-notification__item-icon">
															<i class="flaticon2-website kt-font-success"></i>
														</div>
														<div class="kt-notification__item-details">
															<div class="kt-notification__item-title">
																{{ $link }}
															</div>
														</div>
													</a>
												</div>
												<div class="kt-separator kt-separator--border-dashed"></div>
											@endforeach

										</div>
									</div>
								@else
									<p> N/A </p>
								@endif
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-4 col-xl-3">
					<h3 class="pt-1">
						{{ $course->title }}
						<button type="button" class="btn btn-brand btn-sm btn-icon btn-circle extra-small-button" data-toggle="kt-popover" title="" data-content="{{ $course->description }}" data-original-title="Course Description"><i class="fa fa-info"></i></button>
						<button type="button" class="btn btn-info btn-sm btn-icon btn-circle extra-small-button" data-toggle="kt-popover" title="" data-content="{{ $course->learn_what }}" data-original-title="Course Learning Outcomes"><i class="fa fa-question"></i></button>
						<button type="button" class="btn btn-warning btn-sm btn-icon btn-circle extra-small-button" data-toggle="kt-popover" title="" data-content="{{ $course->credit_hours }} Hours" data-original-title="Course Credit Hours"><i class="fa fa-clock"></i></button>
					</h3>

					<div class="accordion accordion-toggle-arrow" id="{{ 'accordion_'.$course->id }}">
						@foreach ($course->lessons as $lesson)                         
							@if (!$lesson->topics->isEmpty())
								<div class="card">
									<div class="card-header">
										<div class="card-title" data-toggle="collapse" data-target="#{{ 'collapse_'.$lesson->id }}">
											{{ $lesson->title }} &nbsp;
											<button type="button" class="btn btn-brand btn-sm btn-icon btn-circle extra-small-button" data-toggle="kt-popover" title="" data-content="{{ $lesson->brief }}" data-original-title="Lesson Brief"><i class="fa fa-info"></i></button>
										</div>
									</div>
									<div id="{{ 'collapse_'.$lesson->id }}" class="collapse @if($topic->lesson_id == $lesson->id) show @endif" data-parent="#{{ 'accordion_'.$course->id }}">
										<div class="card-body">
											<div class="kt-notification kt-notification--fit">
												@foreach ($lesson->topics as $t)
													<a data-topic-id="{{ $t->id }}" href="#" class="kt-notification__item topic-select @if($topic->id == $t->id) selected-topic @endif">
														<div class="kt-notification__item-icon">
															<label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
																<input type="checkbox"> 
																<span></span>
															</label>
														</div>
														<div class="kt-notification__item-details">
															<div class="kt-notification__item-title">
																{{ $t->title }}
															</div>
														</div>
													</a>
												@endforeach
											</div>
										</div>
									</div>
								</div>
							@endif
						@endforeach  
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>	
@endsection

@push('styles')
	<link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
@endpush

@push('scripts')
	<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#ul-head li:first-child a').addClass('active');
			$('#ul-body div:first-child').addClass('active');

			$('.topic-select').on('click', function(e) {
				e.preventDefault();

				$('.topic-select').removeClass('selected-topic');
				$(this).addClass('selected-topic');

				let selectedTopicId = $(this).attr('data-topic-id');
				let url = "/student/load-topic/" + selectedTopicId;

				$.ajax({
					type: 'GET',
					url: url,
					dataType: 'json',
					beforeSend: function(response) {
						NProgress.start();
					},
					success: function(response) {
						if (response.result) {
							$('#render-here').html('').html(response.result);
						}

						NProgress.done();
					}
				});
			});
		});
	</script>
@endpush