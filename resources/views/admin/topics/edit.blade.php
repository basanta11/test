@extends('layouts.main')
@section('title','Topics | '. config("app.name"))
@if ( !auth()->user()->hasRole('Teacher') )
    @section('courses','kt-menu__item--open')
@else
    @section('assigned-courses','kt-menu__item--open')
@endif

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Topics'),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses/'.$lesson['course']['id'] : '/assigned-courses/'.$lesson['course']['id'],
            ],
            [
                'name' => $lesson->title,
                'url' => '/lessons/'.$lesson->id,
            ],
            [
                'name' => __('Edit Topic'),
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
					{{ __("Edit Topic") }}
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/topics/{{ $topic->id }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" value="{{ $topic->title }}" name="title" class="form-control" placeholder="Enter title">
                    </div>
                    <div id="kt_repeater_1" class="mb-4">
                        <div class="form-group form-group-last row">
                            <label class="col-lg-12 col-form-label">{{ __("Reference Links") }}:</label>
                            <div data-repeater-list="references" class="col-lg-12">
                            @if(!$topic->reference_links)
                                <div data-repeater-item="" class="form-group row align-items-center" style="">
                                    <div class="col-md-10">
                                        <div class="kt-form__group--inline">
                                            
                                            <div class="kt-form__control">
                                            <input type="text" name="references" maxlength="150" class="form-control"  placeholder="Enter reference link..." >
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
                            @else
                                @foreach(json_decode($topic->reference_links) as $ref)
                                <div data-repeater-item="" class="form-group row align-items-center" style="">
                                    <div class="col-md-10">
                                        <div class="kt-form__group--inline">
                                            
                                            <div class="kt-form__control">
                                            <input type="text" name="references" maxlength="150" class="form-control" value="{{ $ref }}"  placeholder="Enter reference link..." >
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
                                @endforeach
                            @endif
                            
                        </div>
                        <div class="col-lg-4">
                            <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                <i class="la la-plus"></i> {{ __("Add") }}
                            </a>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/lessons/{{ $lesson->id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
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
                references: {
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
    $('#kt_repeater_1').repeater({
        initEmpty: false,
        // isFirstItemUndeletable: true,
        // defaultValues: {
        //     'text-input': 'foo',
        // },
            
        show: function () {
            $(this).slideDown();
        },

        hide: function (deleteElement) {                
            $(this).slideUp(deleteElement);                 
        }   
    });



    
</script>
@endpush