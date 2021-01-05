@extends('layouts.main')
@section('title','Behaviours | '. config("app.name"))

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Behaviours"),
        'crumbs' => [
            [
                'name' => __("Behaviours"),
                'url' => '/behaviours'
            ],
            [
                'name' => 'Edit Behaviour',
                'url' => url()->current()
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Edit Behaviour") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/behaviours/{{ $behaviour->id }}">
                @csrf
                @method('PATCH')

                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>Student</label>
                        <input type="text" value="{{ $behaviour->student->name }}" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label>Behaviour</label>
                        <textarea name="behaviour" cols="30" rows="10" class="form-control" required>{{ $behaviour->behaviour }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Marks</label>
                        <input type="number" min=0 class="form-control" name="marks" value="{{ $behaviour->marks }}" required>
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/behaviours"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection 
@push('scripts')
<script>
    $('.kt-form').on('submit',function(e){
        KTApp.blockPage();
    })
</script>
@endpush