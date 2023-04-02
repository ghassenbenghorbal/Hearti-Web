<template>
<admin-layout>
    <div style="height:100%" class="d-flex">
        <div style="height:100%;width:30%;" class="px-1">
            <v-card outlined rounded="xl" style="height:100%;">

            </v-card>
        </div>

        <div style="height:100%;" class="px-1 flex-grow-1">
            <v-card outlined rounded="xl" style="height:100%;">

            </v-card>
        </div>
    </div>
</admin-layout>
</template>

    
<script>
import AdminLayout from "../layouts/AdminLayout.vue";
export default {
    components: {
        AdminLayout
    },
    inject: {
        theme: {
            default: {
                isDark: false
            }
        }
    },
    props: {},
    data() {
        return {

        };
    },
    methods: {},
    computed: {},
    watch: {},
    mounted() {},
    beforeDestroy() {}
};
</script>

    
<style>
.imgstyle {
    width: 15% !important;
    aspect-ratio: 3/2 !important;
    object-fit: contain !important;
    mix-blend-mode: color-burn !important;
}
</style>
