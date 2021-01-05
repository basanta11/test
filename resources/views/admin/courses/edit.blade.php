@extends('layouts.main')
@section('title','Courses | '. config("app.name"))
@section('courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Courses"),
        'crumbs' => [
            [
                'name' => __("Courses"),
                'url' => '/courses'
            ],
            [
                'name' => __("Edit Course"),
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
					{{ __("Edit Course") }}
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/courses/{{ $course->id }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>{{ __("Classroom") }}</label>
                        <input type="text" name=""  value="{{ $course['classroom']['title'] }}" readonly class="form-control">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                    <input type="text" name="title" class="form-control" value="{{ $course->title }}" placeholder="{{ __("Enter title") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Description") }}</label>
                        <textarea name="description" class="form-control" placeholder="{{ __("Enter description") }}">{{ $course->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Credit Hours") }}</label>
                        <input type="number" name="credit_hours"  value="{{ $course->credit_hours }}" class="form-control" placeholder="{{ __("Enter credit hours") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Learning Outcome") }}</label>
                        <textarea name="learn_what" class="form-control" placeholder="{{ __("Enter learning outcome") }}">{{ $course->learn_what }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Course Cover Image") }}</label>
                        <div class="custom-file">
                            <input accept="image/*" type="file" name="photo" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/courses"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script src="{{ global_asset('assets/js/pages/crud/forms/widgets/select2.js') }}" type="text/javascript"></script>
<script>

    $('select[name="classroom_id"],select[name="section_id"]').selectpicker();
    $('select[name="user_id[]"]').select2({
        placeholder: "Please select teachers...",
    });
    // email check
    $('input[name="email"').on('focus',function(e){
        if($("#" + 'email-error-custom').length != 0) {
            $('input[name="email"').removeClass('is-invalid')
            $("#email-error-custom").remove();
        }
    })
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
           var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
                title: {
                    required: true,
                    maxlength:150,
                },
                credit_hours: {
                    required: true,
                    maxlength:3,
                    max: 999,
                },
                learn_what: {
                    required: true,
                    maxlength:500,
                },
                description: {
                    required: true,
                    maxlength:500,
                },
            }
        });
        
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return;
        }

        return true;

    })

    $('select[name="classroom_id"]').on('change',function(e){
        var elem=$(this);
        const s = $('select[name="section_id"]');
        let url = '{{ url("/api/get-section") . '/' }}' + elem.val();

        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            success: function (res) {
                if (res !== []) {
                    s.html('');
                    s.append('<option selected disabled>Please select section..</option>');
                    $.each(res, function( index, value ) {
                        let html = `<option value="${value.id}">${value.title}</option>`;

                        s.append(html);
                    });

                    s.selectpicker('refresh');
                }
                else {
                    s.html('');
                }
            }
        });
    });
</script>
@endpush