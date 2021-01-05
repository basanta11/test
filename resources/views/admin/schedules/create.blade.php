@extends('layouts.main')
@section('title','Schedules | '. config("app.name"))
@section('schedules','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Schedules"),
        'crumbs' => [
            [
                'name' => __("Schedules"),
                'url' => '/schedules'
            ],
            [
                'name' => __('Create Schedule'),
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
					{{ __('Create Schedule') }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/schedules">
                @csrf

                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>{{ __("Classroom") }}</label>
                        <select id="select-classroom" name="classroom_id" title="{{ __('Please select classroom') }}..." class="form-control selectpicker">
                            @foreach($classrooms as $cr)
                                <option value="{{ $cr->id }}">{{ $cr->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Section") }}</label>
                        <select id="select-section" name="section_id" class="form-control selectpicker">
                            <option selected disabled>{{ __('Please select classroom first') }}...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Type") }} &nbsp;&nbsp;</label>
                        <input value="0" data-switch="true" type="checkbox" name="type" checked="checked" data-on-color="success" data-off-color="warning" id="type">
                        
                    </div>
                    <div class="form-group d-course">
                        <label>{{ __("Course") }}</label>
                        <select id="select-course" name="course_id" class="form-control selectpicker">
                            <option selected disabled>{{ __("Please select section first") }}...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Day") }}</label>
                        <select name="day" title="{{ __("Please select a day") }}..." class="form-control selectpicker">
                            <option value="sunday">Sunday</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __("Start Time") }}</label>
                            <input class="form-control" name="start_time" placeholder="{{ __("Please enter start time") }}...">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __("End Time") }}</label>
                            <input class="form-control" name="end_time" placeholder="{{ __("Please enter end time") }}...">
                        </div>
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
<script src="{{ global_asset('assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js') }}" type="text/javascript"></script>
<script>
    $('.selectpicker').selectpicker();
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
    // email check').timepicker();
    // email check
    $('input[name="email"').on('focusout',function(e){
        var elem=$(this);
        var email=elem.val();
        $.ajax({
            url:'/api/email-exists/'+email,
            dataType:'JSON',
            type:'GET',
            success:function(data){
                if(data.status){
                    elem.parent().addClass('is-invalid')
                    if($("#" + 'email-error-custom').length != 0) {
                        $('#email-error').html(`Email already taken`);
                    }else{
                        elem.parent().append(`<div id="email-error-custom" class="error invalid-feedback">Email already taken</div>`)
                    }
                    elem.addClass('is-invalid')
                    elem.val("");
                }
            }
        })
        
    })
    // email check end
    
    $('.kt-form').on('submit',function(e){

        KTApp.blockPage();
        e.preventDefault();
        var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
                day: {
                    required: true
                },
                classroom_id:{
                    required:true,
                },
                section_id:{
                    required:true,
                },
                start_time: {
                    required: true
                },
                end_time: {
                    required: true
                },
            }
        });

        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return false;
        }
       
        var start_time=(form.find('input[name="start_time"]').val())
        var end_time=(form.find('input[name="end_time"]').val())
        var section=(form.find('select[name="section_id"]').val())
        var day=(form.find('select[name="day"]').val())
        $.ajax({
            url:`/validate-time/${start_time}/${end_time}/${day}/${section}`,
            dataType:'JSON',
            type:'GET',
            success:function(data){
                console.log(data);
                if(data.status==false){
                    KTApp.unblockPage();
                    makeErrCustom($('input[name="start_time"]'),data.message);
                    makeErrCustom($('input[name="end_time"]'),data.message);
                }else{
                    form.unbind('submit').submit()
                    return true;
                }
            }
        })
        // return false;

    })

    $('#select-classroom').on('change',function(e){
        var id=$(this).val();
        var elem=$('#select-section');
        $.ajax({
            url:'/get-sections/'+id,
            type:'GET',
            dataType:'JSON',
            success:function(data){
                if(data.status){
                    console.log(data);
                    if (data.data !== []) {
                        elem.html('');
                        elem.attr('title','');
                        elem.append('<option selected disabled>Please select section..</option>');
                        $.each(data.data, function( index, value ) {
                            let html = `<option value="${value.id}">${value.title}</option>`;

                            elem.append(html);
                        });

                        elem.selectpicker('refresh');
                    }
                    else {
                        elem.html('');
                    }
                }else{
                    toastr.error('There seems to be a problem proceeding. Please try again.');
                }
            }
        });
    });

    $('#select-section').on('change',function(e){
        var id=$(this).val();
        var elem=$('#select-course');
        $.ajax({
            url:'/get-courses/'+id,
            type:'GET',
            dataType:'JSON',
            success:function(data){
                if(data.status){
                    console.log(data);
                    if (data.data !== []) {
                        elem.html('');
                        elem.attr('title','');
                        elem.append('<option selected disabled>Please select course..</option>');
                        $.each(data.data, function( index, value ) {
                            let html = `<option value="${value.id}">${value.title}</option>`;

                            elem.append(html);
                        });

                        elem.selectpicker('refresh');
                    }
                    else {
                        elem.html('');
                    }
                }else{
                    toastr.error('There seems to be a problem proceeding. Please try again.');
                }
            }
        });
    });

    // switch
    $("[name='type']").bootstrapSwitch({
        onText: '{{ __("Teaching") }}',
        offText: '{{ __("Break") }}',
        state: true,
        onInit:function(ev){
            _teaching()
        },
        onSwitchChange: function(event) {

            if($('#type').val()==0){
                _teaching()
            }else{
                _break()
            }
        }
    });

    function _break(){
        $('#type').val(0)
        $('.d-course').hide(300)
        $('select[name="course_id"]').removeAttr('required').attr('disabled','disabled')
        
    }
    function _teaching(){
        $('#type').val(1)
        $('.d-course').show(300)

        $('select[name="course_id"]').attr('required','required').removeAttr('disabled')
        $('#select-course').selectpicker('refresh');


    }
    // switch end

</script>
@endpush