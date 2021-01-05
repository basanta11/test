<template>
    <div class="kt-portlet__foot">
        <div class="kt-chat__input">
            <div class="kt-chat__editor">
                <textarea style="height: 50px" placeholder="Type here..." v-model="messageText" @keyup="sendTypingEvent" @keydown.enter.exact.prevent @keyup.enter.exact="sendMessage"></textarea>
            </div>
            <div class="kt-chat__toolbar">
                <div class="kt_chat__actions">
                    <button type="button" class="btn btn-brand btn-md btn-upper btn-bold kt-chat__reply" @click="sendMessage">reply</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['auth'],
    data() {
        return {
            messageText: ''
        }
    },
    methods: {
        sendMessage(e) {
            if (this.messageText.length >= 1) {
                this.$emit('messagesent', {
                    message: this.messageText,
                    group_id: this.activegroup,
                    user: {
                        id: this.auth.id,
                        name: this.auth.name,
                        image: this.auth.image,
                    }
                });
                this.messageText = "";
            }
        },

        sendTypingEvent() {
            this.$emit('typingevent');
        }
    }
}
</script>

<style>

</style>