@extends('layouts.main')
@section('title','Student | '. config("app.name"))
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
                'name' => __("View Student"),
                'url' => url()->current(),
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
	@include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		
        <!--Begin::Section-->
        <div class="row">
            <div class="col-xl-12">

                <!--begin:: Widgets/Applications/User/Profile3-->
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-portlet__body">
                        <div class="kt-widget kt-widget--user-profile-3">
                            <div class="kt-widget__top">
                                <div class="kt-widget__media kt-hidden-">
                                    <img class="custom-image-show" src="{{ $profile_image }}" alt="image">
                                </div>
                                <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                    JM
                                </div>
                                <div class="kt-widget__content">
                                    <div class="kt-widget__head">
                                        <a href="#" class="kt-widget__username">
                                            {{ $student->name }}
                                            @if($student->status!=1) <i class="text-danger flaticon2-exclamation"></i> @else <i class="flaticon2-correct"></i> @endif
                                        </a>
                                        <div class="kt-widget__action">

                                            <a href="/students/{{ $student->id }}/edit"><button type="button"  class="btn btn-warning btn-sm btn-upper">{{ __("Edit") }}</button></a>&nbsp;
                                            @if($student->status!=1)
                                                <button type="button" data-status="{{ $student->status }}" href="#" data-id="{{ $student->id }}" class="status-change btn btn-label-success btn-sm btn-upper">{{ __("Activate") }}</button>&nbsp;
                                            @else
                                                <button type="button" data-status="{{ $student->status }}" href="#" data-id="{{ $student->id }}" class="status-change btn btn-label-danger btn-sm btn-upper">{{ __("Deactivate") }}</button>&nbsp;

                                            @endif
                                        </div>
                                    </div>
                                    <div class="kt-widget__subhead">
                                        <a href="#"><i class="flaticon2-new-email"></i>{{ $student->email }}</a>
                                        <a href="#"><i class="flaticon2-phone"></i>{{ $student->phone }} </a>
                                        <a href="#"><i class="flaticon2-placeholder"></i>{{ $student->address }}</a>

                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__text">
                                            <label>{{ __("Gender") }}: </label>
                                            @if($student->gender==0){{ __("Male") }} @elseif($student->gender == 1) {{ __("Female") }} @else {{ __("Other") }} @endif
                                        </div>
                                        
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__text">
                                            <label>{{ __("Citizenship Number") }}: </label>
                                            {{ $student->citizen_number }}
                                        </div>
                                        
                                    </div>
                                    <div class="kt-widget__info">
                                        <div class="kt-widget__text">
                                            <label>{{ __("Symbol Number") }}: </label>
                                            {{ $student->symbol_number }}
                                        </div>
                                        
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Applications/User/Profile3-->
            </div>
        </div>
     
    </div>

</div>
@endsection
@push('scripts')
<script>
    $(document).on('click','.status-change',function(e){
        e.preventDefault();
        var status=$(this).data('status');
        var id=$(this).data('id');
        var link=message=header="";
        if(status==1){
            link=`/students/change-status/${id}/2`;
            message="Do you really want to deactivate this account?";
            header="Deactivate account";
        }else{
            link=`/students/change-status/${id}/1`;
            message="Do you really want to activate this account?";
            header="Activate account";
        }

        makeModal(link,message,header,"PATCH");

    });
$('.kt-form').on('submit',function(e){
            KTApp.blockPage();
           var form=$(this);
       
        form.validate({
            focusInvalid: true,
            rules: {
                behavior: {
                    required: true,
                    maxlength:500,
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
</script>
@endpush