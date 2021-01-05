@extends('layouts.main')
@section('title','Students | '. config("app.name"))
@section('students','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Students"),
        'crumbs' => [
            [
                'name' => __("Students"),
                'url' => '/students'
            ],
            [
                'name' => __("Create Student"),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				{{-- <span class="kt-portlet__head-icon">
					<i class="kt-font-brand flaticon2-line-plus"></i>
				</span> --}}
				<h3 class="kt-portlet__head-title">
					{{ __("Create Student") }}
					{{-- <small>initialized from remote json file</small> --}}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" action="/students" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group row">
                        <div class="col-md-6">
                        
                            <label>{{ __("Name") }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ __("Enter name") }}">
                        {{-- <span class="form-text text-muted">We'll never share your email with anyone else.</span> --}}
                        </div>
                        <div class="col-md-6">
                            <label>{{ __("Roll Number") }}</label>
                            <input type="text" name="roll_number" class="form-control" value="{{ old('roll_number') }}" placeholder="{{ __("Enter roll number") }}">
                        {{-- <span class="form-text text-muted">We'll never share your email with anyone else.</span> --}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label>{{ __("Email address") }}</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" aria-describedby="emailHelp" placeholder="{{ __("Enter email") }}">
                        {{-- <span class="form-text text-muted">We'll never share your email with anyone else.</span> --}}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>{{ __("Address") }}</label>
                                <input type="text" name="address" autocomplete="off" class="form-control" value="{{ old('address') }}" placeholder="{{ __("Enter address") }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("House number") }}</label>
                                <input type="text" name="house_number" autocomplete="off" class="form-control" value="{{ old('house_number') }}" placeholder="{{ __("Enter house number") }}">
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Phone") }}</label>
                                <input type="number" min="1" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="{{ __("Enter phone") }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">

                            <label>{{ __("Citizenship Number") }}</label>
                            <input type="text" name="citizen_number" class="form-control" value="{{ old('citizen_number') }}" placeholder="{{ __("Enter citizen number") }}">
                            
                        </div>
                        <div class="col-md-6">
                            <label>{{ __("Symbol Number") }}</label>
                            <input type="text" name="symbol_number" class="form-control" value="{{ old('symbol_number') }}" placeholder="{{ __("Enter symbol number") }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("Gender") }}</label>
                                <select name="gender" class="form-control" >
                                    <option selected disabled>{{ __("Select gender") }}...</option>
                                    <option value="0">{{ __("Male") }}</option>
                                    <option value="1">{{ __("Female") }}</option>
                                    <option value="2">{{ __("Other") }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Date of Birth") }}</label>
                                <input type="text" name="dob" class="form-control" value="{{ old('dob') }}" id="kt_datepicker_1" data-date-end-date="0d" readonly="" placeholder="{{ __("Select date") }}">

                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("Classroom") }}</label>
                                <select name="classroom_id" class="form-control"  >
                                    <option selected disabled>{{ __("Select classroom") }}...</option>
                                    @foreach($classrooms as $cr)
                                        <option value="{{ $cr->id }}">{{ $cr->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Section") }}</label>
                                <select name="section_id" class="form-control">
                                    <option selected disabled>{{ __("Please select classroom first") }}...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @plan('large')
                    {{-- guardian --}}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{ __("Guardian Name") }}</label>
                                <input type="text"  name="guardian_name" class="form-control" value="{{ old('guardian_name') }}" placeholder="{{ __("Enter guardian name") }}">
                            </div>
                            <div class="col-md-6">
                                <label>{{ __("Guardian Number") }}</label>
                                <input type="number" min="1" name="guardian_number" class="form-control" value="{{ old('guardian_number') }}" placeholder="{{ __("Enter guardian number") }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>{{ __("Guardian Email") }}</label>
                                <input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email') }}" placeholder="{{ __("Enter guardian email") }}">
                            </div>
                        </div>
                    </div>
                    @endplan
                    <div class="form-group">
                        <label>{{ __("Image") }}</label>
                        <div class="custom-file">
                            <input accept="image/*" type="file" name="image" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                        </div>
                    </div>
                    
                    {{-- <div class="form-group form-group-last">
                        <label for="exampleTextarea">Example textarea</label>
                        <textarea class="form-control" value="{{ old('name') }}" id="exampleTextarea" rows="3"></textarea>
                    </div> --}}
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/students"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script src="{{ global_asset('assets/js/thai-validator-function.min.js') }}" type="text/javascript"></script>

{{-- thailand address script --}}
<link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script src="{{ global_asset('assets/js/thai-address/JQL.min.js') }}" type="text/javascript"></script>
<script src="{{ global_asset('assets/js/thai-address/jquery.Thailand.min.js') }}" type="text/javascript"></script>
<script>
    $.Thailand({
        $search: $('input[name="address"]'),
        $full_address: $('input[name="address'),
        
        onDataFill: function(data){
            full_address=`${data.district}, ${data.amphoe}, ${data.province}, ${data.zipcode}`;
        },
    });   
</script>
{{-- end --}}

<script>
    $('input[name="citizen_number"]').inputmask({
        "mask": "9-9999-99999-99-9",
    });
    $('input[name="citizen_number"],input[name="symbol_number"]').on('focus',function(e){
        var elem=$(this);
        remErrCustom(elem)
    })  

    $('input[name="citizen_number"]').on('focusout',function(e){
        var elem=$(this)
        var unmasked=Inputmask.unmask($(this).val(), { alias: "9-9999-99999-99-9"})
        if(unmasked.length>0){
            if(!ThaiNationalID(unmasked)){
                // console.log()
                makeErrCustom(elem,'Invalid citizen number.');
            }else{
                $.ajax({
                    url:'/api/citizen-number-exists/',
                    dataType:'JSON',
                    type:'GET',
                    data:{
                        citizen_number:elem.val(),
                    },
                    success:function(data){
                        console.log(data);
                        if(data.status){
                            makeErrCustom(elem,'Citizen number already exists.')
                        }
                    }
                })

            }

        }
    });

    $('input[name="symbol_number"]').on('focusout',function(e){
        var elem=$(this)
        $.ajax({
            url:'/api/symbol-number-exists/',
            dataType:'JSON',
            type:'GET',
            data:{
                symbol_number:elem.val(),
            },
            success:function(data){
                console.log(data);
                if(data.status){
                    makeErrCustom(elem,'Symbol number already exists.')
                }
            }
        })
    });

    // email check
    $('input[name="email"], input[name="guardian_email"]').on('focus',function(e){
        var elem=$(this)
        remErrCustom(elem)
    })
    $('input[name="email"], input[name="guardian_email"]').on('focusout',function(e){
        var elem=$(this);
        var email=elem.val();
        $.ajax({
            url:'/api/email-exists/',
            dataType:'JSON',
            type:'GET',
            data:{
                email:email,
            },
            success:function(data){
                if(data.status){
                    makeErrCustom(elem,'Email already taken')
                }
            }
        })
        
    })
    // email check end

    $('#kt_datepicker_1').datepicker({
        format:'yyyy-mm-dd',
    });
    $('.kt-form').on('submit',function(e){
        KTApp.blockPage();
        var form=$(this);
        $.validator.addMethod("notEqualTo", function(value, element, param) {
        // Bind to the blur event of the target in order to revalidate whenever the target field is updated
        var target = $( param );
        if ( this.settings.onfocusout && target.not( ".validate-equalTo-blur" ).length ) {
            target.addClass( "validate-equalTo-blur" ).on( "blur.validate-equalTo", function() {
                $( element ).valid();
            } );
        }
        return value !== target.val();
        // condition returns true or false
    }, "Guardian email must be different");
        form.validate({
            focusInvalid: true,
            onfocusout:true,
            rules: {
                email: {
                    required: true,
                    email: true
                },
                name: {
                    required: true,
                    maxlength:150,
                },
                address: {
                    required: true,
                    maxlength:150,
                },
                // citizen_number:{
                //     required: true,
                // },
                symbol_number:{
                    required:true,
                    number: true,
                },
                // phone: {
                //     required: true,
                //     maxlength:11,
                // },
                gender: {
                    required: true,
                },
                dob:{
                    required: true,
                },
                classroom_id:{
                    required: true,
                },
                section_id:{
                    required: true,
                },
                @plan('large')
                guardian_name:{
                    required: true,
                    maxlength:150,
                },
                guardian_email:{
                    required: true,
                    email: true,
                    notEqualTo: "#email"
                },
                guardian_number:{
                    required: true,
                    maxlength:11,
                },
                @endplan
                roll_number:{
                    required: true,
                }
            }
        });
        
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return;
        }

        return true;

    })

    $('select[name="gender"],select[name="classroom_id"],select[name="section_id"]').selectpicker();
    $('select[name="classroom_id"]').on('change',function(e){
        var elem=$(this);
        const s = $('select[name="section_id"]');
        let url = '{{ url("/api/get-section") . '/' }}' + elem.val();

        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            success: function (res) {
                if (res !== []) {
                    s.html('');
                    s.append('<option selected disabled>Please select section..</option>');
                    $.each(res, function( index, value ) {
                        let html = `<option value="${value.id}">${value.title}</option>`;

                        s.append(html);
                    });

                    s.selectpicker('refresh');
                }
                else {
                    s.html('');
                }
            }
        });
    });
</script>
@endpush