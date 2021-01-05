@extends('layouts.main')
@section('title','Behaviours | '. config("app.name"))

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Behaviours"),
        'crumbs' => [
            [
                'name' => __("Behaviour Types"),
                'url' => '/behaviour-types'
            ],
            [
                'name' => 'Add Behaviour',
                'url' => '/behaviour-types/create'
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Add Behaviour Types") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/behaviour-types">
                @csrf

                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group ">
                        <label>Title</label>
                        <input class="form-control" name="title" placeholder="Enter Title" required>
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/behaviour-types"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection 
@push('scripts')
<script>
    $('.selectpicker').selectpicker();
    $('.kt-form').on('submit',function(e){
        KTApp.blockPage();
    })
</script>
@endpush