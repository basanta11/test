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
<div class="modal fade" id="option_del_modal" tabindex="-1" role="dialog" aria-labelledby="option_del_modal_header" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="option_del_modal_header">Delete Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this option?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="option_del_modal_submit" type="button" data-option-id="" class="btn btn-primary">Proceed</button>
            </div>
        </div>
    </div>
</div>
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
            <form class="kt-form" method="POST" action="/test-questions/{{ $question->id }}" enctype="multipart/form-data">
            
                @csrf
                @method('PATCH')

                <input type="hidden" name="edit_type" value="image">
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Question") }}</label>
                        <textarea class="form-control" id="editor1" name="question">{{ $question->title }}</textarea>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __("Order") }}</label>
                            <input type="number" name="order" class="form-control" value="{{ $question->order }}" min="1">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __("Marks") }}</label>
                            <input type="number" name="marks" class="form-control" value="{{ $question->marks }}" min="1">
                        </div>
                    </div>

                    <div id="kt_repeater_1" class="mb-4">
                        <div class="form-group form-group-last row">
                            <label class="col-lg-12 col-form-label">{{ __("Options") }}:</label>
                            @foreach($options as $key=>$option)
                                <div class="col-lg-12" id="option-div-{{ $option->id }}" data-id="{{ $option->id }}">
                                    <div class="form-group row align-items-center">
                                        <div class="col-md-9">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__control">
                                                    <img class="img-thumbnail" data-id="{{ $option->id }}" src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/question_options/'.$option->title) }}">
                                                    <input type="hidden" name="oldoption[{{$option->id}}][title]" value="{{ $option->title }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="ht-form__group--inline">
                                                <div class="kt-form__control">
                                                    <input type="checkbox" title="Correct Answer?" class="form-control c-image" name="oldoption[{{$option->id}}][is_correct]" {{ $option->is_correct == 1 ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        @if($key!=0)
                                        <div class="col-md-2">
                                            <a href="javascript:;" class="w-100 btn-sm btn btn-label-danger btn-bold del-btn" data-parent="option-div-{{ $option->id }}" data-toggle="modal" data-target="#option_del_modal">
                                                <i class="la la-trash-o"></i>
                                                {{ __("Delete") }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <div data-repeater-list="options" class="col-lg-12">
                                <div data-repeater-item="" class="form-group row align-items-center" style="">
                                    <div class="col-md-9">
                                        <div class="kt-form__group--inline">
                                            <div class="kt-form__control">
                                                <div class="custom-file">
                                                    <input data-type="question_options" accept="image/*" type="file" name="option" class="custom-file-input custom-file-rem repeater-input" id="customFileImage">
                                                    <label class="custom-file-label" for="customFileImage">{{ __("Choose file") }}</label>
                                                </div>
                                                <div class="progress mt-3" style="display: none">
                                                    <input type="hidden" name="option_file_name">
                                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="ht-form__group--inline">
                                            <div class="kt-form__control">
                                                <input type="checkbox" title="Correct Answer?" class="form-control c-image" name="correct_answer_image">
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
                        <button type="submit" id="submit-btn" class="btn btn-primary">{{ __("Submit") }}</button>
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
    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );

    $('.c-image').on('change', function() {
        let checked = $(this);
        console.log($(this).closest('div.col-md-1'));
        let checkedInput = $(this).closest('div.col-md-1').siblings('div.col-md-9').find('input.repeater-input');
        
        if(checked.is(':checked')) {
            $('.c-image').prop('checked', false);
            checked.prop('checked', true);
            checkedInput.attr('required', 'true');
        }
        else {
            checkedInput.removeAttr('required');
        }
    });

    $('#kt_repeater_1').repeater({
        initEmpty: false,
        // isFirstItemUndeletable: true,
        defaultValues: {
            'text-input': 'foo',
        },
        show: function () {
            $(this).slideDown();

            $('.c-image').on('change', function() {
                let checked = $(this);
                if(checked.is(':checked')) {
                    $('.c-image').prop('checked', false);
                    checked.prop('checked', true);
                    checked.parents('div').find('.select-div').siblings('div.col-md-9').find('input.repeater-input').attr('required', 'true');
                }
                else {
                    checked.parents('div').find('.select-div').siblings('div.col-md-9').find('input.repeater-input').removeAttr('required');
                }
            });
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }   
    });

    function removeFile(ty, fn) {
        let folder = 'question_options';
        $.ajax({
            url: '/topics/removeFile/'+folder+'/'+fn,
            type: 'GET'
        });

        return true;
    }

    $(document).on('change','.custom-file-rem',function(e){
        var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>{{ __("Remove") }}</button>`
        var elem=$(this);
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
                    $('#submit-btn').prop('disabled', true);
                },
                success: function(res) {
                    if(res.result == 'success'){
                        progressDiv.find('input').val(res.filename);
                        $('#submit-btn').prop('disabled', false);
                    }
                    else {
                        toastr.error('Failed to upload. Please try again.');
                        $('#submit-btn').prop('disabled', false);
                    }
                }
            });
        }
    });

    $('.del-btn').on('click', function() {
        const parent = $(this).attr('data-parent');
        const optionId = $('#'+parent).attr('data-id');

        $('#option_del_modal_submit').attr('data-option-id', optionId);
    });

    $('#option_del_modal_submit').on('click', function() {
        const optionId = $(this).attr('data-option-id');

        $.ajax({
            url: '/api/tests/options/' + optionId,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('#keen-spinner').show();
                $('#main-body-div').css({ opacity: 0.5 });
            },
            success: function(res) {
                $('#option-div-'+optionId).remove();
                $('#option_del_modal').modal('hide');
                $('#keen-spinner').hide();
                $('#main-body-div').css({ opacity: 1 });
            }
        });
    });

    $('.kt-form').on('submit',function(e){
        var form=$(this);

        KTApp.blockPage();
        
        $.validator.addMethod("greaterThan",
            function (value, element, param) {
                var $otherElement = $(param);
                return parseInt(value, 10) > parseInt($otherElement.val(), 10);
            });
        
        form.validate({
            focusInvalid: true,
            rules: {
                title: {
                    required: true
                },
                full_marks: {
                    required: true,
                    number: true,
                    min: 1,
                    greaterThan: "#pmarks"
                },
                pass_marks: {
                    required: true,
                    number: true,
                    min: 1
                },
                exam_start: {
                    required: true
                },
                duration: {
                    required: true,
                    digits: true
                },
                type: {
                    required: true
                },
                classroom_id: {
                    required: true
                },
                sections: {
                    required: true
                },
            }
        });
        
        if (!form.valid()) {
            e.preventDefault();
KTApp.unblockPage();
            return false;
        }

        return true;

    })
    const question_id={{ $question->id }};
    
    if($('input[name="marks"]').length>0){
        const url = "@php echo url('/api/test-questions/get-mark/'.$question->test_set_id) @endphp";
        
        $.ajax({
            url:url,
            dataType:'JSON',
            type:'GET',
            data:{
                question_id:question_id,
            },
            success:function(data){
                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
            }
        })
    }
     // order check
     $(document).on('focus','input[name="order"]',function(e){
            var elem=$(this)
            remErrCustom(elem)
        })
        $(document).on('focusout','input[name="order"]',function(e){
            var elem=$(this);
            var order=elem.val();
            var set_id={{ $question->test_set_id }};
            $.ajax({
                url:'/api/test-questions/order-exists/',
                dataType:'JSON',
                type:'GET',
                data:{
                    order:order,
                    set_id:set_id,
                    question_id:question_id,
                },
                success:function(data){
                    if(data.status){
                        makeErrCustom(elem,'Order already taken')
                    }
                }
            })
            
        })

        // marking 
        $(document).on('focus','input[name="marks"]',function(e){
            var elem=$(this)
            remErrCustom(elem)
        })
        $(document).on('focusout','input[name="marks"]',function(e){
            var elem=$(this);
            var marks=elem.val();
            var set_id={{ $question->test_set_id }};
            $.ajax({
                url:'/api/test-questions/mark-valid/',
                dataType:'JSON',
                type:'GET',
                data:{
                    marks:marks,
                    set_id:set_id,
                    question_id:question_id,
                },
                success:function(data){
                    if(data.status){
                        makeErrCustom(elem,'Marks given is greater than remaining marks for this set.');
                    }
                }
            })
            
        })
</script>
@endpush