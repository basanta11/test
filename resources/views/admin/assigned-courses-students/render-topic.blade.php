<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-warning course-nav" role="tablist" id="ul-head">
                @if (!empty($videoData['video']))
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#" data-target="#kt_tabs_1_1"><i class="fa fa-file-video"></i>{{ __("Video") }}</a>
                    </li>
                @endif
                @if (!empty($topic->audio))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2"><i class="fa fa-file-audio"></i>{{ __("Audio") }}</a>
                    </li>
                @endif
                @if (!empty($topic->image))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_3"><i class="fa fa-file-image"></i>{{ __("Image") }}</a>
                    </li>
                @endif
                @if (!empty($topic->text))
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_4"><i class="fa fa-file-alt"></i>{{ __("Text") }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="tab-content" id="ul-body">
        @if (!empty($videoData['video']))
            <div class="tab-pane active" id="kt_tabs_1_1" role="tabpanel">
                <div class="video-container">
                    @if ($videoData['videoType'] == 'video')
                        <video id="player" class="video" playsinline controls data-poster="/path/to/poster.jpg">
                            <source src="{{ $videoData['video'] }}" type="video/mp4" />

                            <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
                        </video>
                    @else 
                        <iframe class="video" src="{{ $videoData['video'] }}" frameborder="0" allowfullscreen></iframe>
                    @endif
                </div>
            </div>
        @endif
        @if (!empty($topic->audio))
            <div class="tab-pane" id="kt_tabs_1_2" role="tabpanel">
                <audio class="w-100 p-2" controls src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/audios/' . $topic->audio) }}">Your browser does not support the
                    <code>audio</code> element.</audio>
            </div>
        @endif
        @if (!empty($topic->image))
            <div class="tab-pane" id="kt_tabs_1_3" role="tabpanel">
                <div class="image-container">
                    <img src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/images/' . $topic->image)  }}">
                </div>
            </div>
        @endif
        @if (!empty($topic->text))
            <div class="tab-pane p-3" id="kt_tabs_1_4" role="tabpanel">
                @php echo $topic->text; @endphp
            </div>
        @endif
    </div>
    
</div>
<div class="course-desc">
    <ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-2x nav-tabs-line-success" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#attachments-tab" role="tab">{{ __("Attachments") }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#references-tab" role="tab">{{ __("References") }}</a>
        </li>
    </ul>
    <div class="tab-content p-3">
        <div class="tab-pane active mobile" id="attachments-tab" role="tabpanel">
            @if (!$topic->topic_attachments->isEmpty())
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Download attachments
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="/student/download-all/{{ $topic->id }}" class="btn btn-label-brand btn-bold btn-sm" data-toggle="">
                                Download all
                            </a>
                            
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <!--begin::k-widget4-->
                        <div class="kt-widget4">
                            @foreach ($topic->topic_attachments as $k => $a)

                                @php
                                    switch ($a->type) {
                                        case 'jpeg':
                                            $icon = global_asset('assets/media/files/jpg.svg');
                                            break;

                                        case 'jpg':
                                            $icon = global_asset('assets/media/files/jpg.svg');
                                            break;
                                            
                                        case 'png':
                                            $icon = global_asset('assets/media/files/jpg.svg');
                                            break;

                                        case 'pdf':
                                            $icon = global_asset('assets/media/files/pdf.svg');
                                            break;

                                        case 'zip':
                                            $icon = global_asset('assets/media/files/zip.svg');
                                            break;

                                        case 'doc':
                                            $icon = global_asset('assets/media/files/doc.svg');
                                            break;
                                        
                                        default:
                                            $icon = global_asset('assets/media/files/doc.svg');
                                            break;
                                    }
                                @endphp

                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--icon">
                                        <img src="{{ $icon }}">
                                    </div>
                                    <a href="/student/download/{{ $a->id }}" target="_blank" class="kt-widget4__title">
                                        {{ $a->title }}
                                    </a>
                                    <div class="kt-widget4__tools">
                                        <a href="#" class="btn btn-clean btn-icon btn-sm">
                                            <i class="flaticon2-download-symbol-of-down-arrow-in-a-rectangle"></i>
                                        </a>
                                    </div>
                                </div>

                            @endforeach
                            
                        </div>

                        <!--end::Widget 9-->
                    </div>
                </div>
                
            @else
                <p> N/A </p>
            @endif
        </div>
        <div class="tab-pane" id="references-tab" role="tabpanel">
            @php $links = !empty($topic->reference_links) ? json_decode($topic->reference_links) : null  @endphp
            @if (!empty($links))
                <div class="kt-portlet">
                    <div class="kt-portlet__body">

                        @foreach ($links as $k => $link)
                            <div class="kt-notification kt-notification--fit">
                                <a href="@displaylink($link)" class="kt-notification__item" target="_blank">
                                    <div class="kt-notification__item-icon">
                                        <i class="flaticon2-website kt-font-success"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            {{ $link }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="kt-separator kt-separator--border-dashed"></div>
                        @endforeach

                    </div>
                </div>
            @else
                <p> N/A </p>
            @endif
        </div>
    </div>
</div>

<script>
    $('#ul-head li:first-child a').addClass('active');
    $('#ul-body div:first-child').addClass('active');
</script>