@extends('layouts.main')
@section('title','Settings | '. config("app.name"))
@section('dashboard','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Dashboard'),
        'crumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => '/app'
            ],
            [
                'name' => __('Website Setting'),
                'url' => '/settings/frontend'
            ],
        ]
    ])

<div class="modal fade" id="home_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="modal_form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div id="modal_form_div">
                    </div>
                </form>
                <p></p>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    
	@include('layouts.partials.flash-message')

    <!--Begin::Dashboard 3-->

    <!--Begin::Row-->


   


    <div class="row">
       
        <div class="col-xl-12 col-lg-12 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Blog-->
            <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                
                <div class="kt-portlet__body">
                    <div class="example-preview">
                        <div class="row">
                            <div class="col-4">
                                <ul class="nav flex-column nav-pills">
                                    <li class="nav-item mb-2">
                                        <a class="nav-link 
                                        @if(!Session::get('res')) active @endif
                                        " id="home-tab-5" data-toggle="tab" href="#color">
                                            <span class="nav-text">{{ __("Color") }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link
                                        
                                        @if(Session::get('res')=='about-us') active @endif
                                        " id="home-tab-5" data-toggle="tab" href="#about-us">
                                            <span class="nav-text">{{ __("About Us") }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  mb-2">
                                        <a class="nav-link
                                        @if(Session::get('res')=='banner') active @endif
                                        " id="home-tab-5" data-toggle="tab" href="#banners">
                                            <span class="nav-text">{{ __("Banners") }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  mb-2">
                                        <a class="nav-link
                                        @if(Session::get('res')=='social') active @endif
                                        " id="home-tab-5" data-toggle="tab" href="#socials">
                                            <span class="nav-text">{{ __("Social Links") }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  mb-2">
                                        <a class="nav-link
                                        @if(Session::get('res')=='contact') active @endif
                                        " id="home-tab-5" data-toggle="tab" href="#contact">
                                            <span class="nav-text">{{ __("Contacts") }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item  mb-2">
                                        <a class="nav-link
                                        @if(Session::get('res')=='map') active @endif
                                        " id="home-tab-5" data-toggle="tab" href="#map">
                                            <span class="nav-text">{{ __("Map") }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-8">
                                <div class="tab-content" id="myTabContent5">
                                    <div class="tab-pane fade 
                                    @if(!Session::get('res')) active show @endif
                                    " id="color" role="tabpanel" aria-labelledby="home-tab-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kt-portlet kt-portlet--head-lg kt-portlet--mobile">
                                                    <div class="kt-portlet__head">
                                                        <div class="kt-portlet__head-label">
                                                            <h3 class="kt-portlet__head-title">
                                                               {{ __("Color") }}
                                                            </h3>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="kt-portlet__body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>{{ __("Primary Color") }}</label>
                                                                <div class="form-group">
                                                                    <button id="basic" class="popup-parent btn btm-lg w-100 " style="border: black 1px">{{ __("Primary Color") }}</button>
                                                                    <input class="form-control"  disabled id="basic-input" placeholder="Hex Code">
                                                                    <div class="picker_wrapper layout_default popup popup_right" style="display: none;"><div class="picker_arrow"></div><div class="picker_hue picker_slider" style="color: rgb(0, 204, 255);"><div class="picker_selector" style="left: 53.3333%;"></div></div><div class="picker_sl" style="background-color: rgb(0, 204, 255); color: rgb(126, 208, 229);"><div class="picker_selector" style="left: 66%; top: 30.5%;"></div></div><div class="picker_alpha picker_slider" style="background-image: linear-gradient(rgb(126, 208, 229), rgba(126, 208, 229, 0)), url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='2' height='2'%3E%3Cpath d='M1,0H0V1H2V2H1' fill='lightgrey'/%3E%3C/svg%3E&quot;);"><div class="picker_selector" style="top: 0%;"></div></div><div class="picker_editor"><input></div><div class="picker_sample" style="color: rgb(126, 208, 229);"></div><div class="picker_done"><button>Ok</button></div></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="d-block">{{ __("Secondary Color") }}</label>
                                                                <div class="form-group">
                                                                    <button id="basic-second" class="popup-parent btn btm-lg w-100 " style="border: black 1px">{{ __("Secondary Color") }}</button>
                                                                    <input class="form-control"  disabled id="basic2-input" placeholder="Hex Code">
                                                                    <div class="picker_wrapper layout_default popup popup_right" style="display: none;"><div class="picker_arrow"></div><div class="picker_hue picker_slider" style="color: rgb(0, 204, 255);"><div class="picker_selector" style="left: 53.3333%;"></div></div><div class="picker_sl" style="background-color: rgb(0, 204, 255); color: rgb(126, 208, 229);"><div class="picker_selector" style="left: 66%; top: 30.5%;"></div></div><div class="picker_alpha picker_slider" style="background-image: linear-gradient(rgb(126, 208, 229), rgba(126, 208, 229, 0)), url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='2' height='2'%3E%3Cpath d='M1,0H0V1H2V2H1' fill='lightgrey'/%3E%3C/svg%3E&quot;);"><div class="picker_selector" style="top: 0%;"></div></div><div class="picker_editor"><input></div><div class="picker_sample" style="color: rgb(126, 208, 229);"></div><div class="picker_done"><button>Ok</button></div></div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="d-block">{{ __("Card Color") }}</label>
                                                                <div class="form-group">
                                                                    <button id="basic-third" class="popup-parent btn btm-lg w-100 " style="border: black 1px">{{ __("Card Color") }}</button>
                                                                    <input class="form-control"  disabled id="basic3-input" placeholder="Hex Code">
                                                                    <div class="picker_wrapper layout_default popup popup_right" style="display: none;"><div class="picker_arrow"></div><div class="picker_hue picker_slider" style="color: rgb(0, 204, 255);"><div class="picker_selector" style="left: 53.3333%;"></div></div><div class="picker_sl" style="background-color: rgb(0, 204, 255); color: rgb(126, 208, 229);"><div class="picker_selector" style="left: 66%; top: 30.5%;"></div></div><div class="picker_alpha picker_slider" style="background-image: linear-gradient(rgb(126, 208, 229), rgba(126, 208, 229, 0)), url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='2' height='2'%3E%3Cpath d='M1,0H0V1H2V2H1' fill='lightgrey'/%3E%3C/svg%3E&quot;);"><div class="picker_selector" style="top: 0%;"></div></div><div class="picker_editor"><input></div><div class="picker_sample" style="color: rgb(126, 208, 229);"></div><div class="picker_done"><button>Ok</button></div></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane 

                                    @if(Session::get('res')=='about-us') active show @endif
                                    fade" id="about-us" role="tabpanel" aria-labelledby="profile-tab-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kt-portlet kt-portlet--head-lg kt-portlet--mobile">
                                                    <div class="kt-portlet__head">
                                                        <div class="kt-portlet__head-label">
                                                            <h3 class="kt-portlet__head-title">
                                                                {{ __("About Us") }}
                                                            </h3>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="kt-portlet__body">

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <form class="form" action="/settings/frontend/about-us" method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input class="form-control mb-2" maxlength="200" name="title" value="{{ $data['about-us']['title'] }}" placeholder="Enter Title">
                                                                    <textarea class="form-control mb-2" maxlength="500" name="description" placeholder="Enter Description">{{ $data['about-us']['description'] }}</textarea>
                                                                    <button type="submit" class="btn btn-outline-success btn-sm">{{ __("Save Changes") }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="separator my-4 separator-solid separator-border-2"></div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label>Mission</label>  <span class="btn btn-sm btn-outline-primary py-0 my-0 custom-update" data-type="mission"> <i class="fa fa-edit"></i>{{ __("Update") }}</span>
                                                                <div class="form-group m-0">
                                                                    <input class="form-control mb-2" type="text"  value="{{ $data['mission']['title'] }}" readonly >
                                                                    <img class="custom-image image-medium" src="{{ $data['mission']['image'] }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Vision</label> <span class="btn btn-sm btn-outline-primary py-0 my-0 custom-update"  data-type="vision"> <i class="fa fa-edit"></i>{{ __("Update") }}</span>
                                                                <div class="form-group m-0">
                                                                    <input class="form-control mb-2" type="text" value="{{ $data['vision']['title'] }}" readonly>
                                                                    <img class="custom-image image-medium" src="{{ $data['vision']['image'] }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Goal</label> <span class="btn btn-sm btn-outline-primary py-0 my-0 custom-update"  data-type="goal"> <i class="fa fa-edit"></i>{{ __("Update") }}</span>
                                                                <div class="form-group m-0">
                                                                    <input class="form-control mb-2" type="text" value="{{ $data['goal']['title'] }}"  readonly>
                                                                    <img class="custom-image image-medium" src="{{ $data['goal']['image'] }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade
                                    @if(Session::get('res')=='banner') active show @endif
                                    " id="banners" role="tabpanel" aria-labelledby="contact-tab-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kt-portlet kt-portlet--head-lg kt-portlet--mobile">
                                                    <div class="kt-portlet__head">
                                                        <div class="kt-portlet__head-label">
                                                            <h3 class="kt-portlet__head-title">
                                                                {{ __("Banners") }}
                                                            </h3>
                                                        </div>
                                                        <div class="kt-portlet__head-toolbar">
                                                            <div class="dropdown dropdown-inline">
                                                                <button type="button" class="btn btn-clean custom-add-banner" >
                                                                    <i class="flaticon-plus"></i>{{ __("Add") }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="kt-portlet__body" >
                                                        <div class="row">
                                                            @foreach($data['banners'] as $key=>$banner)
                                                            <div class="col-md-6">
                                                                <div class="kt-portlet kt-portlet--head-lg kt-portlet--mobile " >
                                                                    <div class="kt-portlet__head">
                                                                        <div class="kt-portlet__head-label" >
                                                                            <h3 class="kt-portlet__head-title" >
                                                                                {{ $banner['title'] }}
                                                                            </h3>
                                                                        </div>
                                                                        <div class="kt-portlet__head-toolbar">
                                                                            <div class="dropdown dropdown-inline">
                                                                                <span class="btn btn-sm btn-outline-primary py-0 my-0 custom-update-banner"  data-array='{"id":"{{ $key }}","title":"{{ $banner['title'] }}","description":"{{ $banner['description'] }}"}'> <i class="fa fa-edit"></i></span>
                                                                                <span class="btn btn-sm btn-outline-danger py-0 my-0 item-delete"  data-id="{{ $key }}"> <i class="fa fa-trash"></i></span>
                                                                            </div> 
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    <div class="kt-portlet__body " >
                                                                        <span class="text-truncate">{{ $banner['description'] }}</span>
                                                                    </div>
                    
                                                                    <img class="custom-image image-medium" src="{{ $banner['image'] }}">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade
                                    @if(Session::get('res')=='social') active show @endif
                                    " id="socials" role="tabpanel" aria-labelledby="contact-tab-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kt-portlet kt-portlet--head-lg kt-portlet--mobile">
                                                    <div class="kt-portlet__head">
                                                        <div class="kt-portlet__head-label">
                                                            <h3 class="kt-portlet__head-title">
                                                                {{ __("Social") }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="kt-portlet__body" >
                                                        <div class="row">
                                                            <div class="col-md-12">

                                                                <form action="/settings/frontend/socials" method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fab fa-facebook-f"></i></span>
                                                                        </div>
                                                                        <input type="text" name="facebook" class="form-control" value="{{ $data['social-links']['facebook'] }}" placeholder="Facebook link">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fab fa-twitter"></i></span>
                                                                        </div>
                                                                        <input type="text" name="twitter" class="form-control" value="{{ $data['social-links']['twitter'] }}" placeholder="Twitter link">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fab fa-skype"></i></span>
                                                                        </div>
                                                                        <input type="text" name="skype" class="form-control" value="{{ $data['social-links']['skype'] }}" placeholder="Skype link">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fab fa-youtube"></i></span>
                                                                        </div>
                                                                        <input type="text" name="youtube" class="form-control" value="{{ $data['social-links']['youtube'] }}" placeholder="Youtube link">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fab fa-instagram"></i></span>
                                                                        </div>
                                                                        <input type="text" name="instagram" class="form-control" value="{{ $data['social-links']['instagram'] }}" placeholder="Instagram link">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fab fa-line"></i></span>
                                                                        </div>
                                                                        <input type="text" name="line" class="form-control" value="{{ $data['social-links']['line'] }}" placeholder="Line link">
                                                                    </div>
                                                                    <button class="btn btn-sm btn-outline-success" type="submit">{{ __("Save Changes") }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade
                                    @if(Session::get('res')=='contact') active show @endif
                                    " id="contact" role="tabpanel" aria-labelledby="contact-tab-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kt-portlet kt-portlet--head-lg kt-portlet--mobile">
                                                    <div class="kt-portlet__head">
                                                        <div class="kt-portlet__head-label">
                                                            <h3 class="kt-portlet__head-title">
                                                                {{ __("Contacts") }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="kt-portlet__body" >
                                                        <div class="row">
                                                            <div class="col-md-12">

                                                                <form action="/settings/frontend/contacts" method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fas fa-phone-alt"></i></span>
                                                                        </div>
                                                                    <input type="text" name="phone" class="form-control" value="{{ $data['contacts']['phone'] }}" placeholder="Phone">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fas fa-location-arrow"></i></span>
                                                                        </div>
                                                                        <input type="text" name="address" class="form-control" value="{{ $data['contacts']['address'] }}" placeholder="Location">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fas fa-envelope"></i></span>
                                                                        </div>
                                                                        <input type="text" name="email" class="form-control" value="{{ $data['contacts']['email'] }}" placeholder="Email">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fas fa-video"></i></span>
                                                                        </div>
                                                                        <input type="text" name="video" class="form-control" value="{{ $data['contacts']['video'] }}" placeholder="Video call">
                                                                    </div>
                                                                    <div class="input-group my-4">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fas fa-globe"></i></span>
                                                                        </div>
                                                                        <input type="text" name="website" class="form-control" value="{{ $data['contacts']['website'] }}" placeholder="External website">
                                                                    </div>
                                                                    <button class="btn btn-sm btn-outline-success" type="submit">{{ __("Save Changes") }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade
                                    @if(Session::get('res')=='map') active show @endif
                                    " id="map" role="tabpanel" aria-labelledby="map-tab-5">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kt-portlet kt-portlet--head-lg kt-portlet--mobile">
                                                    <div class="kt-portlet__head">
                                                        <div class="kt-portlet__head-label">
                                                            <h3 class="kt-portlet__head-title">
                                                                {{ __("Map") }}
                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="kt-portlet__body" >
                                                        <div class="row">
                                                            <div class="col-md-12">

                                                                <form action="/settings/frontend/maps" method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <div class="input-group my-4">
                                                                        {{-- <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i style="width: 25px;" class="fas fa-phone-alt"></i></span>
                                                                        </div> --}}
                                                                    <textarea max="500" name="map" class="form-control"  placeholder="Enter iframe of google map">{{ $data['map'] }}</textarea>
                                                                    </div>
                                                                    <button class="btn btn-sm btn-outline-success" type="submit">{{ __("Save Changes") }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        
                    </div>
                    
                    
                    
                </div>
              
            </div>

            <!--end:: Widgets/Blog-->
        </div>
     
    </div>

    <!--End::Row-->

    <!--End::Dashboard 3-->
</div>
@endsection
@push('scripts')
 <!--begin::Page Vendors(used by this page) -->
 <script src="{{ global_asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}" type="text/javascript"></script>
 <script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
 <script src="{{ global_asset('assets/plugins/custom/gmaps/gmaps.js') }}" type="text/javascript"></script>

 <!--end::Page Vendors -->

 <!--begin::Page Scripts(used by this page) -->
 <script src="{{ global_asset('assets/js/pages/dashboard.js') }}" type="text/javascript"></script>


 <script src="{{ global_asset('assets/plugins/colorPicker/dist/vanilla-picker.min.js') }}" type="text/javascript"></script>
 
 <!--end::Page Scripts -->
 <script>
     $(document).ready(function(){
        @if(!auth()->user()->status)
        
        link=`/change-password`;
        message=`{{ __("It looks like you haven't updated your password after first login.") }}<br> {{ __("Please click on proceed to update your password.") }}`;
        header=`{{ __('Update Password') }}`;
    

        makeModal(link,message,header,"GET");

        @endif
        $('.custom-update-banner').on('click',function(e){
            var data=$(this).data('array');
            console.log(data);
            var message='';
            link=`/settings/frontend/change-banner/`+data.id;
            message=`
                <label>Title</label>
                <div class="form-group">
                    <input required class="form-control" name="title" maxlength="200" value="${data.title}" placeholder="Enter Title">
                </div>
                <label>Description</label>
                <div class="form-group">
                    <textarea required class="form-control" name="description" maxlength="200" placeholder="Enter Title">${data.description}</textarea>
                </div>
            `;

            message+=`
                <input  accept="image/*" name="image" type="file">
            `;

            header=`<span class="text-capitalize">Update Banner</span>`

            $('#home_modal .modal-title').html(header);
			$('#modal_form_div').html(message);
			$('#home_modal form').attr('action',link);
			var form=`<button type="button"  class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
						<button type="submit" form="modal_form" class="btn btn-primary">{{__('Proceed') }}</button>`;
            $('#home_modal .modal-footer').html(form);
            $('#home_modal').modal('show');
            
            
        })
        //  add banner
        $('.custom-add-banner').on('click',function(e){
            var message='';
            link=`/settings/frontend/add-banner/`;
            message=`
                <label>Title</label>
                <div class="form-group">
                    <input required class="form-control" name="title" maxlength="200" placeholder="Enter Title">
                </div>
                <label>Description</label>
                <div class="form-group">
                    <textarea required class="form-control" name="description" maxlength="200" placeholder="Enter Description"></textarea>
                </div>
            `;

            message+=`
                <input required accept="image/*" name="image" type="file">
            `;

            header=`<span class="text-capitalize">Add Banner</span>`

            $('#home_modal .modal-title').html(header);
			$('#modal_form_div').html(message);
			$('#home_modal form').attr('action',link);
			var form=`<button type="button"  class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
						<button type="submit" form="modal_form" class="btn btn-primary">{{__('Proceed') }}</button>`;
            $('#home_modal .modal-footer').html(form);
            $('#home_modal').modal('show');
            
            
        })
        $('.custom-update').on('click',function(e){
            var type=$(this).data('type')
            var message='';
            if(type=='mission'){
                link=`/settings/frontend/change-mission`;
                message=`
                     <label>Mission</label>
                    <div class="form-group">
                        <textarea class="form-control" name="mission" maxlength="200" placeholder="Enter Mission">{{ $data['mission']['title'] }}</textarea>
                    </div>
                `;

            }
            else if(type=='vision')
            {
                link=`/settings/frontend/change-vision`;
                message=`
                    <label>Vision</label>
                    <div class="form-group">
                        <textarea class="form-control" name="vision"  maxlength="200"  placeholder="Enter Vision">{{ $data['vision']['title'] }}</textarea>
                    </div>
                `;

            }else{
                link=`/settings/frontend/change-goal`;
                message=`
                    <label>Goal</label>
                    <div class="form-group">
                        <textarea class="form-control" name="goal"  maxlength="200"  placeholder="Enter Goal">{{ $data['goal']['title'] }}</textarea>
                    </div>
                `;
            }

            message+=`
                <input accept="image/*" name="image" type="file">
            `;

            header=`<span class="text-capitalize">${type}</span>`

            $('#home_modal .modal-title').html(header);
			$('#modal_form_div').html(message);
			$('#home_modal form').attr('action',link);
			var form=`<button type="button"  class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
						<button type="submit" form="modal_form" class="btn btn-primary">{{__('Proceed') }}</button>`;
            // makeModal(link,message,header,"PATCH");
            $('#home_modal .modal-footer').html(form);
            $('#home_modal').modal('show');
            
            
        })
    });
    $('#modal_form').on('submit',function(e){
        KTApp.blockPage();
    })

    $(document).on('click','.item-delete',function(e){
		e.preventDefault();
		var id=$(this).data('id');	
		link=`/settings/frontend/delete-banner/${id}`;
		message="Do you really want to delete this banner?";
		header="Delete banner";
		makeModal(link,message,header,"DELETE");

	})
 </script>
 <script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var primary='{{ $data["color"]["primary"] }}';
document.getElementById("basic-input").value = primary; 
var secondary='{{ $data["color"]["secondary"] }}'
document.getElementById("basic2-input").value = secondary; 

var card='{{ $data["color"]["card"] }}'
document.getElementById("basic3-input").value = card; 

var parentBasic  = col('#basic');
var parentBasic2  = col('#basic-second');
var parentBasic3  = col('#basic-third');
var popupBasic;
var popupBasic2;
var popupBasic3;

/* Basic example */
popupBasic = new Picker({
    parent: parentBasic,
    editorFormat: 'rgb',
    color: primary,
});
popupBasic.setOptions({
    popup: 'top',
});
parentBasic.style.background=primary;
popupBasic.onDone = function(color) {
    var selection=color.hex;
    parentBasic.style.background = color.rgbaString;
    document.getElementById("basic-input").value = selection; 
    $.ajax({
        url:'/settings/frontend/change-color',
        dataType:'JSON',
        type:'POST',
        data:{
            type:'primary',
            color:selection
        },
        success:function(data){
            toastr.succes('Primary color changed');
        }
    })
};
//Open the popup manually:
// popupBasic.openHandler();


//*

popupBasic.onOpen  = function(color) { console.log('Opened', this.settings.parent.id, color.hex); };
popupBasic.onClose = function(color) { console.log('Closed', this.settings.parent.id, color.hex); };


/* Basic example */
popupBasic2 = new Picker({
    parent: parentBasic2,
    editorFormat: 'rgb',
    color: secondary,
});
popupBasic2.onDone = function(color) {
    var selection=color.hex;
    parentBasic2.style.background = color.rgbaString;
    document.getElementById("basic2-input").value = selection; 
    
    $.ajax({
        url:'/settings/frontend/change-color',
        dataType:'JSON',
        type:'POST',
        data:{
            type:'secondary',
            color: selection
        },
        success:function(data){
            toastr.succes('Secondary color changed');
        }
    })
};
popupBasic2.setOptions({
    popup: 'top',
});
//Open the popup manually:
// popupBasic2.openHandler();


parentBasic2.style.background=secondary;
//*

popupBasic2.onOpen  = function(color) { console.log('Opened', this.settings.parent.id, color.hex); };
popupBasic2.onClose = function(color) { console.log('Closed', this.settings.parent.id, color.hex); };

// third

/* Basic example */
popupBasic3 = new Picker({
    parent: parentBasic3,
    editorFormat: 'rgb',
    color: card,
});
popupBasic3.onDone = function(color) {
    var selection=color.hex;
    parentBasic3.style.background = color.rgbaString;
    document.getElementById("basic3-input").value = selection; 
    
    $.ajax({
        url:'/settings/frontend/change-color',
        dataType:'JSON',
        type:'POST',
        data:{
            type:'card',
            color: selection
        },
        success:function(data){
            toastr.succes('Card color changed');
        }
    })
};
popupBasic3.setOptions({
    popup: 'top',
});
//Open the popup manually:
// popupBasic3.openHandler();


parentBasic3.style.background=card;
//*

popupBasic3.onOpen  = function(color) { console.log('Opened', this.settings.parent.id, color.hex); };
popupBasic3.onClose = function(color) { console.log('Closed', this.settings.parent.id, color.hex); };

function col(selector, context) {
    return (context || document).querySelector(selector);
}
 </script>
@endpush
@push('styles')
<style>
    .image-medium{
        width: 100% !important;
        height: 300px !important;
    }
</style>
@endpush

@push('styles')
<style>
    .separator.separator-border-2 {
    border-bottom-width: 2px;
}
.separator.separator-solid {
    border-bottom: 1px solid #EBEDF3;
        border-bottom-width: 1px;
}
.separator {
    height: 0;
}
</style>
@endpush