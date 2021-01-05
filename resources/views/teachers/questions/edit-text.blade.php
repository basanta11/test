@extends('layouts.main')
@section('title','Exams | '. config("app.name"))
@section('exams','kt-menu__item--open')
@section('exams','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
    'breadTitle' => __('Exams'),
    'crumbs' => [
        [
            'name' => __('Exams'),
            'url' => '/exam-teachers'
        ],
        [
            'name' => __('View Exam'),
            'url' => '/exam-teachers/'.$question->set->exam_id
        ],
        [
            'name' => __('View Set'),
            'url' => '/sets/'.$question->set_id
        ],
        [
            'name' => __('Edit Question'),
            'url' => url()->current()
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
            @if($question->type==0)
            <form class="kt-form" method="POST" action="/questions/{{ $question->id }}/pdf">
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
                        <a href="/sets/{{ $question->set_id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
            @else

            
            <form class="kt-form" method="POST" action="/questions/{{ $question->id }}">
                @csrf
                @method('PATCH')

                <input type="hidden" name="edit_type" value="text">
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
                                                    <input type="text" name="oldoption[{{$option->id}}][option]" maxlength="150" class="form-control" value="{{ $option->title }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="ht-form__group--inline">
                                                <div class="kt-form__control">
                                                    <input type="checkbox" title="Correct Answer?" class="form-control c-text" name="oldoption[{{$option->id}}][is_correct]" {{ $option->is_correct == 1 ? 'checked' : '' }}>
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
                                                <input type="text" id="test" name="option" maxlength="150" class="form-control repeater-input" placeholder="Enter new option...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="ht-form__group--inline">
                                            <div class="kt-form__control">
                                                <input type="checkbox" title="Correct Answer?" class="form-control c-text" name="correct_answer_text">
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
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/sets/{{ $question->set_id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
        @endif
     
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );

    $('.c-text').on('change', function() {
        let checked = $(this);
        console.log($(this).closest('div.col-md-1'));
        let checkedInput = $(this).closest('div.col-md-1').siblings('div.col-md-9').find('input.repeater-input');
        
        if(checked.is(':checked')) {
            $('.c-text').prop('checked', false);
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

            $('.c-text').on('change', function() {
                let checked = $(this);
                if(checked.is(':checked')) {
                    $('.c-text').prop('checked', false);
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

    $('.del-btn').on('click', function() {
        const parent = $(this).attr('data-parent');
        const optionId = $('#'+parent).attr('data-id');

        $('#option_del_modal_submit').attr('data-option-id', optionId);
    });

    $('#option_del_modal_submit').on('click', function() {
        const optionId = $(this).attr('data-option-id');

        $.ajax({
            url: '/api/options/' + optionId,
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
        const url = "@php echo url('/api/question/get-mark/'.$question->set_id) @endphp";
        
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
            var set_id={{ $question->set_id }};
            $.ajax({
                url:'/api/question/order-exists/',
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
            var set_id={{ $question->set_id }};
            $.ajax({
                url:'/api/question/mark-valid/',
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