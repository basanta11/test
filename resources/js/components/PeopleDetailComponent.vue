<template>
    <div class="kt-widget__item clickable" :data-group-id="group.id">
        <span class="kt-media kt-media--circle">
            <img :src="group.image" alt="image">
        </span>
        <div class="kt-widget__info">
            <div class="kt-widget__section mt-2">
                <a href="#" class="kt-widget__username">{{ group.name }}</a>
                <!-- <span class="kt-badge kt-badge--success kt-badge--dot"></span> -->
            </div>
        </div>
        <!-- <div class="kt-widget__action">
            <span class="kt-widget__date">36 Mines</span>
            <span class="kt-badge kt-badge--success kt-font-bold">7</span>
        </div> -->
    </div>
</template>

<script>
export default {
    props: ['group']
}
</script>

<style>
    .clickable {
        cursor: pointer;
    }
    
    .clickable:hover {
        opacity: 0.8;
    }
</style>