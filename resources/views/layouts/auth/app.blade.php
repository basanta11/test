<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title',config('app.name'))</title>
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

    <!--end::Fonts -->
    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->
    <link href="{{ global_asset('assets/css/pages/login/login-5.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Page Custom Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ global_asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ global_asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ global_asset('public/logo.png') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ global_asset('css/custom-elearning.css') }}">
    @stack('styles')
    <style>
        .custom-file-label::after {
            content: "{{ __('Browse') }}" !important;
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
<body class="kt-page--loading-enabled kt-page--loading kt-header--fixed kt-header--minimize-topbar kt-header-mobile--fixed kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-subheader--enabled kt-subheader--transparent kt-page--loading">

    <!-- begin::Page loader -->

    <!-- end::Page Loader -->

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root kt-page">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v5 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile" style="background-image: url(assets/media/bg/bg-3.jpg);">
                {{-- start --}}
                @yield('content')
                {{-- end --}}
            </div>
        </div>
    </div>

    <!-- end:: Page -->

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

    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="{{ global_asset('assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ global_asset('assets/js/scripts.bundle.js') }}" type="text/javascript"></script>

    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ global_asset('assets/js/pages/custom/login/login-general.js') }}" type="text/javascript"></script>
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('change', 'input[type="file"]', function(e) {
                var filename = $(e.currentTarget).val().replace(/^.*\\/, "");
                $(this).next('label.custom-file-label').text(filename);
            });
        });
    </script>
    <!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>