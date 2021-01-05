@extends('layouts.main')
@section('title','Exams | '. config("app.name"))
@section('exams','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Exams"),
        'crumbs' => [
            [
                'name' => __("Exams") ,
                'url' => '/exam-students'
            ],
            [
                'name' => 'View Result',
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
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__content pl-0">
                                    <div class="kt-widget__head"> 
                                        <span href="#" class="kt-widget__title">{{ $set->title }}</span>
                                        
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__desc">
                                            <span>Exam: {{ $set['exam']['title'] }}</span>
                                        </div>
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__desc">
                                            <span>Type: @if($set['exam']['type']==0) 1st Terminal @elseif($set['exam']['type']==1) 2nd Terminal @else 3rd Terminal @endif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget__bottom">
                                <div class="kt-widget__item col ">
                                    <div class="kt-widget__details pl-0">
                                        <span class="kt-widget__title">Total Marks</span>
                                        <span class="kt-widget__value">{{ $set['exam']['full_marks'] }}</span>
                                    </div>
                                </div>
                                <div class="kt-widget__item col ">
                                    <div class="kt-widget__details pl-0">
                                        <span class="kt-widget__title">Pass Marks</span>
                                        <span class="kt-widget__value">{{ $set['exam']['pass_marks'] }}</span>
                                    </div>
                                </div>
                                <div class="kt-widget__item col ">
                                    <div class="kt-widget__details pl-0">
                                        <span class="kt-widget__title">Obtained Marks</span>
                                        <span class="kt-widget__value">{{ $marksObtained }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection