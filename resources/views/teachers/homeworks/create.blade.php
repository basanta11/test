@extends('layouts.main')
@section('title','Homeworks | '. config("app.name"))
@section('homeworks','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Homeworks"),
        'crumbs' => [
            [
                'name'=>__('Homeworks'),
                'url'=> '/homeworks',
            ],
            [
                'name'=>__('Create Homework'),
                'url'=> url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Create Homework") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" id="homework_form" method="POST" action="/homeworks">
                @csrf
                <input type="hidden" name="attachments" id="attachments" />
                <div class="kt-portlet__body p-0 pb-4">
                    
                    <div class="form-group row">
                        <div class="col-sm-6">

                            <label>{{ __("Course") }}</label>
                            <select name="course_id" class="form-control" >
                                <option selected disabled>Select course...</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">

                            <label>{{ __("Section") }}</label>
                            <select name="section_id[]" multiple="" class="form-control" required>
                            </select>
                        
                        </div>
                    </div>
                    <div class="form-group row" >
                      
                        <div class="col-sm-6">
                        
                            <label>{{ __("Full Marks") }}</label>
                            <input type="number" name="full_marks"  required min="1" class="form-control" placeholder="{{ __("Enter full marks") }}">
                        </div>
                        <div class="col-lg-1">
                            <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                                <label class="mb-2">
                                    {{ __("Due Date") }}?
                                    <input type="checkbox" id="due-date-check">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                        <div class="col-lg-5" id="due-date-div">
                            <label> {{ __("Due Date") }}</label>
                            <input type="text" id="kt_datepicker_1" class="form-control datepicker-mem" placeholder="{{ __("Enter due date") }}" name="due_date_time" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input class="form-control" type="text" name="title"  required class="form-control" placeholder="{{ __("Enter title") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Question") }}</label>
                        <textarea type="text" name="question" id="question" required class="form-control" placeholder="{{ __("Enter question") }}"></textarea>
                    </div>
                </div>
            </form>
            <label>{{ __("Attachments") }}:</label>
            <form action="" class="dropzone dropzone-default dz-clickable" method="POST" enctype="multipart/form-data" id="kt_dropzone">
                @csrf
                
                <div class="dropzone-msg dz-message needsclick">
                    <h3 class="dropzone-msg-title">{{ __("Drop files here or click to upload") }}.</h3>
                    {{-- <span class="dropzone-msg-desc">Only image files are allowed for upload</span> --}}
                </div>
            </form>
            <div class="kt-portlet__foot px-0 pt-4">
                <div class="kt-form__actions">
                    <button form="homework_form"  type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                    <a href="/homeworks"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                </div>
            </div>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
	// $(document).ready(function(){

        $('#due-date-div').hide();
        $('#due-date-check').on('change', function() {
            if ($(this).is(':checked')) {
	            $('#due-date-div').show();
            }
            else {
	            $('#due-date-div').hide();
            }
        });
        function removeFile(ty, fn,id=null) {
                var url= id == null ? '/homeworks/removeFile/'+ty+'/'+fn : '/homeworks/removeFileAndAttachment/'+ty+'/'+fn+'/'+id;
                $.ajax({
                    url: url,
                    type: 'GET'
                });

                return true;
            }

            CKEDITOR.replace( 'question' ,{ removeButtons: 'Table' } );
            
            var id = '#kt_dropzone';
            var hasError=Array();
            var images=Array();
            var i =0;
            var _dropZone=new Dropzone(id,{
                url: "@php echo url('/homeworks/upload-dropzone') @endphp", // Set the url for your upload script location
                paramName: "file", // The name that will be used to transfer the file
                maxFiles: 10,
                maxFilesize: {{ config('app.max_file_size') }}, // MB
                addRemoveLinks: true,
                // autoProcessQueue: false,
                acceptedFiles: "image/*, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" ,
                init:function(){
                    var elem=this
                    
                    this.on('success',function(file,serverfileName){
                        file.serverFn=serverfileName.filename;
                        images.push(serverfileName.filename);
                        
                    })
                },
                
            });
            _dropZone.on("addedfile", function(file) {
                var err=$('#drop_zone_error');
                if(err.length>0){
                    err.remove();
                }
                var thumbnail = $('.dropzone .dz-preview.dz-file-preview .dz-image:last img');

                switch (file.type) {
                    case 'application/pdf':
                        thumbnail.attr('src', '{{ global_asset("assets/media/files/png/pdf.png") }}');
                        break;
                    case 'application/msword':
                        thumbnail.attr('src', '{{ global_asset("assets/media/files/png/word.png") }}');
                        break;
 case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                        thumbnail.attr('src', '{{ global_asset("assets/media/files/png/word.png") }}');
                        break;
 
                    case 'application/vnd.ms-powerpoint':
                        thumbnail.attr('src', '{{ global_asset("assets/media/files/png/powerpoint.png") }}');
                        break;
                    case 'application/vnd.ms-excel':
                        thumbnail.attr('src', '{{ global_asset("assets/media/files/png/excel.png") }}');
                        break;
                    case 'text/plain':
                        thumbnail.attr('src', '{{ global_asset("assets/media/files/png/txt.png") }}');
                        break;
                
                    case 'image/*':
                        break;
                    default:
                        thumbnail.attr('src', '{{ global_asset("assets/media/files/png/document.png") }}');
                        break;
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
                    console.log(d);
                    var img=d;
                    if(d==file.serverFn){
                        if(file.id){
                            removeFile('attachments',img,file.id);
                        }else{
                            removeFile('attachments',img);
                            
                        }
                        images.pop(i);
                        
                    }
                })
                

            });

            _dropZone.on('sending',function(file,xhr, formData){
                formData.append('type','attachments');
            })

        $('#kt_datepicker_1').datetimepicker({
            format: 'yyyy-mm-dd hh:ii',
            autoclose: true,
            startDate: '+0d'
        });

        $('select[name="course_id"]').selectpicker('refresh');
        $('select[name="section_id[]"]').select2({
            placeholder: "Select a course first...",

        });
        

        $('select[name="course_id"]').on('change',function(e){
            var s= $('select[name="section_id[]"]')
            var id=$(this).val();
            $.ajax({
                url:'/homeworks/'+id+'/get-sections',
                type:'GET',
                dataType:'JSON',
                success:function(data){
                    console.log(data.data)
                    if (data.data !== []) {
                        s.html('');
                        $.each(data.data, function( index, value ) {
                            console.log(value.section)
                            let html = `<option value="${value.section.id}">${value.section.title}</option>`;

                                s.append(html);
                            });
                            s.select2({
                                placeholder:"Please select sections",
                                val: ""
                            });
                        }
                        else {
                            s.html('');
                        }
                }
            })
        });
    

        $('.kt-form').on('submit',function(e){ 
            e.preventDefault();
            var form=$(this);
            KTApp.blockPage();
            $.validator.addMethod("check_ck_add_method",
                function (value, element) {
                    return check_ck_editor();
                });

            function check_ck_editor() {
                if (CKEDITOR.instances.question.getData() == '') {
                    return false;
                }
                else {
                    $("#error_check_editor").empty();
                    return true;
                }
            }
            form.validate({
                focusInvalid: true,
                rules: {
                    title:{
                        required:true,
                    },
                    question: {
                        check_ck_add_method: true
                    },
                    full_marks: {
                        required: true,
                        number: true,
                    },
                    'section_id[]': {
                        required: true
                    },
                } ,
                messages:
                    {

                    question:{
                        check_ck_add_method:"Please enter Text",


                    }
                }
            });
            
            if (!form.valid()) {
                KTApp.unblockPage();
                e.preventDefault();
                return false;
            }
            if(hasError.length>0){
                e.preventDefault();
                
                KTApp.unblockPage();
                var element=$('#kt_dropzone')
                var err=$('#drop_zone_error');
                if(err.length>0){
                    err.remove();
                }
                element.append(`<div id="drop_zone_error" style="display:block" class="error invalid-feedback">Invalid files detected. Please check the error message.</div>`)
                
                
                return false;
            
            }else{
                $('#attachments').val(images);

                $(this).unbind('submit').submit();
                return true;

            }

            return true;


        })
    // })
</script>
@endpush
@push('styles')
<style>

.dz-message{
  text-align: center;
  font-size: 28px;
}

.dropzone .dz-preview.dz-file-preview .dz-image{
    background:none !important;
    background-color: #fff0 !important;
    background-size: cover !important;
}
.dz-preview .dz-image img{
    transition: .3s;
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
}
.dz-error-message{
    top: 144px !important;
}
.dropzone .dz-preview .dz-details .dz-filename span, .dropzone .dz-preview .dz-details .dz-size span {
    background-color: rgba(255, 255, 255, 0.8) !important;
}

.dropzone .dz-preview:hover .dz-image img {
    -webkit-filter: blur(1px) !important;
    filter: blur(1px) !important;
}
</style>
@endpush