<template>
    <div>
        <div class="kt-searchbar">
        </div>
        <div class="kt-widget kt-widget--users kt-mt-20">
            <div class="kt-scroll kt-scroll--pull">
                <div class="kt-widget__items">

                    <people-detail-component v-for="(group,index) in groups" @click.native="test(group.id)" :group=group :key="index"></people-detail-component>
                    
                    <p align="center" v-if="!groups.length"> People you chat with will appear here... </p>
                </div>
            </div> 
        </div>
    </div>
</template>

<script>
export default {
    props: ['groups'],
    methods: {
        test(gid) {
            NProgress.start();
            this.$emit('load-messages', gid);
        }
    }
}
</script>

<style>

</style>