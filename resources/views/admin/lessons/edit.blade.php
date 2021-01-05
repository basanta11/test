@extends('layouts.main')
@section('title','Lessons | '. config("app.name"))
@if ( !auth()->user()->hasRole('Teacher') )
    @section('courses','kt-menu__item--open')
@else
    @section('assigned-courses','kt-menu__item--open')
@endif

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Lessons"),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' =>  !auth()->user()->hasRole('Teacher') ? '/courses/'.$lesson['course']['id'] : '/assigned-courses/'.$lesson['course']['id'],
            ],
            [
                'name' => __('Edit Lesson'),
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
					{{ __("Edit Lesson") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/lessons/{{ $lesson->id }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" value="{{ $lesson->title }}" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Brief") }}</label>
                        <textarea  name="brief" class="form-control" placeholder="Enter brief">{{ $lesson->brief }}</textarea>
                    </div>
                    
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        @if ( !auth()->user()->hasRole('Teacher') )
                            <a href="/courses/{{ $lesson['course']['id'] }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                        @else
                            <a href="/assigned-courses/{{ $lesson['course']['id'] }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                        @endif
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
                brief: {
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