@extends('layouts.main')
@section('title','Meetings | '. config("app.name"))
@section('meetings','kt-menu__item--open')
@section('meetings','kt-menu__item--open')
@push('styles')
<style>
    .tOoji a{
        display: none !important;
    }
</style>
@endpush
@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Virtual Classrooms"),
	'crumbs' => [
		[
			'name' => __("My Virtual Classrooms"),
			'url' => '/my-meetings',
        ],
        [
            'name'=> __("View Virtual Classroom"),
            'url'=> url()->current(),
        ]
	]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("View Virtual Classroom") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <div id="meet-here">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://meet.jit.si/external_api.js'></script>
    <script>
        // const domain = 'meet.jit.si';
        var jitsi_data=@json($jitsi_data);
        $(document).ready(() => {
            var domain = "meet.jit.si"
            var options = {

                roomName: "Virtual Classroom",
                width: '100%',
                height: 800,
                userInfo: {
                    email: `${jitsi_data.email}`,
                    displayName: `${jitsi_data.name}`
                },
                interfaceConfigOverwrite: {
                    JITSI_WATERMARK_LINK: 'https://jitsi.org',
                    DEFAULT_REMOTE_DISPLAY_NAME: 'TINEL Member',
                    NATIVE_APP_NAME: 'Jitsi Meet',
                    SHOW_BRAND_WATERMARK: false,

                    /**
                    * Decides whether the chrome extension banner should be rendered on the landing page and during the meeting.
                    * If this is set to false, the banner will not be rendered at all. If set to true, the check for extension(s)
                    * being already installed is done before rendering.
                    */
                    SHOW_CHROME_EXTENSION_BANNER: false,

                    SHOW_DEEP_LINKING_IMAGE: false,
                    SHOW_JITSI_WATERMARK: false,
                    SHOW_POWERED_BY: false,
                    SHOW_PROMOTIONAL_CLOSE_PAGE: false,
                    SHOW_WATERMARK_FOR_GUESTS: false,
                    TOOLBAR_BUTTONS: [
                        'camera', 'chat', 'desktop', 'fullscreen', 'hangup', 'microphone', 'raisehand', 'settings', 'tileview'
                    ]
                },
            parentNode: document.querySelector('#meet-here')
            }

            var api = new JitsiMeetExternalAPI(domain, options);
            api.on('readyToClose', () => {
                window.location.href = '/my-meetings';
                
            });
            
            // api.executeCommand('participantRoleChanged', 'partici')
            api.executeCommand('avatarUrl', `${jitsi_data.image}`);
               
            setTimeout(() => {
            
                api.addEventListener('participantRoleChanged', function(event) {
                    api.executeCommand('password', jitsi_data.token);
                });
                // join a protected channel
                api.on('passwordRequired', function ()
                {
                    api.executeCommand('password', jitsi_data.token);
                });
            }, 10)
        })
    </script>
@endpush
