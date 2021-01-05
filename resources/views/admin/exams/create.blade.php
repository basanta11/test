@extends('layouts.main')
@section('title','Exams | '. config("app.name"))
@section('exams','kt-menu__item--open')
@section('exams','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
    'breadTitle' => 'Exams',
    'crumbs' => [
        [
            'name' => 'Exams',
            'url' => '/exams'
        ],
        [
            'name' => __("Create Exam"),
            'url' => '/exams/create'
        ],
    ]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Create Exam") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/exams">
                @csrf

                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __("Enter name") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Type") }}</label>
                        <select name="type" class="form-control">
                            <option selected disabled>Select type...</option>
                            <option value="0">1<sub>st</sub> Terminal</option>
                            <option value="1">2<sub>nd</sub> Terminal</option>
                            <option value="2">3<sub>rd</sub> Terminal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Exam Start") }}</label>
                        <input type="text" name="exam_start" class="form-control" id="kt_datepicker_1" readonly="" placeholder="Select date">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Duration") }} <span class="text-warning">Please enter in minutes</span></label>
                        <input type="number" name="duration" min="1" class="form-control" placeholder="Enter exam duration in minutes">
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
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("Classroom") }}</label>
                                <select name="classroom_id" class="form-control" >
                                    <option selected disabled>{{ __("Select classroom") }}...</option>
                                    @foreach($classrooms as $cr)
                                        <option value="{{ $cr->id }}">{{ $cr->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Section") }}</label>
                                <select name="sections[]" class="form-control" multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("Course") }}</label>
                                <select id="course-select" name="course_id" class="form-control">
                                    <option value="" selected disabled>{{ __("Please select classroom first.") }}..</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Assign Teacher") }}</label>
                                <select id="teacher-select" name="user_id" class="form-control">
                                    <option value="" selected disabled>{{ __("Please select course first") }}...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Sets") }}</label><br>
                        <input type="text" name="sets" data-role="tagsinput" id="sections" placeholder="Press enter to add another set...">
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/exams"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
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

    $('select[name="type"],select[name="classroom_id"],select[name="course_id"]').selectpicker();
    
    $('select[name="sections[]"]').select2({
        placeholder: '{{ __("Please select classroom first.") }}..'
    });

    $('select[name="classroom_id"]').on('change',function(e){
        var elem=$(this);
        const s = $('select[name="sections[]"]');
        const courseSelect = $('#course-select');
        let url = '{{ url("/api/get-sections-and-courses") . '/' }}' + elem.val();

        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            beforeSend: function()
            {
                $('#keen-spinner').show();
                $('#main-body-div').css({ opacity: 0.5 });
            },
            success: function (res) {
                if (res.data != null) {
                    if (res.data.courses === null) {
                        courseSelect.html('');
                        $('#keen-spinner').hide();
                        $('#main-body-div').css({ opacity: 1 });
                        toastr.error('No teachers assigned to any courses in this classroom yet.');

                        return false;
                    }
                    else {
                        courseSelect.html('');
                        courseSelect.append(`<option value="">Select course...</option>`);

                        $.each(res.data.courses, function( i, v ) {
                            let htm = `<option value="${v.course.id}">${v.course.title}</option>`;

                            courseSelect.append(htm);
                            courseSelect.selectpicker('refresh');
                        });
                    }

                    if (res.data.sections !== []) {
                        s.html('');
                        $.each(res.data.sections, function( index, value ) {
                            let html = `<option value="${value.id}">${value.title}</option>`;

                            s.append(html);
                        });

                        s.select2("destroy").select2({
                            placeholder: 'Select sections...'
                        });
                    }
                }
                else {
                    $('#keen-spinner').hide();
                    $('#main-body-div').css({ opacity: 1 });

                    toastr.error('No courses in this classroom yet.');

                    s.html('');
                    courseSelect.html('');
                }

                $('#keen-spinner').hide();
                $('#main-body-div').css({ opacity: 1 });
            }
        });
    });

    $('#course-select').on('change', function() {
        const teacherSelect = $('#teacher-select');

        $.ajax({
            url: '/api/get-tecehers/'+$(this).val(),
            type: 'get',
            dataType: 'json',
            beforeSend: function()
            {
                $('#keen-spinner').show();
                $('#main-body-div').css({ opacity: 0.5 });
            },
            success: function(res) {
                if (res !== null) {
                    teacherSelect.html('');
                    teacherSelect.append(`<option value="">Select teacher...</option>`);

                    $.each(res, function( index, value ) {
                        let html = `<option value="${value.id}">${value.name}</option>`;

                        teacherSelect.append(html);
                    });

                    teacherSelect.selectpicker('refresh');
                }
                else {
                    $('#keen-spinner').hide();
                    $('#main-body-div').css({ opacity: 1 });

                    teacherSelect.html('');
                }

                $('#keen-spinner').hide();
                $('#main-body-div').css({ opacity: 1 });
            }
        })
    });

    $('.kt-form').on('submit',function(e){
        KTApp.blockPage();
        var form=$(this);
        
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
                'sections[]': {
                    required: true
                },
                course_id: {
                    required: true
                },
                user_id: {
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