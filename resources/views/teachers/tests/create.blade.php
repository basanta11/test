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
                'name' => 'Create',
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
					Create Test
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/tests">
                @csrf
                <input name="lesson_id" hidden value="{{ $lesson->id }}">
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Type") }}</label>
                        <select name="type" class="form-control">
                            <option selected disabled>Select type...</option>
                            <option value="0">Pre Test</option>
                            <option value="1">Post Test</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Test Start") }}</label>
                        <input type="text" name="test_start" class="form-control" id="kt_datepicker_1" readonly="" placeholder="Select date">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Duration") }} <span class="text-warning">Please enter in minutes</span></label>
                        <input type="number" name="duration" min="1" class="form-control" placeholder="Enter test duration in minutes">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Full Marks") }}</label>
                        <input type="number" name="full_marks" min="1" class="form-control" placeholder="Enter full marks">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Pass Marks") }}</label>
                        <input type="number" min="1" id="pmarks" name="pass_marks" class="form-control" placeholder="Enter pass marks">
                    </div>
                   
                    <div class="form-group">
                        <label>{{ __("Sets") }}</label><br>
                        <input type="text" name="sets" data-role="tagsinput" id="sections" placeholder="Press enter to add another set...">
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/tests/{{ $lesson->id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>

<script>
    $('#kt_datepicker_1').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
        startDate: '+0d'
    });

    $('select[name="type"]').selectpicker();
    
  

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
                test_start: {
                    required: true
                },
                duration: {
                    required: true,
                    digits: true
                },
                type: {
                    required: true
                },
                sets: {
                    required: true
                },
            }
        });
        
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return false;
        }

        return true;

    })
</script>
@endpush