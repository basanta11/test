@extends('layouts.main')
@section('title','Assigned Courses | '. config("app.name"))
@section('assigned-courses-students','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Assigned Courses"),
	'crumbs' => [
		[
			'name' => __("Assigned Courses"),
			'url' => '/student/assigned-courses'
		],
		[
			'name' => __("View Course"),
			'url' => '/student/assigned-courses/'.$id,
		],
		[
			'name' => __('View Test'),
			'url' => '/tests-students/'.$id
        ],
        [
			'name' => __('Show Test'),
			'url' => url()->current()
		],
	]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
        <div class="row">
            <div class="col-xl-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-4">
                            <div class="kt-widget__head">
                                <div class="kt-widget__content">
                                    <div class="kt-widget__section">
                                        <a href="#" class="kt-widget__username">
                                            {{ $test->title }}
                                        </a>
                                        <div class="kt-widget__button">
                                            <button type="button" class="btn btn-label-primary">Lesson: {{ $test->lesson->title }}</button>
                                            <button type="button" class="btn btn-label-success">Full Marks: {{ $test->full_marks }}</button>
                                            <button type="button" class="btn btn-label-warning">Pass Marks: {{ $test->pass_marks }}</button>
                                            <button type="button" class="btn btn-label-info">Duration: {{ $test->duration }} minutes</button>
                                            <button type="button" class="btn btn-label-dark">Type: @if($test->type == 0) {{ 'Pre Test' }}  @else {{ 'Post Test' }} @endif</button>
                                        </div>
                                        <p class="p-3">
                                            ( RULES HERE ) 
                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget1 kt-widget1--fit mt-3">
                            <div class="kt-widget1__item">
                                <div class="kt-widget1__info">
                                    <h3 class="kt-widget1__title">Test Date</h3>
                                    <span class="kt-widget1__desc">{{ date('jS M, Y g:i a', strtotime($test->test_start)) }}</span>
                                </div>
                                <span class="kt-widget1__number kt-font-brand" id="timer"></span>
                                {{-- <div id="clockdiv">
                                    <div>
                                        <span class="days"></span>
                                        <div class="smalltext">Days</div>
                                    </div>
                                    <div>
                                        <span class="hours"></span>
                                        <div class="smalltext">Hours</div>
                                    </div>
                                    <div>
                                        <span class="minutes"></span>
                                        <div class="smalltext">Minutes</div>
                                    </div>
                                    <div>
                                        <span class="seconds"></span>
                                        <div class="smalltext">Seconds</div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        #clockdiv{
            font-family: sans-serif;
            color: #fff;
            display: inline-block;
            font-weight: 100;
            text-align: center;
            font-size: 30px;
        }

        #clockdiv > div{
            padding: 10px;
            border-radius: 3px;
            background: #00BF96;
            display: inline-block;
        }

        #clockdiv div > span{
            padding: 15px;
            border-radius: 3px;
            background: #00816A;
            display: inline-block;
        }

        .smalltext{
            padding-top: 5px;
            font-size: 16px;
        }</style>    
@endpush

@push('scripts')
    <script src="{{ global_asset('js/server-date.js') }}"></script>
    <script>
        // Set the date we're counting down to
        var countDownDate = new Date('{{ $test->test_start }}').getTime();
        var expiryDate = new Date('{{ $endtime }}').getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date(ServerDate.now()).getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            var expiryDistance = expiryDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            document.getElementById("timer").innerHTML = days + "d " + hours + "h "
            + minutes + "m " + seconds + "s ";
            var is_finished='{{ $is_finished }}'
            // If the count down is finished, write some text
            if (distance < 0 && is_finished!=11) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = `<a href="/test-start/{{ $test->id }}" class="btn btn-brand">Start Test</a>`;
            }
            
            if (expiryDistance < 0 || is_finished==11) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = `<span class="badge badge-danger">Test Finished</span>`;
            }
        }, 1000);
    </script>    
@endpush