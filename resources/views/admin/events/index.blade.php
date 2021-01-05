@extends('layouts.main')
@section('title','Events | '. config("app.name"))
@section('events','kt-menu__item--open')

@section('content')
<div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" id="delete-form">
                @csrf
                @method('DELETE')
            </form>

            <form action="/events" method="POST" id="addEventForm">
                @csrf

                <input type="hidden" name="event_id" id="hidden-event-id">
                <input type="hidden" name="event_date" id="hidden-event-date">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">{{ __("Add Event") }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __("Event type") }}: </label>
                        <select name="status" class="form-control" id="status" required>
                            <option id="option-holiday" value="0" selected>{{ __("Holiday") }}</option>
                            <option id="option-normal" value="1">{{ __("Normal Event") }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Event title") }}: </label>
                        <input name="title" type="text" class="form-control" placeholder="{{ __("Enter event title") }}..." id="event-title-input" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
                    <button type="submit" form="delete-form" id="delete-btn" class="btn btn-danger">{{ __("Delete") }}</button>
                    <button type="submit" class="btn btn-primary">{{ __("Confirm") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.partials.breadcrumbs', [
    'breadTitle' => __("Events"),
    'crumbs' => [
        [
            'name' => __("Events"),
            'url' => '/events'
        ],
    ]
])
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
                            $('#event-title-input').val(res.event.title);
                            if ( res.event.status == 0 ) {
                                $('#status #option-holiday').attr('selected', "selected");
                                $('#status #option-normal').attr('selected', false);
                            }
                            else {
                                $('#status #option-normal').attr('selected', "selected");
                                $('#status #option-holiday').attr('selected', false);
                            }
                            $('#hidden-event-id').val(res.event.id);
                            $('#delete-form').attr('action', '/events/'+res.event.id);
                            $('#delete-btn').show();
                        }
                        else {
                            $('#event-title-input').val('');
                            $('#status #option-holiday').attr('selected', "selected");
                            $('#delete-btn').hide();
                        }

                        $('#keen-spinner').hide();
                        $('#main-body-div').css({ opacity: 1 });
                    }
                });

                $('#hidden-event-date').val(info.dateStr);
                $('#addEventModal').modal();
            }
        },
        {
            initialView: 'dayGridMonth'
        });
        calendar.render();
    });

    $('#addEventForm').on('submit', function(e) {
        $('#keen-spinner').show();
        $('#main-body-div').css({ opacity: 0.5 });
    });

    $('#delete-btn').on('click', function() {
        if ( confirm('Are you sure you want to delete this event?') == true ) {
            return true;
        }
        else {
            return false;
        }
    });
</script>
@endpush