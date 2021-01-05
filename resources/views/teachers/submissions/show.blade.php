@extends('layouts.main')
@section('title','Exams | '. config("app.name"))
@section('exams','kt-menu__item--open')
@section('exams','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Exams"),
	'crumbs' => [
		[
			'name' => __("Exams"),
			'url' => '/exam-teachers'
		],
		[
			'name' => __("View Submissions"),
			'url' => '/submissions/'.$setuser->id
		],
		[
			'name' => __("View Submission Details"),
			'url' => url()->current()
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
                            <span class="badge badge-primary">Full Marks: {{ $set->exam->full_marks }}</span> &nbsp;
                            <span class="badge badge-danger">Pass Marks: {{ $set->exam->pass_marks }}</span>
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
                            <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v3__wrapper">

                                <!--begin: Form Wizard Form-->
                                <form class="kt-form" id="kt_form" action="/submission-finish/{{ $setuser->id }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="set_id" value="{{ $set->id }}">
                                    <input type="hidden" name="user_id" value="{{ $setuser->user_id }}">
                                    @foreach ($questions as $key => $question)
                                        @switch($question->type)
                                            {{-- Upload PDF --}}
                                            @case(0) 
                                                <input type="hidden" name="question_type" value="upload-pdf">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">Download Question <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">
                                                            <label>Question: </label>
                                                            <div class="form-group text-center">
                                                                <a target="_blank" href="/submissions/download/{{ $question->id }}">
                                                                    
                                                                    <span >{{ $question->attachments()->first()->body }}</span>
                                                                    <img class="mt-2" src="{{ global_asset('assets/media/files/pdf.svg') }}" style="height: 150px; width: 100%;"> <button type="button" class="btn btn-warning align-center mt-3"> <i class="fa fa-download"></i> Download</button></a>
                                                            </div>
                                                            @if(!$answers->isEmpty())
                                                                <input type="hidden" name="answer_id" value="{{ $answers->first()->id }}">
                                                                <label>Answer: </label>
                                                                <div class="form-group text-center">
                                                                    <a target="_blank" href="/download-answer/{{ $answers->first()->id }}">
                                                                        <span >{{ $answers->first()->answer }}</span>
                                                                        <img class="mt-2" src="{{ global_asset('assets/media/files/doc.svg') }}" style="height: 150px; width: 100%;"> <button type="button" class="btn btn-warning align-center mt-3"> <i class="fa fa-download"></i> Download</button></a>
                                                                </div>
                                                            
                                                                <div class="form-group">
                                                                    <label for="">Marks</label>
                                                                    <input type="text" class="form-control marks-input" name="marks" value="{{ ($answers->first()->marks) ? $answers->first()->marks : 0 }}" disabled>
                                                                </div>
                                                            @else
                                                                <div class="form-group">
                                                                    <p>Answer not uploaded.</p>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Marks</label>
                                                                    <input type="text" class="form-control marks-input" name="marks" value="0" disabled>
                                                                </div>
                                                            @endif
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
                                                                                                disabled
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
                                                                                                disabled
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
                                                            
                                                            <div class="form-group">
                                                                <label for="">Marks</label>
                                                                <input type="text" class="form-control marks-input" name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" disabled>
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
                                                                                                value="{{ $option->id }}"
                                                                                                @if( $answers->contains(function($val, $key) use($question, $option) {
                                                                                                    return $val->question_id == $question->id && $val->question_option_id == $option->id;
                                                                                                }) ) 
                                                                                                    {{'checked'}} 
                                                                                                @endif
                                                                                                disabled
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
                                                                                                value="{{ $option->id }}"
                                                                                                class="custom-image-option"
                                                                                                @if( $answers->contains(function($val, $key) use($question, $option) {
                                                                                                    return $val->question_id == $question->id && $val->question_option_id == $option->id;
                                                                                                }) ) 
                                                                                                    {{'checked'}} 
                                                                                                @endif
                                                                                                disabled
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
                                                            
                                                            <div class="form-group">
                                                                <label for="">Marks</label>
                                                                <input type="text" class="form-control marks-input" name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" disabled>
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
                                                                <textarea class="form-control ckeditor" id="editor2" disabled>
                                                                    @php
                                                                        $ans = $answers->where('question_id', $question->id);
                                                                        
                                                                        echo !empty($ans->first()->answer) ? $ans->first()->answer : '';
                                                                    @endphp
                                                                </textarea>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="">Marks</label>
                                                                <input type="text" class="form-control marks-input" name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" disabled>
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
                                                                {!! $question->note !!}
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea class="form-control ckeditor" id="editor1" disabled>
                                                                    @php
                                                                        $ans = $answers->where('question_id', $question->id);
                                                                        
                                                                        echo !empty($ans->first()->answer) ? $ans->first()->answer : '';
                                                                    @endphp
                                                                </textarea>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="">Marks</label>
                                                                <input type="text" class="form-control marks-input" name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" disabled>
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
                                                                @php
                                                                    $textans = $answers->where('question_id', $question->id);
                                                                @endphp
                                                                <input 
                                                                    type="text" 
                                                                    class="form-control" 
                                                                    disabled
                                                                    value="{{ !empty($textans->first()->answer) ? $textans->first()->answer : '' }}"
                                                                >
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <label for="">Marks</label>
                                                                <input type="text" class="form-control marks-input" name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" disabled>
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
                                        <button class="btn btn-secondary btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
                                            Previous
                                        </button>
                                        @endif
                                        <a href="/submissions/{{ $set->exam_id }}" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" >
                                            Go back to submission
                                        </a>
                                        <button id="next-btn" class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
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

    <script>
        let flag = 0;

        
        
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

                // Validation before going to next page
                wizard.on('beforeNext', function(wizardObj) {
                    if ( $('div[data-ktwizard-state="current"]').find('.marks-input').val() == '' ) {
                        toastr.error('Please enter marks for this question.');
                        wizardObj.stop();
                    }

                    if ( flag >= 2 ) {
                        wizardObj.stop();
                    }
                });

                // Change event
                wizard.on('change', function(wizard) {
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

        jQuery(document).ready(function() {
            KTWizard3.init();

            
            
        });

    </script>
@endpush