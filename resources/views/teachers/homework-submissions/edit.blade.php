@extends('layouts.main')
@section('title','Homeworks | '. config("app.name"))
@section('homeworks','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Homeworks"),
        'crumbs' => [
            [
                'name'=>__('Homeworks'),
                'url'=> '/homeworks',
			],
			[
                'name'=>__('Submissions'),
                'url'=> '/homework-submissions/'.$homeworkUser->homework_id,
            ],
            [
                'name'=>__('Edit Submission'),
                'url'=> url()->current(),
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
					{{ __("Homework Submission") }}
				</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
             
            </div>
			
		</div>
		
		<div class="kt-portlet kt-portlet--height-fluid">
			<div class="kt-portlet__body">
				<div class="kt-portlet__head d-block p-0">
					<div class="kt-portlet__head-label">
						<h3 class="kt-portlet__head-title pb-2">
							Homework
						</h3>
					</div>
					<div class="form-group mb-2">
						<label>Title</label>
						<input class="form-control" readonly value="{{ $homework->title }}">
					</div>
					<div class="form-group">
						<label>Question</label>
						<textarea id="title" readonly>{{ $homework->question }}</textarea>
					</div>
				</div>
				<div class="tab-content  px-0">
					<div class="tab-pane active mobile" id="attachments-tab" role="tabpanel">
						@if (!$attachments->isEmpty())
							<div class="kt-portlet kt-portlet--height-fluid mb-0">
								<div class="kt-portlet__head p-0">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">
											Download attachments
										</h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<a href="/my-homeworks/download-all/{{ $homework->id }}" class="btn btn-label-brand btn-bold btn-sm" data-toggle="">
											Download all
										</a>
										
									</div>
								</div>
								<div class="kt-portlet__body px-0">
			
									<!--begin::k-widget4-->
									<div class="kt-widget4">
										@foreach ($attachments as $k => $a)
			
			
											<div class="kt-widget4__item">
												<div class="kt-widget4__pic kt-widget4__pic--icon">
													<img src="{{ $a['location'] }}">
												</div>
												<a href="/my-homeworks/download/{{ $a['id'] }}" target="_blank" class="kt-widget4__title">
													{{ $a['serverName'] }}
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
						<div class="kt-portlet kt-portlet--height-fluid mb-0">
							<div class="kt-portlet__head p-0">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
										Attachments
									</h3>
								</div>
							</div>
							<div class="kt-portlet__body p-0 ">
							<p> N/A </p>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
		<div class="kt-portlet__body">
			<div class="kt-portlet__head d-block p-0">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title pb-2">
						Submission
					</h3>
				</div>
				<textarea id="submission" readonly>{{ $homeworkUser->answer }}</textarea>
			</div>
			<div class="tab-content py-3 px-0">
				<div class="tab-pane active mobile" id="attachments-tab" role="tabpanel">
					@if (!$attachmentsAnswer->isEmpty())
						<div class="kt-portlet kt-portlet--height-fluid my-0">
							<div class="kt-portlet__head p-0">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
										Download attachments
									</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
									<a href="/homework-submissions/download-all/{{ $homeworkUser->id }}" class="btn btn-label-brand btn-bold btn-sm" data-toggle="">
										Download all
									</a>
									
								</div>
							</div>
							<div class="kt-portlet__body px-0">
		
								<!--begin::k-widget4-->
								<div class="kt-widget4">
									@foreach ($attachmentsAnswer as $k => $a)
		
		
										<div class="kt-widget4__item">
											<div class="kt-widget4__pic kt-widget4__pic--icon">
												<img src="{{ $a['location'] }}">
											</div>
											<a href="/homework-submissions/download/{{ $a['id'] }}" target="_blank" class="kt-widget4__title">
												{{ $a['serverName'] }}
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
					<div class="kt-portlet kt-portlet--height-fluid my-0">
						<div class="kt-portlet__head p-0">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									Attachments
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body px-0">
						<p> N/A </p>
						</div>
					</div>
					@endif
				</div>
		    </div>
		
        </div>
        <div class="kt-portlet__body py-0">
            <form action="/homework-submissions/{{ $homeworkUser->id  }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Mark <span class="text-warning">(Total: {{ $homeworkUser['homework']['full_marks'] }})</span></label>
                    <input type="number" name="obtained_marks" min="0" max="{{ $homeworkUser['homework']['full_marks'] }}" value="{{ $homeworkUser->obtained_marks }}" class="form-control">
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button  type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/homework-submissions/{{ $homeworkUser->homework_id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
		<!--end:: Widgets/Blog-->
	</div>
</div>	
@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
	// $(document).ready(function(){

        
	CKEDITOR.replace( 'title' ,{ removeButtons: 'Table' } );
	CKEDITOR.replace( 'submission' ,{ removeButtons: 'Table' } );
            
            
    // })
</script>
@endpush