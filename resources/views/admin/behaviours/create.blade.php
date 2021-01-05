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
                'name' => 'Add Behaviour',
                'url' => '/behaviours/create'
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Add Behaviour") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/behaviours">
                @csrf

                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{ __("Classroom") }}: </label>
                            <select name="classroom_id" class="form-control selectpicker" id="classroom-select" required>
                                <option value="" selected disabled>Please select classroom...</option>
                                @foreach ($classrooms as $cr)
                                    <option value="{{ $cr->id }}">{{ $cr->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Section</label>
                            <select name="section_id" id="section-select" class="form-control selectpicker" required>
                                <option value="" selected disabled>Select classroom first...</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label>Student</label>
                        <select name="student_id" id="student-select" class="form-control selectpicker" required>
                            <option value="" selected disabled>Select section first...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Behaviour</label>
                        <textarea name="behaviour" cols="30" rows="10" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Marks</label>
                        <input type="number" min=0 class="form-control" name="marks" required>
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
    $('.selectpicker').selectpicker();
    $('.kt-form').on('submit',function(e){
        KTApp.blockPage();
    })
    $('#classroom-select').on('change', function() {
        let selected = $(this).val();
        let s = $('#section-select');

        $.ajax({
            url: '/api/get-section/' + selected,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#keen-spinner').show();
                $('#main-body-div').css({ opacity: 0.5 });
            },
            success: function(res) {
                console.log(res);

                if (res) {
                    s.html('');
                    s.append('<option selected disabled>Please select section..</option>');
                    $.each(res, function( index, value ) {
                        let html = `<option value="${value.id}">${value.title}</option>`;

                        s.append(html);
                    });

                    s.selectpicker('refresh');
                }

                $('#keen-spinner').hide();
                $('#main-body-div').css({ opacity: 1 });
            }
        });
    });
    $('#section-select').on('change', function() {
        let selected = $(this).val();
        let s = $('#student-select');

        $.ajax({
            url: '/api/getStudentsFromSection/' + selected,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#keen-spinner').show();
                $('#main-body-div').css({ opacity: 0.5 });
            },
            success: function(res) {
                console.log(res);
                if (res.students) {
                    s.html('');
                    s.append('<option selected disabled>Please select student..</option>');
                    $.each(res.students, function( index, value ) {
                        let html = `<option value="${value.user.id}">${value.user.name} (${value.roll_number})</option>`;

                        s.append(html);
                    });

                    s.selectpicker('refresh');
                }

                $('#keen-spinner').hide();
                $('#main-body-div').css({ opacity: 1 });
            }
        });
    });
</script>
@endpush