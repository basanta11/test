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
                'name' => __('Backend Setting'),
                'url' => '/settings/backend'
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
                                        " id="home-tab-5" data-toggle="tab" href="#theme">
                                            <span class="nav-text">{{ __("Theme") }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-8">
                                <div class="tab-content" id="myTabContent5">
                                    <div class="tab-pane fade 
                                    @if(!Session::get('res')) active show @endif
                                    " id="theme" role="tabpanel" aria-labelledby="home-tab-5">
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
                                                            <div class="col-md-12">
                                                                <label>{{ __("Theme Color") }}</label>
                                                                <div class="form-group">
                                                                    <button id="basic" class="popup-parent btn btm-lg w-100 " style="border: black 1px">{{ __("Theme Color") }}</button>
                                                                    <input class="form-control"  disabled id="basic-input" placeholder="Hex Code">
                                                                    <div class="picker_wrapper layout_default popup popup_right" style="display: none;"><div class="picker_arrow"></div><div class="picker_hue picker_slider" style="color: rgb(0, 204, 255);"><div class="picker_selector" style="left: 53.3333%;"></div></div><div class="picker_sl" style="background-color: rgb(0, 204, 255); color: rgb(126, 208, 229);"><div class="picker_selector" style="left: 66%; top: 30.5%;"></div></div><div class="picker_alpha picker_slider" style="background-image: linear-gradient(rgb(126, 208, 229), rgba(126, 208, 229, 0)), url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='2' height='2'%3E%3Cpath d='M1,0H0V1H2V2H1' fill='lightgrey'/%3E%3C/svg%3E&quot;);"><div class="picker_selector" style="top: 0%;"></div></div><div class="picker_editor"><input></div><div class="picker_sample" style="color: rgb(126, 208, 229);"></div><div class="picker_done"><button>Ok</button></div></div>
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

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var primary='{{ $site_settings["theme"]["primary"] }}';
document.getElementById("basic-input").value = primary; 
var parentBasic  = col('#basic');
var popupBasic;

/* Basic example */
popupBasic = new Picker({
    parent: parentBasic,
    editorFormat: 'rgb',
    color: primary,
});
popupBasic.setOptions({
    popup: 'left',
});
parentBasic.style.background=primary;
popupBasic.onDone = function(color) {
    var selection=color.hex;
    parentBasic.style.background = color.rgbaString;
    document.getElementById("basic-input").value = selection; 
    $(document).ready(function(){
        $('<form action="/settings/backend/change-color" method="POST">@csrf<input name="theme" type="text" value="'+selection+'"></form>').appendTo('body').submit();
    });
};
//Open the popup manually:
// popupBasic.openHandler();


//*

popupBasic.onOpen  = function(color) { console.log('Opened', this.settings.parent.id, color.hex); };
popupBasic.onClose = function(color) { console.log('Closed', this.settings.parent.id, color.hex); };



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