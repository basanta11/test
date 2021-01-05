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
			'name' => __("Virtual Classrooms"),
			'url' => '/meetings',
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
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <span>{{ __("Password") }}: </span>{{ $meeting->token }}
                    </div>
                </div>
            </div>
			
			
		</div>
        <div class="kt-portlet__body">
            <div id="meet-here">
            </div>
        </div>
    </div>
    <form id="video">

    </form>
</div>
@endsection

@push('scripts')
<script src='https://meet.jit.si/external_api.js'></script>
    <script>
        // const domain = 'meet.jit.si';
        var audioStream= null;
        var stream= null;
        var video= null;
        var videoStream= null;
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
                    DEFAULT_REMOTE_DISPLAY_NAME: 'Tinel Member',
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
                // window.location.href = '/meetings';
                stopRecording ();
                
                
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
            }, 10);
            
        })
        // WIP
        // console.log('starting')
        async function startRecording (){
            /* eslint-disable */
            this.videoStream = await navigator.mediaDevices.getDisplayMedia({ audio: true, video: true })
            this.audioStream = await navigator.mediaDevices.getUserMedia({ audio: true })

            const tracks = Array(
                this.videoStream.getVideoTracks()[0],
                this.mergeAudioStreams(this.videoStream, this.audioStream)
            );
            console.log('track', tracks);
            this.stream = new MediaStream(tracks)
            console.log('stream', this.stream)
            this.recorder = new MediaRecorder(this.stream)

            const chunks = []
            this.recorder.ondataavailable = e => chunks.push(e.data)

            this.recorder.onstop = e => {
                const completeBlob = new Blob(chunks, { type: chunks[0].type })
                // this.video = URL.createObjectURL(completeBlob)
                this.video = completeBlob

                this.saveMeeting()
            }

            this.recorder.start()
        }
        function stopRecording () {
            this.recorder.stop()
            this.stream.getVideoTracks()[0].stop()

            // this.showJitsi = false
        }
        function mergeAudioStreams (videoStream, audioStream) {
            const context = new AudioContext()

            // Create a couple of sources
            let source1 = null

            try {
                source1 = context.createMediaStreamSource(videoStream)
            } catch {
                source1 = null
            }

            const source2 = context.createMediaStreamSource(audioStream)
            const destination = context.createMediaStreamDestination()

            const desktopGain = context.createGain()
            const voiceGain = context.createGain()

            desktopGain.gain.value = 0.7
            voiceGain.gain.value = 0.7

            if (source1) {
                // Connect source1
                source1.connect(desktopGain).connect(destination)
            }

            if (source2) {
                // Connect source2
                source2.connect(voiceGain).connect(destination)
            }

            return destination.stream.getAudioTracks()[0]
        }
        
        
        function saveMeeting(){
            var formData = new FormData();

            formData.append('meeting_id',{{ $meeting->id }});
            formData.append('video', this.video);
            const url = "@php echo url('/meetings/upload') @endphp";

            $.ajax({
                
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                
                success: function(res) {
                    console.log(res);
                    toastr.success('Recording saved. Redirecting....');
                    window.location.href = "/meetings";
                }
            });
        }

        setTimeout(() => {
            startRecording();
        }, 200);
    </script>
@endpush
