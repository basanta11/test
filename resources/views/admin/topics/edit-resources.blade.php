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
                'name' => __('Edit Topic Resource'),
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
					{{ __("Edit Topic Resource") }} ({{ ucfirst($type) }})
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/topics/resources/{{ $topic->id }}/update" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input hidden value="{{ $type }}" name="type">

                @if($type=='video')
                <div class="form-group">
                    <label>{{ __("Video") }} &nbsp;&nbsp;</label>
                    <input value="0" data-switch="true" type="checkbox" name="video_type" checked="checked" data-on-color="success" data-off-color="warning" id="video_type">
                    <div class="mt-3">
                        <div id="video_url">
                            <input type="text" name="video_url" value="{{ $topic->video_url }}" class="form-control" placeholder="Enter video url...">
                            
                        </div>
                        <div id="video_file">
                            <div class="custom-file">
                                <input accept="video/*" type="file" name="video" class="custom-file-input custom-file-rem" id="customFile">
                                <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                            </div>
                        </div>
                        
                    </div>
                    @if($topic->video || $topic->video_url)
                    <div class="video-container mt-4 old-file" style="width: 100%;
                        padding-top: 300px;
                         object-fit:contain;
                        position: relative;">
                        @if ($videoData['videoType'] == 'video')
                            <video id="player" class="video" playsinline controls data-poster="/path/to/poster.jpg">
                                <source src="{{ $videoData['video'] }}" type="video/mp4" />

                                <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
                            </video>
                            @else
                            
                            <iframe class="video" src="{{ $videoData['video'] }}" frameborder="0" allowfullscreen></iframe>
                        @endif
                        </div>
                    @endif
                </div>
                @elseif($type=='audio')

                <div class="form-group">
                    <label>{{ __("Audio") }}</label>
                    <div class="custom-file">
                        <input accept="audio/*" type="file" name="audio" required class="custom-file-input custom-file-rem" id="customFileAudio">
                        <label class="custom-file-label" for="customFileAudio">{{ __("Choose file") }}</label>
                    </div>
                </div>
                @if (!empty($topic->audio))
                    <div class="old-file" >
                        <audio class="w-100" controls src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/audios/' . $topic->audio) }}">Your browser does not support the
                        <code>audio</code> element.</audio>
                    </div>
                @endif

                @elseif($type=='image')

                <div class="form-group">
                    <label>{{ __("Image") }}</label>
                    <div class="custom-file">
                        <input accept="image/*" type="file" name="image" required class="custom-file-input custom-file-rem" id="customFileImage">
                        <label class="custom-file-label" for="customFileImage">{{ __("Choose file") }}</label>
                    </div>
                </div>
                @if (!empty($topic->image))
                    <div class="old-file">
                        <div class="image-container">
                            <a href="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/images/' . $topic->image) }}" target="_blank">
                                <img style="width:100%; height: 300px; object-fit:cover;" src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/images/' . $topic->image) }}">
                            </a>
                        </div>
                    </div>
                @endif

                @elseif($type=='text')
                
                <div class="form-group">
                    <label>{{ __("Text") }}</label>
                    {{-- <div class="summernote" id="topic_text_div"></div>
                    <input type="hidden" id="hidden_topic_text" name="text"> --}}
                    <textarea class="form-control" id="editor1" name="text">{{ $topic->text }}</textarea>
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
@endsection
@push('scripts')
<script src="{{ global_asset('assets/js/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
    // var getText = `{!! $topic->text !!}`;

    // $('.summernote').summernote({
    //     toolbar: [
    //         ['style', ['style']],
    //         ['font', ['bold', 'underline', 'clear']],
    //         ['fontname', ['fontname']],
    //         ['color', ['color']],
    //         ['para', ['ul', 'ol', 'paragraph']],
    //         ['table', ['table']],
    //         ['insert', ['link', 'picture']],
    //         ['view', ['fullscreen', 'codeview']],
    //     ],
    // });

    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );
    
    // $('#topic_text_div').summernote('code', getText);

    $(document).on('change','.custom-file-rem',function(e){

        var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>Remove</button>`
        var elem=$(this);
        $('.old-file').hide(200);
        if(elem.parent().parent().find('.custom-remove-button').length==0){
            elem.parent().parent().append(html);
        }
    })
    $(document).on('click','.custom-remove-button',function(e){
        var elem=$(this)

        $('.old-file').show(200);
        elem.parent().find('.custom-file').find('input').val('');
        elem.parent().find('.custom-file').find('label').html('{{ __("Choose file") }}');
        $(this).remove();
    });
    $("[name='video_type']").bootstrapSwitch({
        onText: 'Url',
        offText: 'File',
        state: {{ $topic->video==null ? 'true' : 'false' }},
        onInit:function(ev){
            {{ $topic->video==null ? 'url()' : 'files()' }};
        },
        onSwitchChange: function(event) {

            if($('#video_type').val()==0){
                url()
            }else{
                files()
            }
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

        // let topicText = $('#topic_text_div').summernote('code');
        // $('#hidden_topic_text').val(topicText);

        return true;

    })
    
</script>
@endpush