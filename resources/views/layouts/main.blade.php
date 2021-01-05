<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

    <!--end::Fonts -->

    <!--begin::Page Vendors Styles(used by this page) -->
    <link href="{{ global_asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Page Vendors Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    
    <link href="{{ global_asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ global_asset('assets/css/style.bundle2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ global_asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ global_asset('css/custom-elearning.css') }}">
    <!--begin::Layout Skins(used by all pages) -->

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ global_asset('public/logo.png') }}" />
    @stack('styles')
    
    <style>
        .custom-file-label::after {
            content: "{{ __('Browse') }}" !important;
        }
        body{
            background: url("{{ global_asset('assets/media/bg/be.png') }}");
        /* background-repeat:none; */
            background-size: contain;
        }

        /* accent */
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item.kt-menu__item--here
            > .kt-menu__link
            .kt-menu__link-text {
            color: {{ $site_settings['theme']['primary'] }};
        }
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item:hover:not(.kt-menu__item--here):not(.kt-menu__item--active)
            > .kt-menu__link
            .kt-menu__link-text,
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item.kt-menu__item--hover:not(.kt-menu__item--here):not(.kt-menu__item--active)
            > .kt-menu__link
            .kt-menu__link-text {
            color: {{ $site_settings['theme']['primary'] }};
        }
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item
            .kt-menu__submenu
            > .kt-menu__subnav
            > .kt-menu__item.kt-menu__item--here
            > .kt-menu__link
            .kt-menu__link-bullet.kt-menu__link-bullet--dot
            > span {
            background-color: {{ $site_settings['theme']['primary'] }};
        }
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item
            .kt-menu__submenu
            > .kt-menu__subnav
            > .kt-menu__item.kt-menu__item--here
            > .kt-menu__link
            .kt-menu__link-text {
            color: {{ $site_settings['theme']['primary'] }};
        }
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item
            .kt-menu__submenu
            > .kt-menu__subnav
            > .kt-menu__item:hover:not(.kt-menu__item--here):not(.kt-menu__item--active)
            > .kt-menu__link
            .kt-menu__link-bullet.kt-menu__link-bullet--dot
            > span,
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item
            .kt-menu__submenu
            > .kt-menu__subnav
            > .kt-menu__item.kt-menu__item--hover:not(.kt-menu__item--here):not(.kt-menu__item--active)
            > .kt-menu__link
            .kt-menu__link-bullet.kt-menu__link-bullet--dot
            > span,
            .nav-pills .nav-item .nav-link:active, .nav-pills .nav-item .nav-link.active, .nav-pills .nav-item .nav-link.active:hover {
            background-color: {{ $site_settings['theme']['primary'] }};
        }
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item
            .kt-menu__submenu
            > .kt-menu__subnav
            > .kt-menu__item:hover:not(.kt-menu__item--here):not(.kt-menu__item--active)
            > .kt-menu__link
            .kt-menu__link-text,
        .kt-header-menu
            .kt-menu__nav
            > .kt-menu__item
            .kt-menu__submenu
            > .kt-menu__subnav
            > .kt-menu__item.kt-menu__item--hover:not(.kt-menu__item--here):not(.kt-menu__item--active)
            > .kt-menu__link
            .kt-menu__link-text {
            color: {{ $site_settings['theme']['primary'] }};
        }
        .show > .btn.btn-clean,
        .btn.btn-clean.active,
        .btn.btn-clean:active,
        .btn.btn-clean:hover {
            color: {{ $site_settings['theme']['primary-hover'] }};
            background: #f0f3ff;
        }
        .kt-datatable
            > .kt-datatable__pager
            > .kt-datatable__pager-nav
            > li
            > .kt-datatable__pager-link.kt-datatable__pager-link--active {
            background: {{ $site_settings['theme']['primary'] }};
            color: #ffffff;
        }
        .kt-notification
            .kt-notification__item:hover
            .kt-notification__item-details
            .kt-notification__item-title {
            -webkit-transition: color 0.3s ease;
            transition: color 0.3s ease;
            color: {{ $site_settings['theme']['primary'] }};
        }
        .kt-font-brand, .kt-subheader .kt-subheader__main .kt-subheader__breadcrumbs .kt-subheader__breadcrumbs-link.kt-subheader__breadcrumbs-link--active {
            color: {{ $site_settings['theme']['primary'] }} !important;
        }

        /* login */
        .btn-brand,
        .btn-primary,
        .dropdown-item.active,
        .dropdown-item:active {
            color: #fff;
            background-color: {{ $site_settings['theme']['primary'] }};
            border-color: {{ $site_settings['theme']['primary'] }};
            color: #ffffff;
        }
        .btn-brand:hover,
        .btn-primary:hover {
            color: #fff;
            background-color:  {{ $site_settings['theme']['primary-hover'] }};
            border-color: {{ $site_settings['theme']['primary-hover'] }};
        }
        .btn-brand:not(:disabled):not(.disabled):active,
        .btn-brand:not(:disabled):not(.disabled).active,
        .show
            > .btn-brand.dropdown-toggle
            .btn-primary:not(:disabled):not(.disabled):active,
        .btn-primary:not(:disabled):not(.disabled).active,
        .show > .btn-primary.dropdown-toggle {
            background-color: {{ $site_settings['theme']['primary'] }} !important ;
            border-color: {{ $site_settings['theme']['primary'] }} !important ;
        }
        .btn-brand:focus,
        .btn-brand.focus,
        .btn-primary:focus,
        .btn-primary.focus {
            color: #fff;
            background-color: {{ $site_settings['theme']['primary'] }};
            border-color: {{ $site_settings['theme']['primary-hover'] }};
        }
        .kt-link {
            color: {{ $site_settings['theme']['primary-hover'] }};
        }
        .kt-link:hover {
            color: #1a6da86c;
        }

        .kt-datatable
            > .kt-datatable__pager
            > .kt-datatable__pager-nav
            > li
            > .kt-datatable__pager-link:hover {
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            background: {{ $site_settings['theme']['primary-hover'] }};
            color: #ffffff;
        }
        .kt-datatable
            > .kt-datatable__pager
            > .kt-datatable__pager-nav
            > li
            > .kt-datatable__pager-link.kt-datatable__pager-link--first:hover,
        .kt-datatable
            > .kt-datatable__pager
            > .kt-datatable__pager-nav
            > li
            > .kt-datatable__pager-link.kt-datatable__pager-link--prev:hover,
        .kt-datatable
            > .kt-datatable__pager
            > .kt-datatable__pager-nav
            > li
            > .kt-datatable__pager-link.kt-datatable__pager-link--next:hover,
        .kt-datatable
            > .kt-datatable__pager
            > .kt-datatable__pager-nav
            > li
            > .kt-datatable__pager-link.kt-datatable__pager-link--last:hover {
            background: {{ $site_settings['theme']['primary-hover'] }};
            color: #ffffff;
        }
        .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__heading .kt-menu__link-text, .kt-aside-menu .kt-menu__nav > .kt-menu__item.kt-menu__item--open > .kt-menu__link .kt-menu__link-text{
            color: {{ $site_settings['theme']['primary'] }};
        }
        .kt-aside-menu .kt-menu__nav > .kt-menu__item:not(.kt-menu__item--parent):not(.kt-menu__item--open):not(.kt-menu__item--here):not(.kt-menu__item--active):hover > .kt-menu__heading .kt-menu__link-text, .kt-aside-menu .kt-menu__nav > .kt-menu__item:not(.kt-menu__item--parent):not(.kt-menu__item--open):not(.kt-menu__item--here):not(.kt-menu__item--active):hover > .kt-menu__link .kt-menu__link-text{
            color: {{ $site_settings['theme']['primary-hover'] }};

        }
        
    </style>
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading {!! app()->getLocale(); !!}">

    <!-- begin::Page loader -->
    <div class="spinner-border e-spinner" id="keen-spinner" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <!-- end::Page Loader -->
    
    <!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
            <div class="kt-header__brand-logo">
                <a href="/app">
                    <img alt="Logo" class="custom-logo" src="{{ global_asset('assets/media/logo.png') }}" />
                </a>
            </div>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
        </div>
    </div>
    <!-- end:: Header Mobile -->
    

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--hor kt-grid--root" id="main-body-div">
        
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page" id="app">
            <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

                <!-- begin:: Aside -->
                <div class="kt-aside__brand kt-grid__item my-0" id="kt_aside_brand">
                    <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
                        <div class="kt-header__brand-logo">
                            <a href="/app">
                                <img alt="Logo" class="custom-logo" src="{{ global_asset('assets/media/logo.png') }}" />
                            </a>
                        </div>
                    </div>
                    <div class="kt-aside__brand-tools">
                        {{-- <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                            <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                                        <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                                    </g>
                                </svg></span>
                            <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                        <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                    </g>
                                </svg></span>
                        </button> --}}

                        <!--
        <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
        -->
                    </div>
                </div>

                
            <!-- begin:: Aside Menu -->
            
            @include('layouts.partials.nav')
            <!-- end:: Aside -->
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper " id="kt_wrapper">
                @include('layouts.partials.header')
                <div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

                        <!-- begin:: Subheader -->

                        <!-- end:: Subheader -->

                        <!-- begin:: Content -->
                        @yield('content')

                        <!-- end:: Content -->
                    </div>
                </div>

                @include('layouts.partials.footer')
            </div>
        </div>
    </div>


    
    <!-- begin::Quick Panel -->
    <div id="kt_quick_panel" class="kt-quick-panel">
        <a href="#" class="kt-quick-panel__close" id="kt_quick_panel_close_btn"><i class="flaticon2-delete"></i></a>
        <div class="kt-quick-panel__nav">
            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand  kt-notification-item-padding-x" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" data-toggle="tab" href="#kt_quick_panel_tab_notifications" role="tab">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_quick_panel_tab_logs" role="tab">Audit Logs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kt_quick_panel_tab_settings" role="tab">Settings</a>
                </li>
            </ul>
        </div>
        <div class="kt-quick-panel__content">
            <div class="tab-content">
                <div class="tab-pane fade show kt-scroll active" id="kt_quick_panel_tab_notifications" role="tabpanel">
                    <div class="kt-notification">
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-line-chart kt-font-success"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New order has been received
                                </div>
                                <div class="kt-notification__item-time">
                                    2 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-box-1 kt-font-brand"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New customer is registered
                                </div>
                                <div class="kt-notification__item-time">
                                    3 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-chart2 kt-font-danger"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    Application has been approved
                                </div>
                                <div class="kt-notification__item-time">
                                    3 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-image-file kt-font-warning"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New file has been uploaded
                                </div>
                                <div class="kt-notification__item-time">
                                    5 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-drop kt-font-info"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New user feedback received
                                </div>
                                <div class="kt-notification__item-time">
                                    8 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-pie-chart-2 kt-font-success"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    System reboot has been successfully completed
                                </div>
                                <div class="kt-notification__item-time">
                                    12 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-favourite kt-font-danger"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New order has been placed
                                </div>
                                <div class="kt-notification__item-time">
                                    15 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item kt-notification__item--read">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-safe kt-font-primary"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    Company meeting canceled
                                </div>
                                <div class="kt-notification__item-time">
                                    19 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-psd kt-font-success"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New report has been received
                                </div>
                                <div class="kt-notification__item-time">
                                    23 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon-download-1 kt-font-danger"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    Finance report has been generated
                                </div>
                                <div class="kt-notification__item-time">
                                    25 hrs ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon-security kt-font-warning"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New customer comment recieved
                                </div>
                                <div class="kt-notification__item-time">
                                    2 days ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification__item">
                            <div class="kt-notification__item-icon">
                                <i class="flaticon2-pie-chart kt-font-warning"></i>
                            </div>
                            <div class="kt-notification__item-details">
                                <div class="kt-notification__item-title">
                                    New customer is registered
                                </div>
                                <div class="kt-notification__item-time">
                                    3 days ago
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="tab-pane fade kt-scroll" id="kt_quick_panel_tab_logs" role="tabpanel">
                    <div class="kt-notification-v2">
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon-bell kt-font-brand"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    5 new user generated report
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    Reports based on sales
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon2-box kt-font-danger"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    2 new items submited
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    by Grog John
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon-psd kt-font-brand"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    79 PSD files generated
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    Reports based on sales
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon2-supermarket kt-font-warning"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    $2900 worth producucts sold
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    Total 234 items
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon-paper-plane-1 kt-font-success"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    4.5h-avarage response time
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    Fostest is Barry
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon2-information kt-font-danger"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    Database server is down
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    10 mins ago
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon2-mail-1 kt-font-brand"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    System report has been generated
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    Fostest is Barry
                                </div>
                            </div>
                        </a>
                        <a href="#" class="kt-notification-v2__item">
                            <div class="kt-notification-v2__item-icon">
                                <i class="flaticon2-hangouts-logo kt-font-warning"></i>
                            </div>
                            <div class="kt-notification-v2__itek-wrapper">
                                <div class="kt-notification-v2__item-title">
                                    4.5h-avarage response time
                                </div>
                                <div class="kt-notification-v2__item-desc">
                                    Fostest is Barry
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="tab-pane kt-quick-panel__content-padding-x fade kt-scroll" id="kt_quick_panel_tab_settings" role="tabpanel">
                    <form class="kt-form">
                        <div class="kt-heading kt-heading--sm kt-heading--space-sm">Customer Care</div>
                        <div class="form-group form-group-xs row">
                            <label class="col-8 col-form-label">Enable Notifications:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--success kt-switch--sm">
                                    <label>
                                        <input type="checkbox" checked="checked" name="quick_panel_notifications_1">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group form-group-xs row">
                            <label class="col-8 col-form-label">Enable Case Tracking:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--success kt-switch--sm">
                                    <label>
                                        <input type="checkbox" name="quick_panel_notifications_2">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group form-group-last form-group-xs row">
                            <label class="col-8 col-form-label">Support Portal:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--success kt-switch--sm">
                                    <label>
                                        <input type="checkbox" checked="checked" name="quick_panel_notifications_2">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
                        <div class="kt-heading kt-heading--sm kt-heading--space-sm">Reports</div>
                        <div class="form-group form-group-xs row">
                            <label class="col-8 col-form-label">Generate Reports:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--sm kt-switch--danger">
                                    <label>
                                        <input type="checkbox" checked="checked" name="quick_panel_notifications_3">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group form-group-xs row">
                            <label class="col-8 col-form-label">Enable Report Export:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--sm kt-switch--danger">
                                    <label>
                                        <input type="checkbox" name="quick_panel_notifications_3">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group form-group-last form-group-xs row">
                            <label class="col-8 col-form-label">Allow Data Collection:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--sm kt-switch--danger">
                                    <label>
                                        <input type="checkbox" checked="checked" name="quick_panel_notifications_4">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
                        <div class="kt-heading kt-heading--sm kt-heading--space-sm">Memebers</div>
                        <div class="form-group form-group-xs row">
                            <label class="col-8 col-form-label">Enable Member singup:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--sm kt-switch--brand">
                                    <label>
                                        <input type="checkbox" checked="checked" name="quick_panel_notifications_5">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group form-group-xs row">
                            <label class="col-8 col-form-label">Allow User Feedbacks:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--sm kt-switch--brand">
                                    <label>
                                        <input type="checkbox" name="quick_panel_notifications_5">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group form-group-last form-group-xs row">
                            <label class="col-8 col-form-label">Enable Customer Portal:</label>
                            <div class="col-4 kt-align-right">
                                <span class="kt-switch kt-switch--sm kt-switch--brand">
                                    <label>
                                        <input type="checkbox" checked="checked" name="quick_panel_notifications_6">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- end::Quick Panel -->

    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>

    <!-- end::Scrolltop -->
    
    <!--begin::Modal-->
	<div class="modal fade" id="kt_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div>
				<div class="modal-body">
					<p></p>
				</div>
				<div class="modal-footer">
					<form action="" method="GET">
						
					<form>
				</div>
			</div>
		</div>
    </div>
    
	<!--end::Modal-->

    <!--ENd:: Chat-->

    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#3d94fb",
                    "light": "#ffffff",
                    "dark": "#282a3c",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#3d94fb",
                    "warning": "#ffb822",
                    "danger": "#fd27eb"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };

    </script>

    <!-- end::Global Config -->
    @plan("large")
    <script src="{{ global_asset('js/app.js') }}"></script>
    @endplan
    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="{{ global_asset('assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ global_asset('assets/js/scripts.bundle.js') }}" type="text/javascript"></script>

    <!--end::Global Theme Bundle -->
    {{-- before stack scripts --}}
    <script>
		var makeModal=function(l,m,h,method,input=null){
			$('#kt_modal_1 .modal-title').html(h);
			$('#kt_modal_1 .modal-body p').html(m);
			$('#kt_modal_1 form').attr('action',l);
			var form=`<button type="button"  class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
						<button type="submit" class="btn btn-primary">{{__('Proceed') }}</button>`;
			if(method!='GET')
			{
				form+='@csrf';
				$('#kt_modal_1 form').attr('method','POST');
			}else{
				$('#kt_modal_1 form').attr('method','GET');
			}
			if(method=='PATCH')
			{
				form+='@method("PATCH")';
			}
			if(method=='DELETE')
			{
				form+='@method("DELETE")';
			}
            if(input){
                $.each(input,function($k,$i){
                    form+=$i;
                })
            }

			$('#kt_modal_1 form').html(form);
			
			$('#kt_modal_1').modal('show');
            
            // $('.kt-form').on('submit',function(){
            //     KTApp.blockPage();

            // });
            
		};
        $(document).on('change', 'input[type="file"]', function(e) {
            if($(this)[0].files.length >= 1) {
                var filename = $(e.currentTarget).val().replace(/^.*\\/, "");
                $(this).next('label.custom-file-label').text(filename);
            }
        });
    </script>

    @stack('scripts')

    {{-- after stack scripts --}}
    <script>
        // search times
		$(document).ready(function(e){
            if($('#generalSearch').length>0){
                $('#generalSearch').parent().addClass('kt-input-icon--right')
                $('#generalSearch').parent().append(`
                    <span class="custom-cross kt-input-icon__icon kt-input-icon__icon--right ">
                        <span><i class="la la-times"></i></span>
                    </span>`);
                if(!$('#generalSearch').val().length){
                    $('.custom-cross').hide();
                }else{

                    $('.custom-cross').show();
                }
                $(document).on('keypress','#generalSearch',function(){

                    // console.log($('#generalSearch').val().length);
                    
                    $('.custom-cross').show();
                    
                })
                $(document).on('click','.custom-cross',function(e){
                    $(this).hide();
                    $('#generalSearch').val('');
                    datatable.search('')
                })
            }

            
		})

        // custom errors
        function makeErrCustom(element,message)
        {
            if(element.length==1){
                var name=element.attr('name')
                element.parent().addClass('is-invalid')
                
                remErrCustom(element)
                element.parent().append(`<div id="${name}-error-custom" class="error invalid-feedback">${message}</div>`)
                
                element.addClass('is-invalid')
                element.val("");
            }else{
                $.each(element, function(i,elem){
                    // console.log(elem)
                    var name=elem.attr('name')
                    elem.parent().addClass('is-invalid')
                    
                    remErrCustom(elem)
                    elem.parent().append(`<div id="${name}-error-custom" class="error invalid-feedback">${message}</div>`)
                    
                    elem.addClass('is-invalid')
                    elem.val("");
                });
            }
            
        }

        function remErrCustom(element)
        {
            if(element.length==1){
                var name=element.attr('name')
                if($("#"+name+"-error-custom").length != 0) {
                    element.removeClass('is-invalid')
                    $("#"+name+"-error-custom").remove();
                }
            }else{
                $.each(element, function(i,elem){
                    var name=elem.attr('name')
                    if($("#"+name+"-error-custom").length != 0) {
                        elem.removeClass('is-invalid')
                        $("#"+name+"-error-custom").remove();
                    }
                });

            }
        }

        $(document).on('click','.custom-select-all',function(e){
            var elem=$(this);
            elem.removeClass('custom-select-all')
            elem.addClass('custom-unselect-all')
            elem.html('Deselect All');
        });
        $(document).on('click','.custom-unselect-all',function(e){
            var elem=$(this);
            elem.removeClass('custom-unselect-all')
            elem.addClass('custom-select-all')
            elem.html('Select All');
        });

        $(window).bind("pageshow", function() {
            
            if($('#generalSearch').length>0){
                $('#generalSearch').val('');
                if(typeof(datatable) != "undefined" && datatable !== null){
                    datatable.search('');
                }
            }

        });

        // search times
		
        if($('#generalSearch').length>0){
            $('#generalSearch').parent().addClass('kt-input-icon--right')
            $('#generalSearch').parent().append(`
                <span class="custom-cross kt-input-icon__icon kt-input-icon__icon--right ">
                    <span><i class="la la-times"></i></span>
                </span>`);
            if(!$('#generalSearch').val().length){
                $('.custom-cross').hide();
            }else{

                $('.custom-cross').show();
            }
            $(document).on('keypress','#generalSearch',function(){

                console.log($('#generalSearch').val().length);
                
                $('.custom-cross').show();
                
            })
            $(document).on('click','.custom-cross',function(e){
                $(this).hide();
                $('#generalSearch').val('');
                datatable.search('')
            })
        }
       
    </script>

    <script src="//d2wy8f7a9ursnm.cloudfront.net/v7/bugsnag.min.js"></script>
    <script>
        
        Bugsnag.start({ 
            apiKey: '386ca2791d00789dad3f5ceb0d812432',
            appVersion: '{{env('APP_VERSION', '1.0.0').env('APP_BUILD', '-beta')}}',
            user: {
                id: '@guest{{"1"}}@else{{auth()->user()->id}}@endauth',
                name: '@guest{{"Alpas Bugger"}}@else{{auth()->user()->name}}@endauth',
                email: '@guest{{"developer.alpas@gmail.com"}}@else{{auth()->user()->email}}@endauth'
            } })

    </script>    
</body>

<!-- end::Body -->
</html>