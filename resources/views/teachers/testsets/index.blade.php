@extends('layouts.main')
@section('title','Tests | '. config("app.name"))
@section('assigned-courses','kt-menu__item--open')
@section('assigned-courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Tests"),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' => '/assigned-courses/'.$lesson['course']['id'],
			],
			
            [
                'name' => $lesson['title'],
                'url' => '/lessons/'.$lesson['id'],
            ],
            [
                'name' => 'Tests',
                'url' => '/tests/'.$lesson['id'],
            ],

            [
                'name' => $testset->test->title,
                'url' => '/tests/'.$testset->test->id.'/view',
            ],
            [
                'name' => $testset->title,
                'url' => url()->current(),
            ],
        ]
    ])
{{-- <div>
    <form class="kt-form" id="upload-pdf-form" enctype="multipart/form-data">
        @csrf
        <form action="/file-upload"
        class="dropzone"
        id="my-awesome-dropzone"></form>
        <div class="kt-form__control">
            <label>Image files:</label>
            <form action="" class="dropzone dropzone-default dropzone-success" method="POST" enctype="multipart/form-data" id="kt_dropzone">
                @csrf
                <div class="dropzone-msg dz-message needsclick">
                    <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                    <span class="dropzone-msg-desc">Only image, pdf and psd files are allowed for upload</span>
                </div>
            </form>
            <div class="progress mt-3" style="display: none">
                <input type="hidden" name="option_file_name[]">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
    </form>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
        <button id="upload-pdf-submit" form="upload-pdf-form" type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
    </div>
</div> --}}

