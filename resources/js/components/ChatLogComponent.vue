<template>
    <div class="kt-portlet kt-portlet--head-lg kt-portlet--last">
        <div class="kt-portlet__head">
            <div class="kt-chat__head ">
                <div class="kt-chat__left">
                </div>
                <div class="kt-chat__center">
                    <div class="kt-chat__label" v-if="latest != ''">
                        <a href="#" class="kt-chat__title">{{ latest.user.name }}</a>
                    </div>
                    <div class="kt-chat__pic kt-hidden">
                        <span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="right" title="Jason Muller" data-original-title="Tooltip title">
                            <img src="assets/media/users/300_12.jpg" alt="image">
                        </span>
                        <span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="right" title="Nick Bold" data-original-title="Tooltip title">
                            <img src="assets/media/users/300_11.jpg" alt="image">
                        </span>
                        <span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="right" title="Milano Esco" data-original-title="Tooltip title">
                            <img src="assets/media/users/100_14.jpg" alt="image">
                        </span>
                        <span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="right" title="Teresa Fox" data-original-title="Tooltip title">
                            <img src="assets/media/users/100_4.jpg" alt="image">
                        </span>
                    </div>
                </div>
                <div class="kt-chat__right">
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="flaticon2-add-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-md">

                            <!--begin::Nav-->
                            <ul class="kt-nav" v-if="chatters">
                                <li class="kt-nav__head">
                                    Chat with...
                                </li>
                                <li class="kt-nav__separator"></li>
                                <div class="kt-scroll">
                                    <li class="kt-nav__item" v-for="(chatter, index) in chatters" :key="index">
                                        <a @click="chatwith(chatter.id, index)" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon-add-label-button"></i>
                                            <span class="kt-nav__link-text">{{ chatter.name }}</span>
                                        </a>
                                    </li>
                                </div>
                            </ul>

                            <!--end::Nav-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">
            <div class="custom-scroll p-3" data-mobile-height="300" id="scroll-this" ref="chatDiv" v-if="messages" v-chat-scroll>
                <chat-messages-component v-for="(message,index) in messages" :message=message :auth="authuser" :key="index"></chat-messages-component>

                <p align="center" v-if="!messages.length"> No messages. </p>
            </div>
            <span class="text-muted p-2" v-if="active" >{{ active }} is typing...</span>
        </div>
        
        <chat-composer-component v-if="latest != ''" @messagesent="addmessage" @typingevent="sendTyping" :auth="authuser"></chat-composer-component>
    </div>
</template>

<script>
import VueChatScroll from 'vue-chat-scroll';

Vue.use(VueChatScroll);

export default {
    props: ['messages', 'authuser', 'activegroup', 'latest', 'active', 'chatters', 'groups'],
    updated() {
        var el = this.$refs.chatDiv;
        el.style.overflow='auto';
        el.scrollTop = el.scrollHeight;
    },
    methods: {
        addmessage(message) {
            message.group_id = this.activegroup;
            
            // Add to existing messages
            this.messages.push(message);

            // Persist to the database
            axios.post('/api/messages', message);
        },

        sendTyping() {
            Echo.join('privatechat.' + this.activegroup)
                .whisper('typing', this.latest.user);
            
            // console.log(user.name + ' is typing now')
        },

        chatwith(id, index) {
            NProgress.start();

            this.chatters.splice(index, 1);

            axios.get('/api/groups/create/'+id)
                .then(response => {
                    let newgroup = {
                        id: response.data.group.id,
                        name: response.data.user.name,
                        image: response.data.user.image
                    }
                    this.groups.push(newgroup);
                    
                    this.$emit('load-messages', response.data.group.id);
                });

            var el = this.$refs.chatDiv;
            el.style.overflow='auto';
            el.scrollTop = el.scrollHeight;
        }
    },
}
</script>

<style scoped>
    .custom-scroll {
        overflow-y: scroll;
        max-height: 170px;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>