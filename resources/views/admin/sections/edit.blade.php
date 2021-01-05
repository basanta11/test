@extends('layouts.main')
@section('title','Classrooms | '. config("app.name"))
@section('classes','kt-menu__item--open')
@section('sections','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => 'Sections',
        'crumbs' => [
            [
                'name' => 'Sections',
                'url' => '/sections'
            ],
            [
                'name' => 'Edit Section',
                'url' => url()->current()
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
					Edit Section
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/sections/{{ $section->id }}">
                @csrf
                @method('PATCH')

                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __("Enter name") }}" value="{{ $section->title }}">
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="/teachers"><button type="button" class="btn btn-secondary">Cancel</button></a>
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
    // $('#sections').tagsinput();

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
                    required: true
                },
                sections: {
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