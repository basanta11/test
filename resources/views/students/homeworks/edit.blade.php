@extends('layouts.main')
@section('title','Homeworks | '. config("app.name"))
@section('homeworks','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Homeworks"),
        'crumbs' => [
            [
                'name'=>__('Homeworks'),
                'url'=> '/my-homeworks',
            ],
            [
                'name'=>__('Edit Homework'),
                'url'=> url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-list-2"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					{{ __("Homeworks") }}
				</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
             
            </div>
			
		</div>
		<div class="kt-portlet kt-portlet--height-fluid">
			<div class="kt-portlet__body">
				<div class="kt-widget kt-widget--user-profile-3">
					<div class="kt-widget__top">
						<div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
							JM
						</div>
						<div class="kt-widget__content pl-0">
							<div class="kt-widget__head">
								<a href="#" class="kt-widget__username">
									<label>{{ __("Course") }}: </label>
									{{ $homework['course']['title'] }}
								</a>
							</div>
							<div class="kt-widget__head">
								<a href="#" class="kt-widget__username">
									<label>Section: </label>
									{{ $homework['homework_section'][0]['section']['title'] }}
								</a>
							</div>
							<div class="kt-widget__head">
								<a href="#" class="kt-widget__username">
									<label>Due: </label>
									{{ $homework->due_date_time ? date('jS M, Y g:i a', strtotime($homework->due_date_time)) : 'N/A' }}
								</a>
							</div>
							
						</div>
						
					</div>

				</div>
			</div>
		<div class="kt-portlet__body">
			<div class="kt-portlet__head d-block p-0">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title pb-2">
						{{ __("Homework") }}
					</h3>
                </div>
                <div class="form-group mb-2">
                    <label>{{ __("Title") }}</label>
                    <input class="form-control" readonly value="{{ $homework->title }}">
                </div>
                <div class="form-group">
                    <label>{{ __("Question") }}</label>
                    <textarea id="title" readonly>{{ $homework->question }}</textarea>
                </div>
			</div>
			<div class="tab-content py-3 px-0">
				<div class="tab-pane active mobile" id="attachments-tab" role="tabpanel">
					@if (!$attachments->isEmpty())
						<div class="kt-portlet kt-portlet--height-fluid mb-0">
							<div class="kt-portlet__head p-0">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">
										{{ __("Download attachments") }}
									</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
									<a href="/my-homeworks/download-all/{{ $homework->id }}" class="btn btn-label-brand btn-bold btn-sm" data-toggle="">
										{{ __("Download all") }}
									</a>
									
								</div>
							</div>
							<div class="kt-portlet__body px-0">
		
								<!--begin::k-widget4-->
								<div class="kt-widget4">
									@foreach ($attachments as $k => $a)
		
		
										<div class="kt-widget4__item">
											<div class="kt-widget4__pic kt-widget4__pic--icon">
												<img src="{{ $a['location'] }}">
											</div>
											<a href="/my-homeworks/download/{{ $a['id'] }}" target="_blank" class="kt-widget4__title">
												{{ $a['serverName'] }}
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
                    <div class="kt-portlet kt-portlet--height-fluid mb-0">
						<div class="kt-portlet__head p-0">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									{{ __("Attachments") }}
								</h3>
							</div>
						</div>
						<div class="kt-portlet__body p-0 ">
						<p> N/A </p>
						</div>
					</div>
					@endif
                </div>
			</div>
		</div>
		<div class="kt-portlet__body kt-portlet__body--fit">
            <form class="kt-form" id="homework_form" method="POST" action="/my-homeworks/{{ $homeworkUser->id }}">
                @csrf
                @method('PATCH')
				<input type="hidden" name="homework_id" value="{{ $homework->id }}" />
                <input type="hidden" name="attachments" id="attachments" />
                <div class="kt-portlet__body py-0">
                    
					<div class="kt-portlet__head d-block p-0 py-4" style="min-height: auto">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title">
								{{ __("Submissions") }}
							</h3>
						</div>
					</div>
                    <div class="form-group">
						<label>{{ __("Text") }}</label>
                        <textarea type="text" name="submission" id="submission" required class="form-control" placeholder="Enter submission">{{ $homeworkUser->answer }}</textarea>
                    </div>
                </div>
            </form>
            <div class="kt-portlet__body pt-0 pb-4">
                <label>{{ __("Attachments") }}:</label>
                <form action="" class="dropzone dropzone-default dz-clickable" method="POST" enctype="multipart/form-data" id="kt_dropzone">
                    @csrf
                    
                    <div class="dropzone-msg dz-message needsclick">
                        <h3 class="dropzone-msg-title">{{ __("Drop files here or click to upload") }}</h3>
                        {{-- <span class="dropzone-msg-desc">Only image files are allowed for upload</span> --}}
                    </div>
                </form>
            </div>
            <div class="kt-portlet__foot pt-4">
                <div class="kt-form__actions">
                    <button form="homework_form"  type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                    <a href="/my-homeworks"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                </div>
            </div>
			
		</div>

		<!--end:: Widgets/Blog-->
	</div>
</div>	
@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
	// $(document).ready(function(){

        function removeFile(ty, fn,id=null) {
                var url= id == null ? '/my-homeworks/removeFile/'+ty+'/'+fn : '/my-homeworks/removeFileAndAttachment/'+ty+'/'+fn+'/'+id;
                $.ajax({
                    url: url,
                    type: 'GET'
                });

                return true;
            }

            CKEDITOR.replace( 'title' ,{ removeButtons: 'Table' } );
            CKEDITOR.replace( 'submission' ,{ removeButtons: 'Table' } );
            
            var id = '#kt_dropzone';

            var hasError=Array();
            var images=Array();
            var i =0;
            var _dropZone=new Dropzone(id,{
                url: "@php echo url('/my-homeworks/upload-dropzone') @endphp", // Set the url for your upload script location
                paramName: "file", // The name that will be used to transfer the file
                maxFiles: 10,
                maxFilesize: {{ config('app.max_file_size') }}, // MB
                addRemoveLinks: true,
                // autoProcessQueue: false,
                acceptedFiles: "image/*, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" ,
                init:function(){
                    var elem=this
                    var attachments=@json($attachmentsAnswer);
                    $.each(attachments, function(key,value) {
                        images.push(value.serverName);    
                        var mockFile = { serverFn:value.serverName, name: value.serverName, size: value.size, id:value.id };
                        elem.files.push(mockFile);
                        elem.emit("addedfile", mockFile);
                        elem.emit("thumbnail", mockFile, value.location);
                        elem.emit("complete", mockFile);

                    });
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

        
        

        $('.kt-form').on('submit',function(e){ 
            e.preventDefault();
            var form=$(this);
            KTApp.blockPage();
            $.validator.addMethod("check_ck_add_method",
                function (value, element) {
                    return check_ck_editor();
                });

            function check_ck_editor() {
                if (CKEDITOR.instances.submission.getData() == '') {
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
                    submission: {
                        check_ck_add_method: true
                    },
                } ,
                messages:
                    {

                    title:{
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