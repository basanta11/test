@extends('layouts.main')
@section('title','Dashboard | '. config("app.name"))
@section('dashboard','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
    'breadTitle' => __('Dashboard'),
    'crumbs' => [
        [
            'name' => __('Dashboard'),
            'url' => '/app'
        ],
    ]
])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    
	@include('layouts.partials.flash-message')

    <!--Begin::Dashboard 3-->

    <!--Begin::Row-->
    <div class="row">
       
        <div class="col-xl-12 col-lg-12 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Blog-->
            <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                <div class="kt-portlet__body">

                    <!--begin::Form-->
                    <form class="kt-form kt-form--label-right" id="test-form" enctype="multipart/form-data">
                        <div class="kt-portlet__body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Video:</label>
                                <div class="col-lg-6">
                                    <input data-type="video" type="file" id="video-file" name="video">
                                    <div class="progress mt-3" id="main-video-progress" style="display: none">
                                        <div id="video-progress" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Image:</label>
                                <div class="col-lg-6">
                                    <input data-type="image" type="file" id="image-file" name="image">
                                    <div class="progress mt-3" id="main-image-progress" style="display: none">
                                        <div id="image-progress" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="reset" class="btn btn-brand">Submit</button>
                                    <a href="/app" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!--end::Form-->

                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script>
        $('input[type="file"]').on('change', function() {
            var type = $(this).attr('data-type');
            var formData = new FormData();
            var input = $(this)[0].files[0];

            formData.append('selectedFile', input);
            formData.append('type', type);
            const url = "@php echo url('/storeFile') @endphp";

            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round(((evt.loaded / evt.total) * 100);
                            $("#"+type+"-progress").width(percentComplete + '%');
                            $("#"+type+"-progress").html(percentComplete+'%');
                        }
                    }, false);
                    return xhr;
                },
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
                beforeSend: function() {
                    $("#main-"+type+"-progress").show();
                    $("#"+type+"-progress").width('0%');
                },
                success: function(res) {
                    // if(res.result == 'success'){
                    //     $('#uploadStatus').html('<p style="color:#28A74B;">File has uploaded successfully!</p>');
                    // }
                    // else {
                    //     $('#uploadStatus').html('<p style="color:#EA4335;">Please select a valid file to upload.</p>');
                    // }
                }
            });
        });
    </script>
@endpush
