@extends('layouts.main')
@section('title','Administrators | '. config("app.name"))
@section('administrators','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Administrators"),
        'crumbs' => [
            [
                'name' => __("Administrators"),
                'url' => '/administrators'
            ],
            [
                'name' => 'Edit Administrator',
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
					Edit Administrator
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" action="/administrators/{{ $administrator->id }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @csrf
                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>{{ __("Name") }}</label>
                    <input type="text" name="name" value="{{ old('name',$administrator->name )}}" class="form-control" placeholder="{{ __("Enter name") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Email address") }}</label>
                        <input type="email" name="email" value="{{ old('email',$administrator->email )}}"  class="form-control" aria-describedby="emailHelp" placeholder="{{ __("Enter email") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Address") }}</label>
                        <input type="text" name="address" autocomplete="off" value="{{ old('address',$administrator->address )}}"  class="form-control" placeholder="{{ __("Enter address") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("House number") }}</label>
                        <input type="text" name="house_number" autocomplete="off" value="{{ old('house_number',$administrator->house_number )}}" class="form-control" placeholder="{{ __("Enter house number") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Phone") }}</label>
                        <input type="number" min="1" name="phone" value="{{ old('phone',$administrator->phone )}}"  class="form-control" placeholder="{{ __("Enter phone") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Citizenship Number") }}</label>
                        <input value="{{ $administrator->citizen_number }}" type="text" name="citizen_number" class="form-control" placeholder="{{ __("Enter citizen number") }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Symbol Number") }}</label>
                        <input value="{{ $administrator->symbol_number }}" type="text" name="symbol_number" class="form-control" placeholder="{{ __("Enter symbol number") }}">
                    </div>
                    <div class="form-group">
                         <label>{{ __("Gender") }}</label>
                        <select name="gender" class="form-control" required>
                            <option selected disabled>Select gender...</option>
                            <option {{ $administrator->gender==0 ? 'selected' :'' }} value="0">Male</option>
                            <option {{ $administrator->gender==1 ? 'selected' :'' }} value="1">Female</option>
                            <option {{ $administrator->gender==2 ? 'selected' :'' }} value="2">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Image") }}</label>
                        <div class="custom-file">
                            <input accept="image/*" type="file" name="image" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                        </div>
                    </div>
                    
                    {{-- <div class="form-group form-group-last">
                        <label for="exampleTextarea">Example textarea</label>
                        <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
                    </div> --}}
                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/administrators"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')

<script src="{{ global_asset('assets/js/thai-validator-function.min.js') }}" type="text/javascript"></script>


<link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
<script src="{{ global_asset('assets/js/thai-address/JQL.min.js') }}" type="text/javascript"></script>
<script src="{{ global_asset('assets/js/thai-address/jquery.Thailand.min.js') }}" type="text/javascript"></script>
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

                        id:{{ $administrator->id }},
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

                id:{{ $administrator->id }},
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
    $('input[name="email"').on('focus',function(e){
        var elem=$(this)
        remErrCustom(elem)
    })
    $('input[name="email"').on('focusout',function(e){
        var elem=$(this);
        var email=elem.val();
        $.ajax({
            url:'/api/email-exists/',
            dataType:'JSON',
            type:'GET',
            data:{
                email:email,
                id:{{ $administrator->id }},
            },
            success:function(data){
                if(data.status){
                    makeErrCustom(elem,'Email already taken')
                }
            }
        })
        
    })
    // email check end

$('.kt-form').on('submit',function(e){
            KTApp.blockPage();
           var form=$(this);
       
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
                // phone: {
                //     required: true,
                //     maxlength:11,
                // },
                gender: {
                    required: true,
                },
                // citizen_number:{
                //     required: true,
                // },
                symbol_number:{
                    required:true,
                    number: true,
                },
                // image:{
                //     required:true,
                // }
            }
        });
        
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return;
        }

        return true;

    })

    $('select[name="gender"]').selectpicker();
    $.Thailand({
        $search: $('input[name="address"]'),
        $full_address: $('input[name="address'),
        
        onDataFill: function(data){
            full_address=`${data.district}, ${data.amphoe}, ${data.province}, ${data.zipcode}`;
        },
    });   
</script>
@endpush