@extends('layouts.main')
@section('title','Chat | '. config("app.name"))
@section('chats','kt-menu__item--open')
@section('content')
    @include('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Chat'),
        'crumbs' => [
            [
                'name' => __('Chat'),
                'url' => '/chat'
            ],
        ]
    ])


    <div class="kt-container kt-grid__item kt-grid__item--fluid">
        @include('layouts.partials.flash-message')

        <!--Begin::App-->
        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

            <!--Begin:: App Aside Mobile Toggle-->
            <button class="kt-app__aside-close" id="kt_chat_aside_close">
                <i class="la la-close"></i>
            </button>

            <!--End:: App Aside Mobile Toggle-->

            <!--Begin:: App Aside-->
            <div class="kt-grid__item kt-app__toggle kt-app__aside kt-app__aside--lg kt-app__aside--fit" id="kt_chat_aside">

                <!--begin::Portlet-->
                <div class="kt-portlet kt-portlet--last">
                    <div class="kt-portlet__body">
                        <people-component :groups="groups" @load-messages="loadnew"></people-component>
                    </div>
                </div>

                <!--end::Portlet-->
            </div>

            <!--End:: App Aside-->

            <!--Begin:: App Content-->
            <div class="kt-grid__item kt-grid__item--fluid kt-app__content" id="kt_chat_content">
                <div class="kt-chat">
                    <chat-log-component 
                        :messages="messages" 
                        :chatters="chatters" 
                        :activegroup="activegroup" 
                        :authuser="authuser" 
                        :active="activeUser" 
                        :latest="latest"
                        :groups="groups"
                        :key="componentKey"
                        @load-messages="loadnew"
                    >
                    </chat-log-component>                        
                </div>
            </div>

            <!--End:: App Content-->
        </div>

        <!--End::App-->
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <script src="{{ global_asset('assets/js/pages/custom/chat/chat.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ global_asset('js/app.js') }}"></script> --}}
@endpush