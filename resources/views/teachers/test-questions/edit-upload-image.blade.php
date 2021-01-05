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
                'name' => $test->title,
                'url' => '/tests/'.$test->id.'/view',
            ],

            [
                'name' => $testset->title,
                'url' => '/test-sets/'.$testset->id,
            ],

            [
                'name' => __('Edit'),
                'url' => url()->current(),
            ],
        ]
    ])

<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Edit Question") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form mt-2" action="/test-questions/update-image-upload/{{ $question->id }}" method="post" id="image-upload-form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="attachments" id="attachments" />
                <div class="form-group">
                    <label>Question</label>
                    <textarea class="form-control" id="editor1" name="question">{{ $question->title }}</textarea>
                </div>
                <div class="form-group">
                    <label>Order</label>
                    <input type="number" id="order" min="1" class="form-control number-type" name="order" value="{{ $question->order }}" required min="1">
                </div>
                <div class="form-group">
                    <label>Marks</label>
                    <input type="number" id="marks" class="form-control number-type" name="marks" value="{{ $question->marks }}" required min="1">
                </div>
                <div class="form-group">
                    <label>Note:</label>
                    <textarea class="form-control" id="uploadImageNote" name="note">{{ $question->note }}</textarea>
                </div>
                
            </form>
        
            <label>Image files:</label>
            <form action="" class="dropzone dropzone-default dz-clickable" method="POST" enctype="multipart/form-data" id="kt_dropzone">
                @csrf
                
                <div class="dropzone-msg dz-message needsclick">
                    <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                    <span class="dropzone-msg-desc">Only image files are allowed for upload</span>
                </div>
            </form>
           
            <div class="kt-portlet__foot px-0 pt-4">
                <div class="kt-form__actions">
                    <button id="image-upload" form="image-upload-form" type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                    <a href="/test-sets/{{ $question->test_set_id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('scripts')

<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
    $(document).ready(function(){
        function removeFile(ty, fn,id=null) {
            var url= id == null ? '/test-questions/removeFile/'+ty+'/'+fn : '/test-questions/removeFileAndAttachment/'+ty+'/'+fn+'/'+id;
            $.ajax({
                url: url,
                type: 'GET'
            });

            return true;
        }

        CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );
        CKEDITOR.replace( 'uploadImageNote' ,{ removeButtons: 'Table' } );

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
                var elem=this
                var attachments=@json($attachments);
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

        $('#image-upload-form').on('submit', function(e) {
            e.preventDefault();

            KTApp.blockPage();
            var form=$(this);
        
            form.validate({
                focusInvalid: true,
                rules: {
                    question: {
                        required: true
                    },
                    marks: {
                        required: true,
                        number: true,
                        min: 1,
                    },
                    
                    
                }
            });
            
            if (!form.valid()) {
                e.preventDefault();
                KTApp.unblockPage();
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
            }
            else if(images.length<1){
                e.preventDefault();
                
                KTApp.unblockPage();
                var element=$('#kt_dropzone')
                var err=$('#drop_zone_error');
                if(err.length>0){
                    err.remove();
                }
                element.append(`<div id="drop_zone_error" style="display:block" class="error invalid-feedback">At least one file is required.</div>`)
                
                
                return false;
            }else{
                $('#attachments').val(images);
    
                $(this).unbind('submit').submit();
                return true;

            }

        });    
    })
    const question_id={{ $question->id }};
    
    if($('input[name="marks"]').length>0){
        const url = "@php echo url('/api/test-questions/get-mark/'.$question->test_set_id) @endphp";
        
        $.ajax({
            url:url,
            dataType:'JSON',
            type:'GET',
            data:{
                question_id:question_id,
            },
            success:function(data){
                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
            }
        })
    }
     // order check
     $(document).on('focus','input[name="order"]',function(e){
            var elem=$(this)
            remErrCustom(elem)
        })
        $(document).on('focusout','input[name="order"]',function(e){
            var elem=$(this);
            var order=elem.val();
            var set_id={{ $question->test_set_id }};
            $.ajax({
                url:'/api/test-questions/order-exists/',
                dataType:'JSON',
                type:'GET',
                data:{
                    order:order,
                    set_id:set_id,
                    question_id:question_id,
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
            var set_id={{ $question->test_set_id }};
            $.ajax({
                url:'/api/test-questions/mark-valid/',
                dataType:'JSON',
                type:'GET',
                data:{
                    marks:marks,
                    set_id:set_id,
                    question_id:question_id,
                },
                success:function(data){
                    if(data.status){
                        makeErrCustom(elem,'Marks given is greater than remaining marks for this set.');
                    }
                }
            })
            
        })
</script>
@endpush
@push('styles')
<style>

.dz-message{
  text-align: center;
  font-size: 28px;
}

.dz-preview .dz-image img{
  width: 100% !important;
  height: 100% !important;
  object-fit: cover;
}
.dz-error-message{
    top: 144px !important;
}
</style>
@endpush