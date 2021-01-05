@extends('layouts.main')
@section('title','Schedules | '. config("app.name"))
@section('schedules','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Schedules'),
        'crumbs' => [
            [
                'name' => __('Schedules'),
                'url' => '/schedules'
            ],
            [
                'name'=>$schedule['section']['title'],
                'url'=>'/schedules/'.$schedule['section']['id'],
            ],
            [
                'name' => __('Edit') . ' ' . __('Schedule'),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				{{-- <span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-line-plus"></i>
				</span> --}}
				<h3 class="kt-portlet__head-title">
					{{ __('Edit') . ' ' . __('Schedule') }}
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/schedules/{{ $schedule->id }}">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>{{ __("Classroom") }}</label>
                        <input class="form-control" name="classroom_id" data-val="{{ $schedule['section']['classroom']['id'] }}" readonly value="{{ $schedule['section']['classroom']['title'] }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Section") }}</label>
                        <input class="form-control" name="section_id" data-val="{{ $schedule['section']['id'] }}" readonly value="{{ $schedule['section']['title'] }}">
                        
                    </div>
                    <div class="form-group">
                        <label>{{ __("Type") }} &nbsp;&nbsp;</label>
                        <input value="0" data-switch="true" type="checkbox" name="type" checked="checked" data-on-color="success" data-off-color="warning" id="type">
                        
                    </div>
                    <div class="form-group d-course">
                        <label>{{ __("Course") }}</label>
                        <select id="select-course" name="course_id" class="form-control selectpicker">
                            <option selected disabled>{{ __("Please select course") }}...</option>
                            @foreach($courses as $course)
                                <option {{ $course->id == $schedule->course_id ? 'selected' : '' }} value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Day") }}</label>
                        <select name="day" class="selectpicker form-control">
                            @foreach($days as $d)
                                <option {{ $d==$schedule->day ? 'selected' : '' }}  value="{{ $d }}">{{ ucfirst($d) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __("Start Time") }}</label>
                            <input class="form-control" name="start_time" value="{{ $schedule->start_time }}" placeholder="Please enter start time...">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __("End Time") }}</label>
                            <input class="form-control" name="end_time" value="{{ $schedule->end_time }}" placeholder="Please enter end time...">
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/schedules/{{ $schedule['section']['id'] }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
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
    const schedule=@json($schedule);
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
   
    
    $('.kt-form').on('submit',function(e){
        KTApp.blockPage();
        
        e.preventDefault();
        var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
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
        var section=(form.find('input[name="section_id"]').data('val'))
        var day=(form.find('select[name="day"]').val())
        $.ajax({
            url:`/validate-time/${start_time}/${end_time}/${day}/${section}`,
            dataType:'JSON',
            type:'GET',
            data:{
                id: schedule.id,
            },
            success:function(data){
                console.log(data);
                if(data.status==false){

                    KTApp.unblockPage();
                    makeErrCustom($('input[name="start_time"]'),data.message);
                    makeErrCustom($('input[name="end_time"]'),data.message);
                    return false;
                }else{
                    form.unbind('submit').submit()
                    return true;
                }
            }
        })
        // return false;

    })

    $('#select-course').selectpicker();

    // switch
    const course_type='{{ $schedule->type }}'
    $("[name='type']").bootstrapSwitch({
        onText: '{{ __("Teaching") }}',
        offText: '{{ __("Break") }}',
        state: course_type==1  ? true : false,
        onInit:function(ev){
            course_type == 1 ? _teaching() : _break()
            // _teaching()
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