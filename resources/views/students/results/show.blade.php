@extends('layouts.main')
@section('title','Results | '. config("app.name"))
@section('results','kt-menu__item--open')
@section('results','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Results"),
	'crumbs' => [
		[
			'name' => __("Results"),
			'url' => '/results'
        ],
        [
            'name' => __('View'),
            'url' => url()->current(),

        ]
	]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
        <div class="row"> 
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__content pl-0">
                                    <div class="kt-widget__head"> 
                                        <span href="#" class="kt-widget__title">{{ $set['exam']['course']['title'] }}</span>
                                        
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__desc">
                                            <span>{{ __("Exam") }}: {{ $set['exam']['title'] }}</span>
                                        </div>
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__desc">
                                            <span>{{ __("Set") }}: {{ $set->title }}</span>
                                        </div>
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__desc">
                                            <span>@if($set['exam']['type']==0) 1st Terminal @elseif($set['exam']['type']==1) 2nd Terminal @else 3rd Terminal @endif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__bottom">
                                <div class="kt-widget__item col ">
                                    <div class="kt-widget__details pl-0">
                                        <span class="kt-widget__title">{{ __("Total Marks") }}</span>
                                        <span class="kt-widget__value">{{ $set['exam']['full_marks'] }}</span>
                                    </div>
                                </div>
                                <div class="kt-widget__item col ">
                                    <div class="kt-widget__details pl-0">
                                        <span class="kt-widget__title">{{ __("Pass Marks") }}</span>
                                        <span class="kt-widget__value">{{ $set['exam']['pass_marks'] }}</span>
                                    </div>
                                </div>
                                <div class="kt-widget__item col ">
                                    <div class="kt-widget__details pl-0">
                                        <span class="kt-widget__title">{{ __("Obtained Marks") }}</span>
                                        <span class="kt-widget__value">{{ $marksObtained }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                        <span>1</span> {{ __("Setup Location") }}
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
                                <form class="kt-form" id="kt_form" >
                                    

                                    <input type="hidden" name="set_id" value="{{ $set->id }}">
                                    <input type="hidden" name="user_id" value="{{ $setuser->user_id }}">
                                    @foreach ($questions as $key => $question)
                                        @switch($question->type)
                                            {{-- Upload PDF --}}
                                            @case(0) 
                                                <input type="hidden" name="question_type" value="upload-pdf">
                                                <input type="hidden" name="answer_id" value="{{ $answers->first()->id }} }}">
                                                <div class="kt-wizard-v3__content" data-ktwizard-type="step-content" @if($loop->first) data-ktwizard-state="current" @endif>
                                                    <div class="kt-heading kt-heading--md">{{ __("Download Question") }} <small class="text-warning">({{ $question->marks }} marks)</small></div>
                                                    <div class="kt-form__section kt-form__section--first">
                                                        <div class="kt-wizard-v3__form">
                                                            <div class="form-group">
                                                                <a target="_blank" href="/exam-start/download/{{ $question->id }}/{{ auth()->user()->id }}"><img src="{{ global_asset('assets/media/files/pdf.svg') }}" style="height: 250px; width: 100%;"></a>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>{{ __("Answer") }}: </label>
                                                                <a target="_blank" href="/download-answer/{{ $answers->first()->id }}"><img src="{{ global_asset('assets/media/files/doc.svg') }}" style="height: 250px; width: 100%;"></a>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">{{ __("Marks") }}</label>
                                                                <input type="text" class="form-control marks-input" readonly name="marks" value="{{ ($answers->first()->marks) ? $answers->first()->marks : 0 }}" required>
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
                                                                <label for="">{{ __("Marks") }}</label>
                                                                <input type="text" class="form-control marks-input" readonly name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" required>
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
                                                                <label for="">{{ __("Marks") }}</label>
                                                                <input type="text" class="form-control marks-input" readonly name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" required>
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
                                                                {!! $question->title !!}
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
                                                                <label for="">{{ __("Marks") }}</label>
                                                                <input type="text" class="form-control marks-input" readonly name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" required>
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
                                                                {!! $question->title !!}
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
                                                                <label for="">{{ __("Marks") }}</label>
                                                                <input type="text" class="form-control marks-input" readonly name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" required>
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
                                                                <label for="">{{ __("Marks") }}</label>
                                                                <input type="text" class="form-control marks-input" readonly name="marks-question-{{ $question->id }}" value="{{ !empty($formattedAnswers[$question->id]['marks']) ? $formattedAnswers[$question->id]['marks'] : 0 }}" required>
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
                                            {{ __("Previous") }}
                                        </button>
                                        @endif
                                        <a  href="/results" class="btn btn-success btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u">
                                            {{ __("Go back to results") }}
                                        </a>
                                        <button id="next-btn" class="btn btn-brand btn-md btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
                                            {{ __("Next") }}
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