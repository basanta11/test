@extends('layouts.main')
@section('title','Tests | '. config("app.name"))
@section('assigned-courses','kt-menu__item--open')
@section('assigned-courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Tests"),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' => '/assigned-courses/'.$lesson['course']['id'],
			],
			
            [
                'name' => $lesson['title'],
                'url' => '/lessons/'.$lesson['id'],
            ],
            [
                'name' => 'Tests',
                'url' => '/tests/'.$lesson['id'],
            ],

            [
                'name' => $test->title,
                'url' => '/tests/'.$test->id.'/view',
            ],

            [
                'name' => $testset->title,
                'url' => '/test-sets/'.$testset->id,
            ],

            [
                'name' => __('Edit'),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Edit Question") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/test-questions/{{ $question->id }}/pdf">
                @csrf
                @method('PATCH')
                <div class="kt-form__control">
                    <label>Pdf file:</label>
                    <div class="custom-file">
                        <input data-type="attachments" accept="application/pdf" type="file" name="pdf" class="custom-file-input custom-file-rem" id="customFileImage">
                        <label class="custom-file-label" for="customFileImage">{{ __("Choose file") }}</label>
                    </div>
                    <div class="progress mt-3" style="display: none">
                        <input type="hidden" name="option_file_name">
                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/test-sets/{{ $question->test_set_id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
           
        </div>
       
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
    $('#upload-pdf-form').on('submit', function(e) {
        e.preventDefault();

        KTApp.blockPage();
        var form=$(this);

        form.validate({
            focusInvalid: true,
            rules: {
                pdf: {
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
    
    function removeFile(ty, fn) {
        $.ajax({
            url: '/test-questions/removeFile/'+ty+'/'+fn,
            type: 'GET'
        });

        return true;
    }

    $(document).on('change','.custom-file-rem',function(e){

        var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>{{ __("Remove") }}</button>`
        var elem=$(this);
        if($(this).parent().parent().find('.progress input').val()){
            removeFile(
                $(this).parent().parent().find('.custom-file input').data('type'),
                $(this).parent().parent().find('.progress input').val()
            )
        }
        if(elem.parent().parent().find('.custom-remove-button').length==0){
            elem.parent().parent().append(html);
        }
    });

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
            const url = "@php echo url('/test-questions/storeFile') @endphp";

            var progressDiv = $(this).parent().siblings('div.progress');
            console.log(input, formData, type)
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
                data:formData,
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
</script>
@endpush