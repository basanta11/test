@extends('layouts.main')
@section('title','Teachers | '. config("app.name"))
@section('teachers','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Teachers"),
        'crumbs' => [
            [
                'name' => __("Teachers"),
                'url' => '/teachers'
            ],
            [
                'name' => 'Create Teacher CSV',
                'url' => '/teachers/create'
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
    <div class="err">
        {{-- ajax err --}}
    </div>
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				{{-- <span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-line-plus"></i>
				</span> --}}
				<h3 class="kt-portlet__head-title">
					Create Teacher CSV
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" action="/teachers/csv" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="kt-portlet__body p-0">
                    <div class="form-group">
                        <label>CSV file: <small class="text-muted font-italic text-small"><abbr title="Leave a blank if not specified">(citizen number and symbol number are optional)</abbr></small></label>
                        <div class="custom-file">
                            <input accept="*.csv" type="file" name="csv" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                        </div>
                        <input id="data_fix" name="data_fix" hidden>
                    </div>
                </div>
                <div class="kt-portlet__body p-0 ">
                    <div class="form-group">
                    <a target="_blank" href="{{ global_asset('/assets/media/examples/teacher.csv') }}">Click here to download a demo of a csv file.</a>
                    </div>
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/teachers"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script>
    var all_data="";
    var success=false;
    $('.kt-form').on('submit',function(e){
        console.log($('input[name="csv"]').get(0).files)

        KTApp.blockPage();
        var form=$(this);
        if($('input[name="csv"]').get(0).files.length == 0)
        {
            KTApp.unblockPage();
            makeErrCustom($('input[name="csv"]'),'File is required to submit.');
            e.preventDefault();
            return; 
        }
        if(success===false){
            e.preventDefault();
            KTApp.unblockPage();
            makeErrCustom($('input[name="csv"]'),'Please check the list above and upload a valid csv.');
            return; 

        }
        
        form.unbind('submit').submit()
        return true;

    })

    $('input[name="csv"]').on('change',function(e){
        remErrCustom($(this))
        // var elem=$(this)
        var file_data = this.files[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            url: '/teachers/csv-validation'+'?_token=' + '{{ csrf_token() }}',
            data: form_data,
            type: 'post',
            // async: false,
            processData: false,
            contentType: false,
            success:function(data){
                if(!data.success)
                {
                    success=false;
                    var list="";
                    $.each(data.message,function(i,j){
                        list+=`<li>${j}</li>`
                    })
                    $('.err').html(`<div class="alert alert-light alert-elevate mb-0" role="alert">

                        <div class="alert alert-danger w-100" role="alert">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text">
                                <ul>
                                    ${list}
                                </ul>
                            </div>
                        </div>
                    </div>`)
                }else{
                    success=true;
                    $('.err').html(`<div class="alert alert-light alert-elevate mb-0" role="alert">

                    <div class="alert alert-success w-100" role="alert">
                        <div class="alert-icon"><i class="flaticon2-check-mark"></i></div>
                        <div class="alert-text">
                            <ul>
                                <li>Data is ready to be uploaded.</li>
                            </ul>
                        </div>
                    </div>
                    </div>`)

                    all_data=data.data;
                    $('#data_fix').val(all_data);
                    console.log(all_data);
                }
                // console.log(data,data.message);
                // alert(data.message);

            }
         });
    });
    

</script>
@endpush