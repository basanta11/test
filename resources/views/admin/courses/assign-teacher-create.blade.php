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
                'name' => 'Assign Course',
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
					Assign Course
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
                <form class="kt-form" method="POST" action="/courses/assign-teacher/{{ $course->id }}" >
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" value="{{ $course->title }}" readonly class="form-control">
                    </div>
                    @if(!$hasTeachers)
                        @foreach($sections as $s)
                            <div class="form-group ">
                            <label>{{ $s->title }}</label>
                                
                            <select class="form-control kt-select2 users" placeholder="Please select teachers..." title="Please select teachers..." name="section_id[{{ $s->id }}][]" required>
                                    <option disabled>Select teachers...</option>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    @else 
                        @foreach($sections as $s)
                        <div class="form-group ">
                            <label>{{ $s['title'] }}</label>
                            <select class="form-control kt-select2 users" placeholder="Please select teachers..." title="Please select teachers..." name="section_id[{{ $s['id'] }}][]" required>
                                <option disabled>Select teachers...</option>
                                @foreach($teachers as $t)
                                    <option  {{ in_array($t->id, $s['teachers']) ? 'selected' : '' }} value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                                </select>
                            </div>
                        @endforeach
                    @endif
                    
                    {{-- <div class="form-group form-group-last">
                        <label for="exampleTextarea">Example textarea</label>
                        <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                    </div> --}}
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

    $('.section').selectpicker();
    $('.users').selectpicker({
        placeholder: "Please select teachers...",
    });
   
    
$('.kt-form').on('submit',function(e){
            KTApp.blockPage();
           var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
                section_id: {
                    required: true,
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