@extends('layouts.main')
@section('title','Topics | '. config("app.name"))
@if ( !auth()->user()->hasRole('Teacher') )
    @section('courses','kt-menu__item--open')
@else
    @section('assigned-courses','kt-menu__item--open')
@endif
@push('styles')

@endpush
@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Topics'),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? 'Courses' : 'Assigned Courses',
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses/'.$lesson['course']['id'] : '/assigned-courses/'.$lesson['course']['id'],
            ],
            [
                'name' => $lesson->title,
                'url' => '/lessons/'.$lesson->id,
            ],
            [
                'name' => $topic->title,
                'url' => '/topics/'.$topic->id,
            ],
            [
                'name' => __('Create Topic Attachment'),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				{{-- <span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-line-plus"></i>
				</span> --}}
				<h3 class="kt-portlet__head-title">
					{{ __("Create Topic Attachment") }}
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/topics/attachments" enctype="multipart/form-data">
                @csrf
                <input hidden value="{{ $topic->id }}" name="topic_id">
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Attachments") }}</label>
                        <div class="kt-form__control">
                            <div class="custom-file">
                                
                                <input data-type="attachment" type="file" name="attachment" accept="image/*,video/*,audio/*,.doc,.docx,application/pdf,application/vnd.ms-excel" class="custom-file-input input-attach">
                                <label class="custom-file-label">{{ __("Choose file") }}</label>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/topics/{{ $topic->id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')

<script>
$('.kt-form').on('submit',function(e){
            KTApp.blockPage();
           var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
                title: {
                    required: true,
                    maxlength:150,
                },
                attachments: {
                    required: true,
                },
            }
        });
        
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return;
        }

        return true;

    })
    
    $(document).on('change','.input-attach',function(e){
        $(this).next('.custom-file-label').addClass("selected").html("")
        var fileName = $(this).val();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    })

    $(document).on('change','.custom-file-rem',function(e){

        var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>Remove</button>`
        var elem=$(this);
        if(elem.parent().parent().find('.custom-remove-button').length==0){
            elem.parent().parent().append(html);
        }
    })
    $(document).on('click','.custom-remove-button',function(e){
        var elem=$(this)
        elem.parent().find('.custom-file').find('input').val('');
        elem.parent().find('.custom-file').find('label').html('Choose file');
        $(this).remove();
    });

    
</script>
@endpush