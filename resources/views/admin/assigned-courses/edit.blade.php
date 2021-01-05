@extends('layouts.main')
@section('title','Assigned Courses | '. config("app.name"))
@section('assigned-courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Assigned Courses"),
        'crumbs' => [
            [
                'name' => __("Assigned Courses"),
                'url' => '/assigned-courses'
            ],
            [
                'name' => 'Edit Course',
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
					Edit Course
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/assigned-courses/{{ $course->id }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                    <input type="text" name="title" class="form-control" value="{{ $course->title }}" placeholder="Enter title" disabled>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Credit Hours") }}</label>
                        <input type="number" name="credit_hours"  value="{{ $course->credit_hours }}" class="form-control" placeholder="Enter credit hours" disabled>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Learning Outcome") }}</label>
                        <textarea name="learn_what" class="form-control" placeholder="Enter what is learnt">{{ $course->learn_what }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Classroom") }}</label>
                        <input type="text" name=""  value="{{ $course['classroom']['title'] }}" disabled class="form-control" placeholder="Enter credit hours">
                    </div>
                    
                    
                    {{-- <div class="form-group form-group-last">
                        <label for="exampleTextarea">Example textarea</label>
                        <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                    </div> --}}
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/assigned-courses"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script>

    $('select[name="classroom_id"],select[name="section_id"]').selectpicker();
    
$('.kt-form').on('submit',function(e){
            KTApp.blockPage();
           var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
                learn_what: {
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

</script>
@endpush