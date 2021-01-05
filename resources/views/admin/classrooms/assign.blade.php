@extends('layouts.main')
@section('title','Classrooms | '. config("app.name"))
@section('classes','kt-menu__item--open')
@section('classrooms','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Classrooms'),
        'crumbs' => [
            [
                'name' => __('Classrooms'),
                'url' => '/classrooms'
            ],
            [
                'name' => __("Assign Class Teacher"),
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
					{{ __("Assign Class Teacher") }}: {{ $classroom->title }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form action="/assign-class-teacher/{{ $classroom->id }}" method="POST">
                @csrf

                @foreach ($sections as $section)
                    <div class="form-group row">
                        <div class="col-md-4">
                            <input type="text" value="{{ $section->title }}" class="form-control" disabled>
                        </div>
                        <div class="col-md-8">
                            <select class="form-control selectpicker" name="teacher[{{$section->id}}][user_id]" required>
                                <option value="" @if(!$section->user_id) selected @endif disabled></option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @if($section->user_id == $teacher->id) selected @endif>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                    <a href="/classrooms"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection

@push('scripts')
<script>
    $('.selectpicker').selectpicker();
</script>
@endpush