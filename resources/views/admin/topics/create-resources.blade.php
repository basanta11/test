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
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
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
                'name' => 'Create Topic Resource',
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
					{{ __("Create Topic Resource") }} ({{ ucfirst($type) }})
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/topics/resources/{{ $topic->id }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input hidden value="{{ $type }}" name="type">

                @if($type=='video')
                <div class="form-group">
                    <label>{{ __("Video") }} &nbsp;&nbsp;</label>
                    <input value="0" data-switch="true" type="checkbox" name="video_type" checked="checked" data-on-color="success" data-off-color="warning" id="video_type">
                    <div class="mt-3">
                        <div id="video_url">
                            <input type="text" name="video_url" class="form-control" placeholder="Enter video url...">
                        </div>
                        <div id="video_file">
                            <div class="custom-file">
                                <input accept="video/*" type="file" name="video" class="custom-file-input custom-file-rem" id="customFile">
                                <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                
                @elseif($type=='audio')

                <div class="form-group">
                    <label>{{ __("Audio") }}</label>
                    <div class="custom-file">
                        <input accept="audio/*" type="file" name="audio" class="custom-file-input custom-file-rem" id="customFileAudio" required>
                        <label class="custom-file-label" for="customFileAudio">{{ __("Choose file") }}</label>
                    </div>
                </div>

                @elseif($type=='image')

                <div class="form-group">
                    <label>{{ __("Image") }}</label>
                    <div class="custom-file">
                        <input accept="image/*" type="file" name="image" class="custom-file-input custom-file-rem" id="customFileImage" required>
                        <label class="custom-file-label" for="customFileImage">{{ __("Choose file") }}</label>
                    </div>
                </div>

                @elseif($type=='text')
                
                <div class="form-group">
                    <label>{{ __("Text") }}</label>
                    <textarea class="form-control" name="text" id="editor1" required></textarea>
                </div>

                @else

                @endif
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

<script src="{{ global_asset('assets/js/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );

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
                    
    $("[name='video_type']").bootstrapSwitch({
        onText: 'Url',
        offText: 'File',
        state: true,
        onInit:function(ev){
            url()
        },
        onSwitchChange: function(event) {

            if($('#video_type').val()==0){
                url()
            }else{
                files()
            }
            console.log($('#video_type').val());
        }
    });

    function files(){
        $('#video_type').val(0)
        $('#video_file').show(300)
        $('#video_url').hide(300)

        $('input[name="video"]').attr('required','required').removeAttr('disabled')
        $('input[name="video_url"]').removeAttr('required').attr('disabled','disabled')
        
    }
    function url(){
        $('#video_type').val(1)
        $('#video_file').hide(300)
        $('#video_url').show(300)

        $('input[name="video_url"]').attr('required','required').removeAttr('disabled')
        $('input[name="video"]').removeAttr('required','disabled').attr('disabled','disabled')

    }
    $('.kt-form').on('submit',function(e){
        KTApp.blockPage();
        var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
            }
        });
        
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return;
        }

        return true;

    })
    
</script>
@endpush