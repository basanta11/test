@extends('layouts.main')
@section('title','Exams | '. config("app.name"))
@section('exams','kt-menu__item--open')

@section('content')

<div class="modal fade" id="finishExam" tabindex="-1" role="dialog" aria-labelledby="finishExamLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="finishExamLabel">Finish Exam?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to finish your exam session?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="modal-confirm-btn">Yes</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Exams"),
        'crumbs' => [
            [
                'name' => __("Exams") ,
                'url' => '/exam-students'
            ],
            [
                'name' => __('View Exam'),
                'url' => '/exam-students/'.$exam->id,
            ],
            [
                'name' => __('Start Exam'),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
        <div class="row"> 
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <span class="kt-portlet__head-icon">
                                <i class="kt-font-brand flaticon2-time"></i>
                            </span>
                            <h3 class="kt-portlet__head-title" id="timer">
                                
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-wrapper">
                                <div class="kt-portlet__head-actions">
                                    <span class="badge badge-primary">{{ __("Full Marks") }}: {{ $exam->full_marks }}</span> &nbsp;
                                    <span class="badge badge-danger">{{ __("Pass Marks") }}: {{ $exam->pass_marks }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" id="kt_wizard_v3" data-ktwizard-state="step-first">
                            <div class="kt-grid__item d-none">

                                <!--begin: Form Wizard Nav -->
                                <div class="kt-wizard-v3__nav">

                                    <!--doc: Remove "kt-wizard-v3__nav-items--clickable" class and also set 'clickableSteps: false' in the JS init to disable manually clicking step titles -->
                                    <div class="kt-wizard-v3__nav-items kt-wizard-v3__nav-items--clickable">
                                        @foreach ($questions as $question)
                                            <div class="kt-wizard-v3__nav-item" data-ktwizard-type="step" data-ktwizard-state="@if($loop->first) current @else step @endif">
                                                <div class="kt-wizard-v3__nav-body">
                                                    <div class="kt-wizard-v3__nav-label">
                                                        <span>1</span> Setup Location
                                                    </div>
                                                    <div class="kt-wizard-v3__nav-bar"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!--end: Form Wizard Nav -->
                            </div>
                            <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper" id="pointer-div">

                                <!--begin: Form Wizard Form-->
                                <form class="kt-form" id="kt_form" action="/exam-finish" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="set_id" value="{{ $set->id }}">
                                    @foreach ($questions as $key => $question)
                                        @switch($question->type)
                                            {{-- Upload PDF --}}
                                            @case(0) 
                                                <input type="hidden" name="question_type" value="upload-pdf">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">Download Question <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">

                                                            <div class="form-group text-center">
                                                                <a target="_blank" href="/exam-start/download/{{ $question->id }}/{{ auth()->user()->id }}"><img src="{{ global_asset('assets/media/files/pdf.svg') }}" style="height: 150px; width: 100%;"> <button type="button" class="btn btn-warning align-center mt-3"> <i class="fa fa-download"></i> Download</button></a>
                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Upload Answer: &nbsp; <span class="badge badge-info">only doc or pdf file</span></label>
                                                                <div class="custom-file">
                                                                    <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                                    <input type="file" class="custom-file-input custom-file-rem" id="customFile" name="upload_answer" accept=
                                                                    "application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document"  required>
                                                                    <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break

                                            {{-- Single Choice --}}
                                            @case(1)
                                                <input type="hidden" name="question_type" value="created-question">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">Question {{ $key + 1 }} <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                {!! $question->title !!}
                                                            </div>
                                                            
                                                            <div class="row">
                                                                @if(!$question->question_options->isEmpty())
                                                                    @foreach ($question->question_options as $option)
                                                                        @if($option->type == 0)
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <div class="kt-radio-list">
                                                                                        <label class="kt-radio">
                                                                                            <input 
                                                                                                type="radio" 
                                                                                                name="question-{{ $question->id }}"
                                                                                                value="{{ $option->id }}"
                                                                                                @if( $answers->contains(function($val, $key) use($question, $option) {
                                                                                                    return $val->question_id == $question->id && $val->question_option_id == $option->id;
                                                                                                }) ) 
                                                                                                    {{'checked'}} 
                                                                                                @endif
                                                                                            > {{ $option->title }}   
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>  
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <div class="kt-radio-list">
                                                                                        <label class="kt-radio">
                                                                                            <input 
                                                                                                type="radio" 
                                                                                                name="question-{{ $question->id }}"
                                                                                                value="{{ $option->id }}"
                                                                                                class="custom-image-option"
                                                                                                @if( $answers->contains(function($val, $key) use($question, $option) {
                                                                                                    return $val->question_id == $question->id && $val->question_option_id == $option->id;
                                                                                                }) ) 
                                                                                                    {{'checked'}} 
                                                                                                @endif
                                                                                            >
                                                                                            <img class="option-image img-thumbnail" src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/question_options/'.$option->title) }}" alt="">
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                @break

                                            {{-- Multi Choice --}}
                                            @case(2)
                                                <input type="hidden" name="question_type" value="created-question">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">Question {{ $key + 1 }} <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                {!! $question->title !!}
                                                            </div>

                                                            <div class="row">
                                                                @if(!$question->question_options->isEmpty())
                                                                    @foreach ($question->question_options as $option)
                                                                        @if($option->type == 0)
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <div class="kt-checkbox-list">
                                                                                        <label class="kt-checkbox">
                                                                                            <input 
                                                                                                type="checkbox" 
                                                                                                name="question-{{ $question->id }}[]"
                                                                                                value="{{ $option->id }}"
                                                                                                @if( $answers->contains(function($val, $key) use($question, $option) {
                                                                                                    return $val->question_id == $question->id && $val->question_option_id == $option->id;
                                                                                                }) ) 
                                                                                                    {{'checked'}} 
                                                                                                @endif
                                                                                            > {{ $option->title }}
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>  
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <div class="kt-checkbox-list">
                                                                                        <label class="kt-checkbox">
                                                                                            <input 
                                                                                                type="checkbox" 
                                                                                                name="question-{{ $question->id }}[]"
                                                                                                value="{{ $option->id }}"
                                                                                                class="custom-image-option"
                                                                                                @if( $answers->contains(function($val, $key) use($question, $option) {
                                                                                                    return $val->question_id == $question->id && $val->question_option_id == $option->id;
                                                                                                }) ) 
                                                                                                    {{'checked'}} 
                                                                                                @endif
                                                                                            >
                                                                                            <img class="option-image img-thumbnail" src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/question_options/'.$option->title) }}" alt="">
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                @break

                                            {{-- Upload Image --}}
                                            @case(3)
                                                <input type="hidden" name="question_type" value="created-question">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">Question {{ $key + 1 }} <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                {!! $question->title !!}
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                {!! $question->note !!}
                                                            </div>
                                                            @foreach ($question->attachments as $attachment)
                                                                <div class="form-group">
                                                                    <a target="_blank" href="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/attachments/'.$attachment->body) }}"><img src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/attachments/'.$attachment->body) }}" class="img-fluid"></a>
                                                                </div>
                                                            @endforeach
                                                            <div class="form-group">
                                                                <textarea class="form-control ckeditor" id="editor2" name="question-{{ $question->id }}">
                                                                    @php
                                                                        $ans = $answers->where('question_id', $question->id);
                                                                        if(isset($ans->first()->answer)){
                                                                            echo $ans->first()->answer;
                                                                        }
                                                                    @endphp
                                                                </textarea>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                @break

                                            {{-- Paragraph --}}
                                            @case(4)
                                                <input type="hidden" name="question_type" value="created-question">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">Question {{ $key + 1 }} <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                {!! $question->title !!}
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                {!! $question->note !!}
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea class="form-control ckeditor" id="editor1" name="question-{{ $question->id }}">
                                                                    @php
                                                                        $ans = $answers->where('question_id', $question->id);
                                                                        
                                                                        if ($ans->first()) 
                                                                            echo $ans->first()->answer;
                                                                    @endphp
                                                                </textarea>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                            
                                            {{-- Text --}}
                                            @default
                                                <input type="hidden" name="question_type" value="created-question">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">Question {{ $key + 1 }} <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">
                                                            <div class="form-group">
                                                                <label>Title</label>
                                                                {!! $question->title !!}
                                                            </div>
                                                            <div class="form-group">
                                                                @php $textans = $answers->where('question_id', $question->id); @endphp
                                                                <input 
                                                                    type="text" 
                                                                    class="form-control" 
                                                                    name="question-{{ $question->id }}"
                                                                    @if($textans->first())
                                                                    value="{{ $textans->first()->answer }}"
                                                                    @endif
                                                                >
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                        @endswitch
                                    @endforeach

                                    <!--begin: Form Actions -->
                                    <div class="kt-form__actions">
                                        @if($type == 'Created')
                                        <button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u wizard-btns" data-ktwizard-type="action-prev">
                                            Previous
                                        </button>
                                        @endif
                                        <button id="submit-btn" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u wizard-btns" data-ktwizard-type="action-submit">
                                            Submit
                                        </button>
                                        <button id="next-btn" class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u wizard-btns" data-ktwizard-type="action-next">
                                            Next Step
                                        </button>
                                    </div>

                                    <!--end: Form Actions -->
                                </form>

                                <!--end: Form Wizard Form-->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="{{ global_asset('assets/css/pages/wizard/wizard-3.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .custom-image-option + img {
            cursor: pointer;
        }

        .custom-image-option:checked + img {
            outline: 2px solid #F18B21;
        }

        .option-image {
            width :100% ;
            height : auto;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="{{ global_asset('js/server-date.js') }}"></script>

    <script>
        var submitFlag = false;
        // Set the date we're counting down to
        var countDownDate = new Date('{{ $endtime }}').getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date(ServerDate.now()).getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for hours, minutes and seconds
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            document.getElementById("timer").innerHTML = hours + "h "
            + minutes + "m " + seconds + "s ";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                submitFlag = true;
                document.getElementById('pointer-div').style.pointerEvents = "none";
                if ( document.getElementById("customFile") ) {
                    document.getElementById("customFile").required = false;
                }
                $('#kt_form').submit();
                document.getElementById("timer").innerHTML = `TIME UP!!`;
                toastr.error('Time is up!!!');
            }
        }, 1000);

        // Class definition
        var KTWizard3 = function () {
            // Base elements
            var wizardEl;
            var formEl;

            // Private functions
            var initWizard = function () {
                // Initialize form wizard
                wizard = new KTWizard('kt_wizard_v3', {
                    startStep: 1, // initial active step number
                    clickableSteps: true  // allow step clicking
                });

                // Change event
                wizard.on('change', function(wizard) {
                    $('input[type="hidden"]').focus();
                    KTUtil.scrollTop();
                });
            }

            return {
                // public functions
                init: function() {
                    wizardEl = KTUtil.get('kt_wizard_v3');
                    formEl = $('#kt_form');

                    initWizard();
                }
            };
        }();

        window.onbeforeunload = function(event)
        {
            if(event.target.activeElement.id !== 'submit-btn' && event.target.activeElement.id !== 'modal-confirm-btn') {
                if ( submitFlag == false ) {
                    return confirm("Are you sure you want to refresh? Unsaved data will be cleared.");
                }
            }
        };

        history.pushState(null, document.title, location.href);
        window.addEventListener('popstate', function (event)
        {
            const leavePage = confirm("Are you sure you want to go back? Unsaved data will be cleared.");
            if (leavePage) {
                history.back(); 
            } else {
                history.pushState(null, document.title, location.href);
            }  
        });

        jQuery(document).ready(function() {
            KTWizard3.init();

            $('#next-btn').on('click', function() {
                for ( instance in CKEDITOR.instances )
                    CKEDITOR.instances[instance].updateElement();

                var formData = new FormData($('#kt_form')[0]);

                const url = "@php echo url('/auto-save') @endphp";

                $.ajax({
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
                        $('#keen-spinner').show();
                        $('#main-body-div').css({ opacity: 0.5 });
                    },
                    success: function(res) {
                        $('#keen-spinner').hide();
                        $('#main-body-div').css({ opacity: 1 });
                    }
                });
            });

            $('#submit-btn').on('click', function(e) {
                e.preventDefault();
                var form=$('#kt_form');
                console.log(form);
                form.validate({
                    focusInvalid: true,
                    onfocusout:true,
                    rules: {
                        upload_answer: {
                            required: true,
                            extension: "pdf,doc,docx",
                        },
                    }
                });
                
                if (!form.valid()) {
                    KTApp.unblockPage();
                    e.preventDefault();
                    return;
                }else{
                    $('#finishExam').modal();

                }

            });
            
            $(document).on('click','#modal-confirm-btn', function(e) {
                e.preventDefault();
                if ( $('input[name="question_type"]').val() == 'created-question' ) {
                    for ( instance in CKEDITOR.instances )
                        CKEDITOR.instances[instance].updateElement();

                    var formData = new FormData($('#kt_form')[0]);

                    const url = "@php echo url('/auto-save') @endphp";

                    $.ajax({
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
                            $('#keen-spinner').show();
                            $('#main-body-div').css({ opacity: 0.5 });
                        },
                        success: function(res) {
                            submitFlag = true;
                            $('#keen-spinner').hide();
                            $('#main-body-div').css({ opacity: 1 });
                            $('#finishExam').modal('hide');
                        }
                    });

                }

                $('#kt_form').submit();
            });

            $(document).on('keypress', function(e){
                if(e.keyCode == 13) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            });
        });

    </script>
@endpush