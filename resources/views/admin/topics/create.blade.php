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
                'name' => 'Create Topic',
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
					{{ __("Create Topic") }}
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/topics" enctype="multipart/form-data">
                @csrf
                <input hidden value="{{ $lesson->id }}" name="lesson_id">
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-md m-0 mb-2"></div>
                    <div class="mb-2 ">
                        <h5>
                            {{ __("Resources") }}
                            <small class="text-muted font-italic text-small"><abbr title="video, image, audio or text">({{ __("at least one of the four is required") }})</abbr></small>
                        </h5>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Video") }} &nbsp;&nbsp;</label>
                        <input value="0" data-switch="true" type="checkbox" name="video_type" checked="checked" data-on-color="success" data-off-color="warning" id="video_type">
                        <div class="mt-3">
                            <div id="video_url">
                                <input type="text" name="video_url" class="form-control" placeholder="Enter video url...">
                            </div>
                            <div id="video_file">
                                <div class="custom-file">
                                    <input data-type="video" accept="video/*" type="file" name="videoinput" class="custom-file-input custom-file-rem" id="customFile">
                                    <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                                </div>
                                <div class="progress mt-3" style="display: none">
                                    <input type="hidden" name="video">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Audio") }}</label>
                        <div class="custom-file">
                            <input data-type="audio" accept="audio/*" type="file" name="audioinput" class="custom-file-input custom-file-rem" id="customFileAudio">
                            <label class="custom-file-label" for="customFileAudio">{{ __("Choose file") }}</label>
                        </div>
                        <div class="progress mt-3" style="display: none">
                            <input type="hidden" name="audio">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Image") }}</label>
                        <div class="custom-file">
                            <input data-type="image" accept="image/*" type="file" name="imageinput" class="custom-file-input custom-file-rem" id="customFileImage">
                            <label class="custom-file-label" for="customFileImage">{{ __("Choose file") }}</label>
                        </div>
                        <div class="progress mt-3" style="display: none">
                            <input type="hidden" name="image">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label>{{ __("Text") }}</label>
                        <div class="summernote" id="topic_text_div"></div>
                        <input type="hidden" id="hidden_topic_text" name="text">
                    </div> --}}

                    <div class="form-group">
                        <label>Text</label>
                        <textarea class="form-control" id="editor1" name="text"></textarea>
                    </div>

                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-md m-0"></div>
                    <div id="kt_repeater_1" class="mb-4">
                        <div class="form-group form-group-last row">
                            <label class="col-lg-12 col-form-label">{{ __("Reference Links") }}:</label>
                            <div data-repeater-list="references" class="col-lg-12">
                                
                            <div data-repeater-item="" class="form-group row align-items-center" style="">
                                    <div class="col-md-10">
                                        <div class="kt-form__group--inline">
                                            
                                            <div class="kt-form__control">
                                                <input type="text" name="references" maxlength="150" class="form-control" placeholder="Enter reference link..." >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:;" data-repeater-delete="" class="w-100 btn-sm btn btn-label-danger btn-bold">
                                            <i class="la la-trash-o"></i>
                                            {{ __("Delete") }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-last row">
                            {{-- <label class="col-lg-2 col-form-label"></label> --}}
                            <div class="col-lg-4">
                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                    <i class="la la-plus"></i> {{ __("Add") }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div id="kt_repeater_2">
                        <div class="form-group form-group-last row">
                            <label class="col-lg-12 col-form-label">{{ __("Attachments") }}:</label>
                            <div data-repeater-list="attachments" class="col-lg-12">
                                
                            <div data-repeater-item="" class="form-group row align-items-center" style="">
                                    <div class="col-md-3">
                                        <div class="kt-form__group--inline">
                                            
                                            <div class="kt-form__control">
                                                <input type="text" name="attachment_title" maxlength="150" class="form-control" placeholder="{{ __("Enter title of attachment") }}..." >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="kt-form__group--inline">
                                            
                                            <div class="kt-form__control">
                                                <div class="custom-file">
                                                    <input data-type="attachment" type="file" name="attachments" accept="image/*,video/*,audio/*,.doc,.docx,application/pdf,application/vnd.ms-excel" class="custom-file-input input-attach">
                                                    <label class="custom-file-label">{{ __("Choose file") }}</label>
                                                </div>
                                                <div class="progress mt-3" style="display: none">
                                                    <input type="hidden" name="attachment_filename">
                                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-md-none kt-margin-b-10"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:;" data-repeater-delete="" class="w-100 btn-sm btn btn-label-danger btn-bold">
                                            <i class="la la-trash-o"></i>
                                            {{ __("Delete") }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-last row">
                            {{-- <label class="col-lg-2 col-form-label"></label> --}}
                            <div class="col-lg-4">
                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                    <i class="la la-plus"></i> {{ __("Add") }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button id="submit-btn" type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/lessons/{{ $lesson->id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ global_asset('assets/js/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script src="{{ global_asset('assets/plugins/custom/uppy/uppy.bundle.js') }}" type="text/javascript"></script>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
    $(document).ready(function() {
        // $('.summernote').summernote({
        //     height: 150,
        //     toolbar: [
        //         ['style', ['style']],
        //         ['font', ['bold', 'underline', 'clear']],
        //         ['color', ['color']],
        //         ['fontname', ['fontname']],
        //         ['para', ['ul', 'ol', 'paragraph']],
        //         ['table', ['table']],
        //         ['insert', ['link', 'picture']],
        //         ['view', ['fullscreen', 'codeview']],
        //     ],
        //     fontNames: [ 'Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Kanit', 'Poppins'],
        //     fontNamesIgnoreCheck: [ 'Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Kanit', 'Poppins'],
        // });

        CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );

        $('input[name="videoinput"], input[name="video_url"] ,input[name="audioinput"], input[name="imageinput"], textarea[name="text"]').on('change keydown',function(e){
            remErrCustom([
                $('input[name="videoinput"]'), 
                $('input[name="video_url"]'), 
                $('input[name="audioinput"]'),
                $('input[name="imageinput"]'),
                $('textarea[name="text"]')
            ]);
        })
        $('.kt-form').on('submit',function(e){
            // let topicText = $('#topic_text_div').summernote('code');

            // if( topicText !== "<p><br></p>" ) {
            //     $('#hidden_topic_text').val(topicText);
            // }

            if( ($('input[name="videoinput"]').val()=="" && $('input[name="video_url"]').val()=="")
            && ($('input[name="audioinput"]').val()=="") 
            && ($('input[name="imageinput"]').val()=="") 
            && (CKEDITOR.instances.editor1.getData()=="") 
            ){
                makeErrCustom([
                    $('input[name="videoinput"]'), 
                    $('input[name="video_url"]'), 
                    $('input[name="audioinput"]'),
                    $('input[name="imageinput"]'),
                    $('textarea[name="text"]')
                ],'At least one of the, video, audio, image or text has to be filled.');
                // toastr.error('At least one of the, video, audio, image or text has to be filled.')
                return false;
            }
            var form=$(this);
        
            form.validate({
                focusInvalid: true,
                rules: {
                    title: {
                        required: true,
                        maxlength:150,
                    },
                    brief: {
                        required: true,
                        maxlength:500,
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
        $('#kt_repeater_1,#kt_repeater_2').repeater({
            initEmpty: false,
            isFirstItemUndeletable: true,
            defaultValues: {
                'text-input': 'foo',
            },
                
            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                removeFile ($(this).find('input[type="file"]').attr('data-type'), $(this).find('input[type="hidden"]').val() );
                $(this).slideUp(deleteElement);
            }   
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
                // console.log($('#video_type').val());
            }
        });

        function files(){
            $('#video_type').val(0)
            $('#video_file').show(300)
            $('#video_url').hide(300)

            // $('input[name="videoinput"]').attr('required','required').removeAttr('disabled')
            // $('input[name="video_url"]').removeAttr('required').attr('disabled','disabled')
            
        }

        function url(){
            $('#video_type').val(1)
            $('#video_file').hide(300)
            $('#video_url').show(300)

            // $('input[name="video_url"]').attr('required','required').removeAttr('disabled')
            // $('input[name="videoinput"]').removeAttr('required','disabled').attr('disabled','disabled')

        }

        function removeFile(ty, fn) {
            let folder;

            switch (ty) {
                case 'video':
                    folder = 'videos';
                    break;

                case 'audio':
                    folder = 'audios';
                    break;

                case 'image':
                    folder = 'images';
                    break;
            
                default:
                    folder = 'attachments';
                    break;
            }

            $.ajax({
                url: '/topics/removeFile/'+folder+'/'+fn,
                type: 'GET'
            });

            return true;
        }

        $(document).on('change','.input-attach',function(e){
            var fileName = $(this).val();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        })

        $(document).on('change','.custom-file-rem',function(e){

            var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>{{ __("Remove") }}</button>`
            var elem=$(this);
            if(elem.parent().parent().find('.custom-remove-button').length==0){
                elem.parent().parent().append(html);
            }
        })
        $(document).on('click','.custom-remove-button',function(e){
            var elem=$(this);
            var customFile = elem.parent().find('.custom-file');
            var hiddenFilename = customFile.siblings('div.progress').find('input');
            var selectedInput = customFile.find('input');

            removeFile( selectedInput.attr('data-type'), hiddenFilename.val() );
            selectedInput.val('');
            customFile.find('label').html('{{ __("Choose file") }}');
            hiddenFilename.val('');
            customFile.siblings('div.progress').hide();
            $(this).remove();
        });

        $(document).on('change', 'input[type="file"]', function() {
            if($(this)[0].files.length === 1) {

                var type = $(this).attr('data-type');
                var formData = new FormData();
                var input = $(this)[0].files[0];

                formData.append('selectedFile', input);
                formData.append('type', type);
                const url = "@php echo url('/topics/storeFile') @endphp";

                var progressDiv = $(this).parent().siblings('div.progress');

                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                progressDiv.find('.progress-bar').width(percentComplete + '%');
                                progressDiv.find('.progress-bar').html(percentComplete+'%');
                            }
                        }, false);
                        return xhr;
                    },
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        progressDiv.find('.progress-bar').width('0%');
                        progressDiv.find('.progress-bar').html('0%');
                        progressDiv.show();
                    },
                    success: function(res) {
                        if(res.result == 'success'){
                            progressDiv.find('input').val(res.filename);
                        }
                        else {
                            toastr.error('Failed to upload. Please try again.')
                        }
                    }
                });
            }
        });

        $(document).ajaxStart(function() {
            $('#submit-btn').prop('disabled', true);
        }).ajaxStop(function() {
            $('#submit-btn').prop('disabled', false);
        });
    });
</script>
@endpush