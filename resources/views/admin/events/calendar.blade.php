@extends('layouts.main')
@section('title','Events | '. config("app.name"))
@section('events','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
    'breadTitle' => __("Events"),
    'crumbs' => [
        [
            'name' => __("Events"),
            'url' => '/calendar'
        ],
    ]
])

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailModalLabel">{{ __("Event Details") }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
			</div>
        </div>
    </div>
</div>

<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__body kt-portlet__body--fit">
            <div class="col-12 p-5">
                <div id="calendar"></div>
            </div>
            <div class="col px-5">
                <span><i class="fa fa-circle" style="color:rgb(220, 53, 69)"></i> {{ __("Red - Holiday") }}<br>
                    <i class="fa fa-circle" style="color: rgb(40, 167, 69)"></i> {{ __("Green - Normal Event Day") }}</span>
            </div>
		</div>
	</div>
</div>	
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.1.0/main.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.1.0/main.min.js"></script>

<script>

	document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            height: 500,
            customButtons: {
                myCustomButton: {
                    text: "@if($today)Today's Event: {{ $today->title }} @endif"
                }
            },
            @if($today)headerToolbar: {
                center: 'myCustomButton',
            },@endif
            events: @json($events),
			dateClick: function(info) {
				$.ajax({
                    url: '/check-event/' + info.dateStr,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#keen-spinner').show();
                        $('#main-body-div').css({ opacity: 0.5 });
                    },
                    success: function(res) {
                        if (res.event) {
                            $('#modal-body').html(res.event.title);
							$('#detailModal').modal();
                        }
						else {
                            $('#modal-body').html('');
						}

                        $('#keen-spinner').hide();
                        $('#main-body-div').css({ opacity: 1 });
                    }
                });
            }
        },
        {
            initialView: 'dayGridMonth'
        });
        calendar.render();
    });
</script>
@endpush