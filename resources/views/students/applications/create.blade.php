@extends('layouts.main')
@section('title','Leave Applications | '. config("app.name"))
@section('applications','kt-menu__item--open')
@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Leave Applications'),
        'crumbs' => [
            [
                'name' => __('Leave Applications'),
                'url' => '/leave-applications'
            ],
            [
                'name' => __('Create Leave Applications'),
                'url' => '/leave-applications/create'
            ],
        ]
	])

    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        @include('layouts.partials.flash-message')
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        {{ __('Create Leave Applications') }}
                    </h3>
                </div>
                
            </div>
            <div class="kt-portlet__body">
                <form class="kt-form" method="POST" action="/leave-applications">
                    @csrf

                    <div class="kt-portlet__body p-0 pb-4">
                        <div class="form-group">
                            <label>{{ __("Message") }}</label>
                            <textarea name="body" class="form-control" cols="30" rows="10" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ __("Date") }}</label>
                            <input type="text" name="leave_date" class="form-control" id="kt_datepicker_1" placeholder="Select date" required>

                        </div>
                    </div>
                    <div class="kt-portlet__foot px-0 pt-4">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                            <a href="/schedules"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                        </div>
                    </div>
                </form>
            </div>
        
        </div>

    </div>

@endsection

@push('scripts')    
    <script>
        $('#kt_datepicker_1').datepicker({
            startDate: '0d',
            todayHighlight: true,
            format:'yyyy-mm-dd',
            autoclose: true,
        });

        $('#kt_datepicker_1').on('change', function() {
            var el = $(this).val();

            if (el < new Date()) {
                toastr.error('Future dates can only be selected!');
                el.val('');
            }
        });

        $('.kt-form').on('submit',function(e){
            KTApp.blockPage();
        })
    </script>
@endpush