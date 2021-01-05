<template>
    <div class="kt-chat__messages">
        <div class="kt-chat__message kt-chat__message--right" v-if="message.user.id == auth.id">
            <div class="kt-chat__user">
                <span class="kt-chat__datetime">{{ message.created_at }}</span>
                <a href="" class="kt-chat__username">You</a>
                <span class="kt-media kt-media--circle kt-media--sm">
                    <img :src="message.user.image" alt="image">
                </span>
            </div>
            <div class="kt-chat__text kt-bg-light-brand">
                {{ message.message }}
            </div>
        </div>

        <div class="kt-chat__message" v-else>
            <div class="kt-chat__user">
                <span class="kt-media kt-media--circle kt-media--sm">
                    <img :src="message.user.image" alt="image">
                </span>
                <a href="" class="kt-chat__username">{{ message.user.name }}</a>
                <span class="kt-chat__datetime">{{ message.created_at }}</span>
            </div>
            <div class="kt-chat__text kt-bg-light-success">
                {{ message.message }}
            </div>
        </div>
    </div> 
</template>

<script>
export default {
    props: ['message', 'auth'],
}
</script>

<style>

</style>