<div class="modal fade bd-example-modal-lg" id="question_modal" role="dialog" aria-labelledby="question_modal_header" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="question_modal_header">Add Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="kt-form">
                    <div class="kt-portlet__body p-0 pb-4">
                        <div class="form-group">
                            <label>{{ __("Type") }}</label>
                            <select name="type" class="form-control" id="question-type" required>
                                <option selected disabled value id="blank-option">Select type...</option>
                                
                                <option id="option-pdf"  data-type="upload-pdf" value="0">Upload PDF</option>
                                <option data-type="single-choice" value="1">Single Choice</option>
                                <option data-type="multi-choice" value="2">Multi Choice</option>
                                <option data-type="image-upload" value="3">Image Upload</option>
                                <option data-type="paragraph" value="4">Paragraph</option>

                                <option data-type="text" value="5">Text</option>
                            </select>
                        </div>

                        <div id="render-here"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ $testset->title }}
				</h3>
            </div>
            @if(!$hasPdf)
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        
                        <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" id="add-question-btn" data-toggle="modal" data-target="#question_modal">
                            <i class="la la-plus"></i>
                            {{ __('Add Question') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif
		</div>
        
        <div class="kt-portlet__body">
            <!--begin: Search Form -->
			<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
				<div class="row align-items-center">
					<div class="col-xl-8 order-2 order-xl-1">
						<div class="row align-items-center">
							<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
								<div class="kt-input-icon kt-input-icon--left">
									<input type="text" class="form-control" placeholder="{{ __('Search') }}..." id="generalSearch">
									<span class="kt-input-icon__icon kt-input-icon__icon--left">
										<span><i class="la la-search"></i></span>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--end: Search Form -->
        </div>

        <div class="kt-portlet__body kt-portlet__body--fit">

			<!--begin: Datatable -->
			<div class="kt-datatable"></div>
			<!--end: Datatable -->
		</div>
    </div>

</div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    
    <script>
        $('#question-type').selectpicker();

        let options = {
            // datasource definition
            data: { 
                type: 'remote',
                source: {
                    read: {
                        method: 'GET',
                        url: '/api/get-test-questions/{{$testset->id}}',
                        map: function(raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                serverPaging: false,
                serverFiltering: false,
                serverSorting: false,
            },

            // layout definition
            layout: {
                scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                footer: false // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch')
            },

            // columns definition
            columns: [
                {
                    field: 'order',
                    title: '{{ __("Order") }}',
                    sortable: 'asc',
                },
                {
                    field: 'title',
                    title: '{{ __("Question") }}',
                    sortable: false,
                },

                {field:'id',title:'ID',visible:false,width:0},
                {
                    field: 'type',
                    title: '{{ __("Type") }}',
                    template: function(row) {
                        switch (row.type) {
                            case 0:
                                return 'Upload PDF';
                                break;
                                
                            case 1:
                                return 'Single Choice';
                                break;
                                
                            case 2:
                                return 'Multi Choice';
                                break;
                                
                            case 3:
                                return 'Image Upload';
                                break;
                            case 4:
                                return 'Paragraph';
                                break;
                            default:
                                return 'Text';
                                break;
                        }
                    },
                },

                {
                    field: 'marks',
                    title: '{{ __("Marks") }}',
                },
                {
                    field: 'Actions',
                    title: '{{ __("Actions") }}',
                    sortable: false,
                    width: 110,
                    overflow: 'visible',
                    autoHide: false,
                    template: function(data) {
                        return '\
                        <div class="dropdown">\
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
                                <i class="la la-ellipsis-h"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="/test-questions/'+data.id+'/edit"><i class="la la-edit"></i> {{ __("Edit Question") }}</a>\
                                <a class="dropdown-item delete-question" data-question-id="'+data.id+'" href="#"><i class="la la-trash"></i> {{ __("Delete") }}</a>\
                            </div>\
                        </div>\
                    ';
                    },
                }
            ],
        };

        let datatable = $('.kt-datatable').KTDatatable(options);

		$(document).on('click','.delete-question',function(e){
			e.preventDefault();

			var id = $(this).attr('data-question-id');
			var link = "/test-questions/"+id;
            var message = "Are you sure you want to delete this question?";
            header = "Delete Question";

			makeModal(link, message, header, "DELETE");
		});

        $('#add-question-btn').on('click', function() {
            const url = "@php echo url('/api/test-sets/has-questions/'.$testset->id) @endphp";
            
            $.ajax({
                url:url,
                dataType:'JSON',
                type:'GET',
                error:function(){
                    toastr.error('Server error. Please try again.');
                    $('#question_modal').modal('hide');
                },
                success:function(data){
                    console.log(data.status);
                    if(data.status){
                        $('#option-pdf').attr('disabled',true);
                    }

                    $('#question-type').val('').selectpicker('refresh');
                    $('.question-type-div').hide();
                }
            });
        });

        $('#question-type').on('change', function() {
            const type = $('option:selected', this).attr('data-type');
            

            switch (type) {
                case 'single-choice':
                    $('#render-here').html('').html(`<x-single-choice></x-single-choice>`);

                    if($('input[name="marks"]').length>0){
                        
                        const url = "@php echo url('/api/test-questions/get-mark/'.$testset->id) @endphp";
                        $.ajax({
                            url:url,
                            dataType:'JSON',
                            type:'GET',
                            success:function(data){
                                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
                            }
                        })
                    }
                    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );

                    $('select[name="option_type"]').selectpicker();

                    $('select[name="option_type"]').on('change', function() {
                        if( $(this).val() == "text" ) {
                            $('#option-image').hide();
                            $('#option-text').show();
                        }
                        else {
                            $('#option-text').hide();
                            $('#option-image').show();
                        }
                    });

                    $('#kt_repeater_1,#kt_repeater_2').repeater({
                        initEmpty: false,
                        isFirstItemUndeletable: true,
                        defaultValues: {
                            'text-input': 'foo',
                        },
                        show: function () {
                            $(this).slideDown();

                            $('.c-text').on('change', function() {
                                let checked = $(this);
                                if(checked.is(':checked')) {
                                    $('.c-text').prop('checked', false);
                                    checked.prop('checked', true);
                                }
                            });

                            $('.c-image').on('change', function() {
                                let checked = $(this);
                                if(checked.is(':checked')) {
                                    $('.c-image').prop('checked', false);
                                    checked.prop('checked', true);
                                }
                            });
                        },
                        hide: function (deleteElement) {
                            removeFile ($(this).find('input[type="file"]').attr('data-type'), $(this).find('input[type="hidden"]').val() );
                            $(this).slideUp(deleteElement);
                        }   
                    });

                    $('.number-type').on('focusout', function() {
                        const enteredValue = $(this).val();

                        if ( enteredValue !== '' && enteredValue <= 0 ) {
                            toastr.error('The number field must not be less than 1.');
                            $(this).val('');
                        }
                    });

                    // function removeFile(ty, fn) {
                    //     let folder = 'question_options';
                    //     $.ajax({
                    //         url: '/topics/removeFile/'+folder+'/'+fn,
                    //         type: 'GET'
                    //     });

                    //     return true;
                    // }

                    // $(document).on('change','.custom-file-rem',function(e){

                    //     var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>{{ __("Remove") }}</button>`
                    //     var elem=$(this);
                    //     if(elem.parent().parent().find('.custom-remove-button').length==0){
                    //         elem.parent().parent().append(html);
                    //     }
                    // });

                    // $(document).on('click','.custom-remove-button',function(e){
                    //     var elem=$(this);
                    //     var customFile = elem.parent().find('.custom-file');
                    //     var hiddenFilename = customFile.siblings('div.progress').find('input');
                    //     var selectedInput = customFile.find('input');

                    //     removeFile( selectedInput.attr('data-type'), hiddenFilename.val() );
                    //     selectedInput.val('');
                    //     customFile.find('label').html('{{ __("Choose file") }}');
                    //     hiddenFilename.val('');
                    //     customFile.siblings('div.progress').hide();
                    //     $(this).remove();
                    // });

                    // $(document).on('change', 'input[type="file"]', function() {
                    //     if($(this)[0].files.length === 1) {

                    //         var type = $(this).attr('data-type');
                    //         var formData = new FormData();
                    //         var input = $(this)[0].files[0];

                    //         formData.append('selectedFile', input);
                    //         formData.append('type', type);
                    //         const url = "@php echo url('/test-questions/storeFile') @endphp";

                    //         var progressDiv = $(this).parent().siblings('div.progress');

                    //         $.ajax({
                    //             xhr: function() {
                    //                 var xhr = new window.XMLHttpRequest();
                    //                 xhr.upload.addEventListener("progress", function(evt) {
                    //                     if (evt.lengthComputable) {
                    //                         var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                    //                         progressDiv.find('.progress-bar').width(percentComplete + '%');
                    //                         progressDiv.find('.progress-bar').html(percentComplete+'%');
                    //                     }
                    //                 }, false);
                    //                 return xhr;
                    //             },
                    //             url: url,
                    //             headers: {
                    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    //             },
                    //             type: 'POST',
                    //             data: formData,
                    //             dataType: 'json',
                    //             cache: false,
                    //             contentType: false,
                    //             processData: false,
                    //             beforeSend: function() {
                    //                 progressDiv.find('.progress-bar').width('0%');
                    //                 progressDiv.find('.progress-bar').html('0%');
                    //                 progressDiv.show();
                    //             },
                    //             success: function(res) {
                    //                 if(res.result == 'success'){
                    //                     progressDiv.find('input').val(res.filename);
                    //                 }
                    //                 else {
                    //                     toastr.error('Failed to upload. Please try again.')
                    //                 }
                    //             }
                    //         });
                    //     }
                    // });

                    $('#single-choice-form').on('submit', function(e) {
                        e.preventDefault();

                        if ($('select[name="option_type"]').val() == 'text') {
                            let atLeastOneIsChecked = false;
                            $('.c-text').each(function () {
                                if ($(this).is(':checked')) {
                                    atLeastOneIsChecked = true;
                                    return false;
                                }
                            });

                            if (!atLeastOneIsChecked) {
                                toastr.error('Choose the correct answer before submitting the form.');
                                return false;
                            }
                        }
                        else {
                            let atLeastOneIsChecked = false;
                            $('.c-image').each(function () {
                                if ($(this).is(':checked')) {
                                    atLeastOneIsChecked = true;
                                    return false;
                                }
                            });

                            if (!atLeastOneIsChecked) {
                                toastr.error('Choose the correct answer before submitting the form.');
                                return false;
                            }
                        }

                        var formData = new FormData($(this)[0]);
                        formData.append('question', CKEDITOR.instances.editor1.getData());
                        formData.append('set_id', {{$testset->id}});

                        const url = "@php echo url('/test-questions/store-single-choice') @endphp";

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
                            beforeSend: function() {
                                $('#keen-spinner').show();
                                $('#main-body-div').css({ opacity: 0.5 });
                            },
                            success: function(res) {
                                if(res.result == 'success'){
                                    $('#question_modal').modal('hide');
                                    datatable.reload();
                                    $('#render-here').html('');
                                    $('#question-type').val('').selectpicker('refresh');
                                    toastr.success('Question saved.');
                                }
                                else {
                                    toastr.error(res.message);
                                }

                                $('#keen-spinner').hide();
                                $('#main-body-div').css({ opacity: 1 });
                            }
                        });
                    });

                    break;

                case 'multi-choice':
                    $('#render-here').html('').html(`<x-multi-choice></x-multi-choice>`);
                    if($('input[name="marks"]').length>0){
                        
                        const url = "@php echo url('/api/test-questions/get-mark/'.$testset->id) @endphp";
                        $.ajax({
                            url:url,
                            dataType:'JSON',
                            type:'GET',
                            success:function(data){
                                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
                            }
                        })
                    }
                    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );

                    $('select[name="option_type"]').selectpicker();

                    $('select[name="option_type"]').on('change', function() {
                        if( $(this).val() == "text" ) {
                            $('#option-image').hide();
                            $('#option-text').show();
                        }
                        else {
                            $('#option-text').hide();
                            $('#option-image').show();
                        }
                    });

                    $('#kt_repeater_1,#kt_repeater_2').repeater({
                        initEmpty: false,
                        isFirstItemUndeletable: true,
                        defaultValues: {
                            'text-input': 'foo',
                        },
                        show: function () {
                            $(this).slideDown();

                            $('.c-text').on('change', function() {
                                let checked = $(this);
                                if(checked.is(':checked')) {
                                    $('.c-text').prop('checked', false);
                                    checked.prop('checked', true);
                                }
                            });

                            $('.c-image').on('change', function() {
                                let checked = $(this);
                                if(checked.is(':checked')) {
                                    $('.c-image').prop('checked', false);
                                    checked.prop('checked', true);
                                }
                            });
                        },
                        hide: function (deleteElement) {
                            removeFile ($(this).find('input[type="file"]').attr('data-type'), $(this).find('input[type="hidden"]').val() );
                            $(this).slideUp(deleteElement);
                        }   
                    });

                    $('.number-type').on('focusout', function() {
                        const enteredValue = $(this).val();

                        if ( enteredValue !== '' && enteredValue <= 0 ) {
                            toastr.error('The number field must not be less than 1.');
                            $(this).val('');
                        }
                    });

                    $('#multi-choice-form').on('submit', function(e) {
                        e.preventDefault();

                        var formData = new FormData($(this)[0]);
                        formData.append('question', CKEDITOR.instances.editor1.getData());
                        formData.append('set_id', {{$testset->id}});

                        const url = "@php echo url('/test-questions/store-multi-choice') @endphp";

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
                            beforeSend: function() {
                                $('#keen-spinner').show();
                                $('#main-body-div').css({ opacity: 0.5 });
                            },
                            success: function(res) {
                                if(res.result == 'success'){
                                    $('#question_modal').modal('hide');
                                    datatable.reload();
                                    $('#render-here').html('');
                                    $('#question-type').val('').selectpicker('refresh');
                                    toastr.success('Question saved.');
                                }
                                else {
                                    toastr.error(res.message);
                                }

                                $('#keen-spinner').hide();
                                $('#main-body-div').css({ opacity: 1 });
                            }
                        });
                    });

                    break;
                
                // upload pdf here
                case 'upload-pdf':
                    

                    $('#render-here').html('').html(`<x-upload-pdf></x-upload-pdf>`);
                    // $(document).on('change','.custom-file-rem',function(e){
                    //     var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>{{ __("Remove") }}</button>`
                    //     var elem=$(this);
                    //     if(elem.parent().parent().find('.custom-remove-button').length==0){
                    //         elem.parent().parent().append(html);
                    //     }
                    // });

                    // $(document).on('click','.custom-remove-button',function(e){
                    //     var elem=$(this);
                    //     var customFile = elem.parent().find('.custom-file');
                    //     var selectedInput = customFile.find('input');

                    //     selectedInput.val('');

                    //     customFile.find('label').html('{{ __("Choose file") }}');

                    //     $(this).remove();
                    // });


                    $('#upload-pdf-form').on('submit', function(e) {
                        console.log($(this));
                        e.preventDefault();
                        var form=$(this);
       
                        form.validate({
                            focusInvalid: true,
                            rules: {
                                pdf: {
                                    required: true,
                                },
                            }
                        });
                        if (!form.valid()) {
                            e.preventDefault();
                            return;
                        }
                        console.log(form);
                        var formData = new FormData($(this)[0]);
                        formData.append('set_id', {{$testset->id}});
                        
                        const url = "@php echo url('/test-questions/store-upload-pdf') @endphp";

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
                            beforeSend: function() {
                                $('#keen-spinner').show();
                                $('#main-body-div').css({ opacity: 0.5 });
                            },
                            success: function(res) {
                                console.log(res);
                                if(res.result == 'success'){
                                    $('#question_modal').modal('hide');
                                    datatable.reload();
                                    $('#add-question-btn').remove();
                                    $('#render-here').html('');
                                    toastr.success('Question saved.');
                                }
                                else {
                                    toastr.error('Something went worng. Please try again.');
                                }

                                $('#keen-spinner').hide();
                                $('#main-body-div').css({ opacity: 1 });
                            }
                        });
                        
                    });    
                    break;
                case 'image-upload':

                    $('#render-here').html('').html(`<x-image-upload></x-image-upload>`);
                    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );
                    CKEDITOR.replace( 'uploadImageNote' ,{ removeButtons: 'Table' } );

                    if($('input[name="marks"]').length>0){
                        
                        const url = "@php echo url('/api/test-questions/get-mark/'.$testset->id) @endphp";
                        $.ajax({
                            url:url,
                            dataType:'JSON',
                            type:'GET',
                            success:function(data){
                                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
                            }
                        })
                    }

                    var id = '#kt_dropzone';

                    var hasError=Array();
                    var images=Array();
                    var i =0;
                    var _dropZone=new Dropzone(id,{
                        url: "@php echo url('/test-questions/upload-dropzone') @endphp", // Set the url for your upload script location
                        paramName: "file", // The name that will be used to transfer the file
                        maxFiles: 10,
                        maxFilesize: {{ config('app.max_file_size') }}, // MB
                        addRemoveLinks: true,
                        // autoProcessQueue: false,
                        acceptedFiles: "image/*",
                        init:function(){
                            this.on('success',function(file,serverfileName){
                                file.serverFn=serverfileName.filename;
                                images.push(serverfileName.filename);
                                
                            })
                        }
                        
                    });
                    _dropZone.on("addedfile", function(file) {
                        var err=$('#drop_zone_error');
                        if(err.length>0){
                            err.remove();
                        }
                    });
                    // _dropZone.on("complete", function(file) {
                    // console.log(image);
                    // });

                    _dropZone.on("error", function(file) {
                        hasError.push(file.name)
                    }); 
                    _dropZone.on("removedfile", function(file) {
                        var img=null;
                        var err=$('#drop_zone_error');
                        if(err.length>0){
                            err.remove();
                        }
                        hasError.splice( $.inArray(file.name, hasError), 1 );
                        $.each(images, function(i,d){
                            var img=d;
                            if(d==file.serverFn){
                                removeFile('attachments',img);
                                images.pop(i);
                                
                            }
                        })
                        

                    });

                    _dropZone.on('sending',function(file,xhr, formData){
                        formData.append('type','attachments');
                    })

                    $(document).on('submit','#image-upload-form', function(e) {
                        e.preventDefault();
                        var element=$('#kt_dropzone')
                        var err=$('#drop_zone_error');
                        if(hasError.length>0){
                            
                            if(err.length>0){
                                err.remove();
                            }
                            element.append(`<div id="drop_zone_error" style="display:block" class="error invalid-feedback">Invalid files detected. Please check the error message.</div>`)
                            
                        }else if(images.length<1){
                            if(err.length>0){
                                err.remove();
                            }
                            element.append(`<div id="drop_zone_error" style="display:block" class="error invalid-feedback">At least one file is required.</div>`)
                        }
                        else{

                            var form=$(this);

        
                            var formData = new FormData($(this)[0]);
                            
                            formData.append('attachments', images);
                            formData.append('set_id', {{$testset->id}});
                            console.log(formData);
                            const url = "@php echo url('/test-questions/store-image-upload') @endphp";

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
                                beforeSend: function() {
                                    $('#keen-spinner').show();
                                    $('#main-body-div').css({ opacity: 0.5 });
                                },
                                success: function(res) {
                                    if(res.result == 'success'){
                                        $('#question_modal').modal('hide');
                                        datatable.reload();

                                        $('#render-here').html('');
                                        toastr.success('Question saved.');
                                    }
                                    else {
                                        toastr.error('Something went worng. Please try again.');
                                    }

                                    $('#keen-spinner').hide();
                                    $('#main-body-div').css({ opacity: 1 });
                                }
                            });
                        }
                        
                    })
                   
                    break;
                case 'paragraph':

                    $('#render-here').html('').html(`<x-paragraph></x-paragraph>`);

                    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );
                    CKEDITOR.replace( 'formNote' );    
                    if($('input[name="marks"]').length>0){
                        
                        const url = "@php echo url('/api/test-questions/get-mark/'.$testset->id) @endphp";
                        $.ajax({
                            url:url,
                            dataType:'JSON',
                            type:'GET',
                            success:function(data){
                                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
                            }
                        })
                    }
                    $('#paragraph-form').on('submit', function(e) {
                        e.preventDefault();

                        var formData = new FormData($(this)[0]);

                        formData.append('question', CKEDITOR.instances.editor1.getData());

                        formData.append('note', CKEDITOR.instances.formNote.getData());
                        formData.append('set_id', {{$testset->id}});

                        const url = "@php echo url('/test-questions/store-paragraph') @endphp";

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
                            beforeSend: function() {
                                $('#keen-spinner').show();
                                $('#main-body-div').css({ opacity: 0.5 });
                            },
                            success: function(res) {
                                if(res.result == 'success'){
                                    $('#question_modal').modal('hide');
                                    datatable.reload();
                                    $('#render-here').html('');
                                    $('#question-type').val('').selectpicker('refresh');
                                    toastr.success('Question saved.');
                                }
                                else {
                                    toastr.error(res.message);
                                }

                                $('#keen-spinner').hide();
                                $('#main-body-div').css({ opacity: 1 });
                            }
                        });
                    });
                    break;
                case 'text':

                    $('#render-here').html('').html(`<x-text></x-text>`);
                    CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );  

                    if($('input[name="marks"]').length>0){
                        
                        const url = "@php echo url('/api/test-questions/get-mark/'.$testset->id) @endphp";
                        $.ajax({
                            url:url,
                            dataType:'JSON',
                            type:'GET',
                            success:function(data){
                                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
                            }
                        })
                    }
                    
                    $('#text-form').on('submit', function(e) {
                        e.preventDefault();

                        var formData = new FormData($(this)[0]);

                        formData.append('question', CKEDITOR.instances.editor1.getData());

                        formData.append('set_id', {{$testset->id}});

                        const url = "@php echo url('/test-questions/store-text') @endphp";

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
                            beforeSend: function() {
                                $('#keen-spinner').show();
                                $('#main-body-div').css({ opacity: 0.5 });
                            },
                            success: function(res) {
                                if(res.result == 'success'){
                                    $('#question_modal').modal('hide');
                                    datatable.reload();
                                    $('#render-here').html('');
                                    $('#question-type').val('').selectpicker('refresh');
                                    toastr.success('Question saved.');
                                }
                                else {
                                    toastr.error(res.message);
                                }

                                $('#keen-spinner').hide();
                                $('#main-body-div').css({ opacity: 1 });
                            }
                        });
                    });
                    break;
                default:

                    break;
            }

        });


        // 
        function removeFile(ty, fn) {
            $.ajax({
                url: '/test-questions/removeFile/'+ty+'/'+fn,
                type: 'GET'
            });

            return true;
        }

        $(document).on('change','.custom-file-rem',function(e){

            var html=`<button type="button" class="btn btn-danger btn-elevate btn-pill btn-sm mt-2 custom-remove-button"><i class="fa fa-times"></i>{{ __("Remove") }}</button>`
            var elem=$(this);
            if($(this).parent().parent().find('.progress input').val()){
                removeFile(
                    $(this).parent().parent().find('.custom-file input').data('type'),
                    $(this).parent().parent().find('.progress input').val()
                )
            }
            if(elem.parent().parent().find('.custom-remove-button').length==0){
                elem.parent().parent().append(html);
            }
        });

        $(document).on('click','.custom-remove-button',function(e){
            var elem=$(this);
            var customFile = elem.parent().find('.custom-file');
            var hiddenFilename = customFile.siblings('div.progress').find('input');
            var selectedInput = customFile.find('input');

            removeFile( selectedInput.attr('data-type'), hiddenFilename.val() );
            selectedInput.val('');
            customFile.find('label').html('{{ __("Choose file") }}');
            hiddenFilename.val('');
            customFile.siblings('div.progress').hide();
            $(this).remove();
        });

        // order check
        $(document).on('focus','input[name="order"]',function(e){
            var elem=$(this)
            remErrCustom(elem)
        })
        $(document).on('focusout','input[name="order"]',function(e){
            var elem=$(this);
            var order=elem.val();
            var set_id={{ $testset->id }};
            $.ajax({
                url:'/api/test-questions/order-exists/',
                dataType:'JSON',
                type:'GET',
                data:{
                    order:order,
                    set_id:set_id,
                },
                success:function(data){
                    if(data.status){
                        makeErrCustom(elem,'Order already taken')
                    }
                }
            })
            
        })

        // marking 
        $(document).on('focus','input[name="marks"]',function(e){
            var elem=$(this)
            remErrCustom(elem)
        })
        $(document).on('focusout','input[name="marks"]',function(e){
            var elem=$(this);
            var marks=elem.val();
            var set_id={{ $testset->id }};
            $.ajax({
                url:'/api/test-questions/mark-valid/',
                dataType:'JSON',
                type:'GET',
                data:{
                    marks:marks,
                    set_id:set_id,
                },
                success:function(data){
                    if(data.status){
                        makeErrCustom(elem,'Marks given is greater than remaining marks for this set.');
                    }
                }
            })
            
        })
       


        $(document).on('change', '.modal input[type="file"]', function() {
            if($(this)[0].files.length === 1) {

                var type = $(this).attr('data-type');
                var formData = new FormData();
                var input = $(this)[0].files[0];

                formData.append('selectedFile', input);
                formData.append('type', type);
                const url = "@php echo url('/test-questions/storeFile') @endphp";

                var progressDiv = $(this).parent().siblings('div.progress');
                console.log(input, formData, type)
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                progressDiv.find('.progress-bar').width(percentComplete + '%');
                                progressDiv.find('.progress-bar').html(percentComplete+'%');
                            }
                        }, false);
                        return xhr;
                    },
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data:formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        progressDiv.find('.progress-bar').width('0%');
                        progressDiv.find('.progress-bar').html('0%');
                        progressDiv.show();
                    },
                    success: function(res) {
                        if(res.result == 'success'){
                            progressDiv.find('input').val(res.filename);
                        }
                        else {
                            toastr.error('Failed to upload. Please try again.')
                        }
                    }
                });
            }
        });

        $(document).on('hide.bs.modal','#question_modal', function () {
            $('#render-here').html('');
            $('#question-type').val('').selectpicker('refresh');   
        });
    </script>
@endpush
@push('styles')
<style>
.dz-error-message{
    top: 144px !important;
}
</style>
@endpush