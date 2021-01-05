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
            'name' => __("Edit Exam"),
            'url' => url()->current()
        ],
    ]
])
<div class="modal fade" id="set_del_modal" tabindex="-1" role="dialog" aria-labelledby="set_del_modal_header" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="set_del_modal_header">Delete Set</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this set?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="set_del_modal_submit" type="button" data-set-id="" class="btn btn-primary">Proceed</button>
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
					Edit Exam
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/exams/{{ $exam->id }}">
                @csrf
                @method('PATCH')

                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __("Enter name") }}" value="{{ $exam->title }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Type") }}</label>
                        <select name="type" class="form-control">
                            <option value="0" {{ $exam->type == 0 ? 'selected' : '' }}>1<sub>st</sub> Terminal</option>
                            <option value="1" {{ $exam->type == 1 ? 'selected' : '' }}>2<sub>nd</sub> Terminal</option>
                            <option value="2" {{ $exam->type == 2 ? 'selected' : '' }}>3<sub>rd</sub> Terminal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Exam Start") }}</label>
                        <input type="text" name="exam_start" class="form-control" id="kt_datepicker_1" readonly="" placeholder="Select date" value="{{ date('Y-m-d H:i', strtotime($exam->exam_start)) }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Duration") }} <span class="text-warning">Please enter in minutes</span></label>
                        <input type="number" name="duration" min="1" class="form-control" placeholder="Enter exam duration in minutes" value="{{ $exam->duration }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Full Marks") }}</label>
                        <input type="number" name="full_marks" min="1" class="form-control" placeholder="Enter full marks" value="{{ $exam->full_marks }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Pass Marks") }}</label>
                        <input type="number" id="pmarks" min="1" name="pass_marks" class="form-control" placeholder="Enter pass marks" value="{{ $exam->pass_marks }}">
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("Classroom") }}</label>
                                <select name="classroom_id" class="form-control" >
                                    @foreach($classrooms as $cr)
                                        <option value="{{ $cr->id }}" {{ $selectedClass == $cr->id ? 'selected' : '' }}>{{ $cr->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Section") }}</label>
                                <select name="sections[]" class="form-control" multiple>
                                    @foreach ($sections as $sec)
                                        <option value="{{ $sec->id }}" <?php echo in_array((int)$sec->id, $selectedSections) ? 'selected' : ''; ?>>{{ $sec->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("Course") }}</label>
                                <select id="course-select" name="course_id" class="form-control">
                                    @foreach( $courses as $course )
                                        <option value="{{ $course->course->id }}"  {{ $exam->course_id == $course->course->id ? 'selected' : '' }}>{{ $course->course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Assign Teacher") }}</label>
                                <select id="teacher-select" name="user_id" class="form-control">
                                    @foreach( $teachers as $teacher )
                                        <option value="{{ $teacher->id }}"  {{ $exam->user_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="kt_repeater_1" class="mb-4">
                        <div class="form-group form-group-last row">
                            <label class="col-lg-12 col-form-label">{{ __("Sets") }}:</label>
                            @foreach($sets as $key=> $set)
                                <div class="col-lg-12" id="set-div-{{ $set->id }}" data-id="{{ $set->id }}">
                                    <div class="form-group row align-items-center">
                                        <div class="col-md-10">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__control">
                                                    <input type="text" name="oldset[{{$set->id}}]" maxlength="150" class="form-control" value="{{ $set->title }}">
                                                </div>
                                            </div>
                                        </div>
                                        @if($key!=0)
                                        <div class="col-md-2">
                                            <a href="javascript:;" class="w-100 btn-sm btn btn-label-danger btn-bold del-btn" data-parent="set-div-{{ $set->id }}" data-toggle="modal" data-target="#set_del_modal">
                                                <i class="la la-trash-o"></i>
                                                {{ __("Delete") }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <div data-repeater-list="sets" class="col-lg-12">
                                <div data-repeater-item="" class="form-group row align-items-center" style="">
                                    <div class="col-md-10">
                                        <div class="kt-form__group--inline">
                                            
                                            <div class="kt-form__control">
                                                <input type="text" name="sets" maxlength="150" class="form-control" placeholder="Enter new set name..." >
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

    $('#kt_repeater_1').repeater({
        initEmpty: false,
        // isFirstItemUndeletable: true,
        defaultValues: {
            'text-input': 'foo',
        },
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }   
    });

    $('select[name="type"],select[name="classroom_id"],select[name="course_id"],select[name="user_id"]').selectpicker();

    $('select[name="sections[]"]').select2();

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
                        toastr.error('This classroom does not have any courses assigned yet.');

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

    $('.del-btn').on('click', function() {
        const parent = $(this).attr('data-parent');
        const setId = $('#'+parent).attr('data-id');

        $('#set_del_modal_submit').attr('data-set-id', setId);
    });

    $('#set_del_modal_submit').on('click', function() {
        const setId = $(this).attr('data-set-id');

        $.ajax({
            url: '/api/sets/' + setId,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('#keen-spinner').show();
                $('#main-body-div').css({ opacity: 0.5 });
            },
            success: function(res) {
                $('#set-div-'+setId).remove();
                $('#set_del_modal').modal('hide');
                $('#keen-spinner').hide();
                $('#main-body-div').css({ opacity: 1 });
            }
        });
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
                user_id: {
                    required: true
                },
                'sections[]': {
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