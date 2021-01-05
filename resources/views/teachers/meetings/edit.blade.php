@extends('layouts.main')
@section('title','Meetings | '. config("app.name"))
@section('meetings','kt-menu__item--open')
@section('meetings','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Virtual Classrooms"),
	'crumbs' => [
		[
			'name' => __("Virtual Classrooms"),
			'url' => '/meetings',
        ],
        [
            'name'=> __("Edit Virtual Classroom"),
            'url'=> url()->current(),
        ]
	]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Edit Virtual Classroom") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/meetings/{{ $meeting->id }}">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" value="{{ $meeting->title }}" class="form-control" placeholder="{{ __("Enter name") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Course") }}</label>
                        <select name="course_id" class="form-control">
                            <option selected disabled>{{ __("Select course") }}...</option>
                            @foreach($courses as $course)
                            <option @if($meeting->course_id==$course->id) selected @endif value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                    
                        <label>{{ __("Sections") }}</label>
                        <select name="section_id[]" multiple="" class="form-control">
                            @foreach($sections as $section)
                                <option @if(in_array($section['section']['id'], $meeting_sections)) selected @endif value="{{ $section['section']['id'] }}">{{ $section['section']['title'] }}</option>
                            
                            @endforeach
                        </select>
                        
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12">{{ __("Date") }}</label>
                        <div class="col-lg-12">
                            <div class="input-daterange input-group row" id="kt_datepicker_5">     
                                <div class="col-lg-6">
                                    <input type="text" value="{{ $meeting->start_date }}" class="form-control" name="start_date" placeholder="{{ __("Select start date") }}">
                                </div>
                                {{-- <span class="input-group-text px-1"><i class="la la-ellipsis-h"></i></span> --}}
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" value="{{ $meeting->end_date }}" name="end_date" placeholder="{{ __("Select end date") }}">
                                </div>
                                    
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <label class="col-lg-12">{{ __("Time") }}</label>
                            <div class="col-lg-12">
                                <div class="input-daterange input-group row" > 
                                    <div class="col-lg-6">    
                                        <input class="form-control" value="{{ $meeting->start_time }}" id="kt_timepicker_1" name="start_time" readonly="" placeholder="{{ __("Select start time") }}" type="text">
                                    </div>
                                    {{-- <span class="input-group-text px-1"><i class="la la-ellipsis-h"></i></span> --}}
                                    <div class="col-lg-6">
                                        <input class="form-control " value="{{ $meeting->end_time }}" id="kt_timepicker_2" name="end_time" readonly="" placeholder="{{ __("Select end time") }}" type="text">
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label>{{ __("Password") }}</label>
                        <input type="text" name="password" value="{{ $meeting->token }}"  class="form-control" placeholder="{{ __("Enter password for the virtual classroom") }}">
                    </div>
                   
                    
                    
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/meetings"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
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
    // Class definition

    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }


    // range picker
    $('#kt_datepicker_5').datepicker({
        rtl: KTUtil.isRTL(),
        todayHighlight: true,
        templates: arrows,
        format: 'yyyy-mm-dd',
        autoclose: true,
        startDate: '+0d'
    });



    $('#kt_datepicker_1').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
        startDate: '+0d'
    });

    $('select[name="course_id"]').selectpicker('refresh');
    $('select[name="section_id[]"]').select2({
        placeholder: "{{ __('Please select course first') }}...",

    });
    

    $('select[name="course_id"]').on('change',function(e){
        var s= $('select[name="section_id[]"]')
        $.ajax({
            url:'/meetings/{{ $course->id }}/get-sections',
            type:'GET',
            dataType:'JSON',
            success:function(data){
                console.log(data.data)
                if (data.data !== []) {
                    s.html('');
                    $.each(data.data, function( index, value ) {
                        console.log(value.section)
                        let html = `<option value="${value.section.id}">${value.section.title}</option>`;

                            s.append(html);
                        });
                        s.select2({
                            placeholder:"Please select sections",
                            val: ""
                        });
                    }
                    else {
                        s.html('');
                    }
            }
        })
    });
  

    $('.kt-form').on('submit',function(e){
        var form=$(this);
        KTApp.blockPage();

        
        form.validate({
            focusInvalid: true,
            rules: {
                title: {
                    required: true
                },
                course_id:{
                    required: true
                },
                'section_id[]':{
                    required: true
                },
                start_date:{
                    required:true,
                },
                end_date:{
                    required:true,
                },
                start_time:{
                    required:true,
                },
                end_time:{
                    required:true,
                },
                password:{
                    required:true,
                    minlength:6,
                    maxlength:12
                }

               

            }
        });
        
        // form.find('#kt_timepicker_2')
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return false;
        }

        return true;

    })
    $('input[name="start_time"], input[name="end_time"]').timepicker({
        defaultTime:false,
        showMeridian: false,
    });
    $('input[name="end_time"]').timepicker().on('hide.timepicker', function(e) {
        var elem=$(this);
        if(! $('input[name="start_time"]').val()){
            toastr.error('Please set start time first.')
            elem.val('');
        }
        
        var start_time=$('input[name="start_time"]').val();

        var end_time=elem.val()
        
        // 
        var dt = new Date();

        //convert both time into timestamp
        var stt = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + start_time);

        stt = stt.getTime();
        var endt = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + end_time);
        endt = endt.getTime();
        
        if(stt==endt) {
            toastr.error('Both start and end time cannot be same.')
            elem.val('');
        }
        
        if(stt>endt) {
            toastr.error('End time cannot be less than start time')
            elem.val('');
        }
    });
    $('input[name="start_time"], input[name="end_time"]').timepicker().on('show.timepicker', function(e) {
        remErrCustom($(this));
    });
    $('input[name="start_time"]').timepicker().on('hide.timepicker', function(e) {
        var elem=$(this);
        var end_time=$('input[name="end_time"]').val();
        if(end_time){

            if(! $('input[name="start_time"]').val()){
                toastr.error('Please set start time first.')
                elem.val('');
            }
            

            var start_time=elem.val()
            
            // 
            var dt = new Date();

            //convert both time into timestamp
            var stt = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + start_time);

            stt = stt.getTime();
            var endt = new Date((dt.getMonth() + 1) + "/" + dt.getDate() + "/" + dt.getFullYear() + " " + end_time);
            endt = endt.getTime();
            if(stt==endt) {
                toastr.error('Both start and end time cannot be same.')
                elem.val('');
            }
            if(stt>endt) {
                toastr.error('Start time cannot be more than end time')
                elem.val('');
            }
        }
    });
</script>
@endpush