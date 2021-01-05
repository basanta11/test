@extends('layouts.main')
@section('title','Feedbacks | '. config("app.name"))
@section('feedbacks','kt-menu__item--open')

@section('content')
    @include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Feedbacks'),
        'crumbs' => [
            [
                'name' => __('Feedbacks'),
                'url' => '/feedbacks'
            ],
            [
                'name' => __('Add') . ' ' . __('Feedback'),
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
                        {{__('Add') . ' ' . __('Feedback')}}
                    </h3>
                </div>
                
            </div>
            <div class="kt-portlet__body">

                <form method="POST" action="{{ url('/feedbacks') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    @include('admin.feedbacks.form', ['formMode' => 'create'])
                </form>
            </div>
        </div>
    </div>
@endsection
