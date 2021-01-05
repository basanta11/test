const { default: Axios } = require('axios');
const { default: Echo } = require('laravel-echo');

require('./bootstrap');

window.Vue = require('vue');

Vue.component('people-component', require('./components/PeopleComponent.vue').default);
Vue.component('people-search-component', require('./components/PeopleSearchComponent.vue').default);
Vue.component('people-detail-component', require('./components/PeopleDetailComponent.vue').default);
Vue.component('chat-log-component', require('./components/ChatLogComponent.vue').default);
Vue.component('chat-composer-component', require('./components/ChatComposerComponent.vue').default);
Vue.component('chat-messages-component', require('./components/ChatMessagesComponent.vue').default);
Vue.component('notification', require('./components/Notification.vue').default);

import VueChatScroll from 'vue-chat-scroll';
import VueAxios from 'vue-axios';

Vue.use(VueChatScroll);
Vue.use(require('vue-moment'));
Vue.use(VueAxios, axios);

const app = new Vue({
    el: '#app',
    data: {
        messages : [],
        groups : [],
        usersInRoom: [],
        authuser: '',
        activegroup: 0,
        latest: '',
        chatters: '',
        activeUser: false,
        typingTimer: false,
        componentKey: 0
    },
    created() {
        if (window.location.href.indexOf("chat") > -1) {
            this.fetchEverything();
        }
    },
    methods: {
        fetchEverything() {
            axios.get('/api/getEverything').then(response => {
                // console.log(response);
                this.messages = response.data.result.messages;
                this.authuser = response.data.result.authuser;
                this.groups = response.data.result.groups;
                this.activegroup = response.data.result.latestChat;
                this.latest = response.data.result.latestChatDetails;
                this.chatters = response.data.result.chatters;

                if (this.activegroup != 0) {
                    this.listenForNewMessages(this.activegroup);
                }
            });
        },

        listenForNewMessages(ag) {
            // console.log('here');
            window.Echo.join('privatechat.' + ag)
                .here((users) => {
                    this.usersInRoom = users;
                })
                .joining((user) => {
                    this.usersInRoom.push(user);
                })
                .leaving((user) => {
                    this.usersInRoom = this.usersInRoom.filter(u => u != user)
                })
                .listen('MessageSent', (e) => {
                    if (this.messages == null) {
                        this.messages = [];
                    }

                    if ( ag == e.groupId ) {
                        this.messages.push({
                            message: e.message.message,
                            user: {
                                id: e.user.id,
                                name: e.user.name,
                                image: e.user.image
                            }
                        });
                    }
                })
                .listenForWhisper('typing', (user) => {
                    this.activeUser = this.latest.user.name;
                    if(this.typingTimer) {
                        clearTimeout(this.typingTimer);
                    }
                    this.typingTimer = setTimeout(() => {
                        this.activeUser = false;
                    }, 1000);
                });
        },

        loadnew(groupId) {
            window.Echo.leave('privatechat.' + this.activegroup);

            axios.get('/api/load-messages/'+groupId).then(response => {
                this.messages = response.data.messages;
                this.latest = response.data.groupuser;
                this.activegroup = groupId;
                
                this.componentKey += 1;
                
                this.listenForNewMessages(groupId);
                NProgress.done();
            });
        }
    }
});
