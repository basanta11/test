<template>
<div class="kt-header__topbar-item dropdown">
    <div class="kt-header__topbar-wrapper" v-on:click="notificationClick" data-toggle="dropdown" data-offset="30px,0px" aria-expanded="true">
        <span class="kt-header__topbar-icon kt-pulse kt-pulse--brand">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <path d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z" id="Combined-Shape" fill="#000000"/>
                    <rect id="Rectangle-23" fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4" rx="2"/>
                </g>
            </svg>
            <div v-if="unreadNotifications.length >'0'" style="bottom: 25px;position: absolute;right: 2px;">
                <span class="kt-badge kt-badge--danger">
                    {{ unreadNotifications.length }}
                </span>      
            </div>
            <span v-if="unreadNotifications.length >'0'" class="kt-pulse__ring"></span>
        
        </span>

    </div>
    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-lg">
        <!--begin: Head -->
        <div class="kt-head kt-head--skin-dark kt-head--fit-x py-5" style="background-image: url(/assets/media/misc/bg-1.jpg)">
            <h3 class="kt-head__title ">
                Notifications
            </h3>
           
        </div>

        <!--end: Head -->
        <div class="tab-content">
            <div class="tab-pane active show" id="topbar_notifications_notifications" role="tabpanel">
                <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll ps" data-scroll="true" data-height="300" data-mobile-height="200" style="height: 300px; overflow: hidden;">
                    <div v-for="n in totalNotifications">
                        <a v-bind:href="n.data.model_link" class="kt-notification__item remove-after" :class="{ 'kt-shape-font-color-1  kt-shape-bg-color-1' : n.read_at == null}">
                            <div class="kt-notification__item-details" >
                                <div class="kt-notification__item-title" v-html="n.data.notification">
                                  
                                </div>
                                <div class="kt-notification__item-time">
                                    {{ n.created_at | moment('YYYY-MM-DD HH:mm:ss') }}

                                </div>
                            </div>
                        </a>
                    </div>


                    
                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
            </div>
            <a href="/notifications" class="">
                <div class="py-3 d-flex justify-content-center allign-items-center">
                    All Notification
                </div>
            </a>
        </div>
    </div>
</div>


</template>

<script>

// import func from '../../../vue-temp/vue-editor-bridge';
    export default {
        props: ['unreads', 'user','notifications'],
        data() {
            return {
                totalNotifications: this.notifications,
                unreadNotifications: this.unreads
            }
        },
        mounted() {
            // console.log(`users.${this.user}`)
            Echo.channel(`notification.users.${this.user}`).listen('ActionNotification',(notification) => {
                // console.log('message',notification);
                let newMessage = { data:{
                    model_link:notification.data.model_link,
                    notification:notification.data.notification
                    },
                    created_at:notification.data.created_at,
                    read_at:null
                };
                this.totalNotifications.unshift(newMessage);
                this.unreadNotifications.unshift(newMessage);

            }); 
            // Echo.channel(`private-local.3`).listen('BroadcastNotificationCreated',(notification) => {
            //     console.log('message',notification);
            //     let newMessage = { data:{model_link:notification.model_link,notification:notification.notification},created_at:notification.created_at,read_at:null};
            //     this.totalNotifications.unshift(newMessage);
            //     this.unreadNotifications.unshift(newMessage);

            // });
            var notificaiton=this.unreadNotifications;
            
        },
        methods: {
            notificationClick: function(event){
                this.$http.post(
                    '/api/notification-read', 
                    { 
                        unreads: this.unreadNotifications,
                    }
                );
                this.unreadNotifications=[];
            }
        }
    }
</script>
