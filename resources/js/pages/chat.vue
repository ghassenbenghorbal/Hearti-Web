<template>
    <admin-layout>
        <div style="height:100%" class="d-flex flex-column">
            <!-- <v-snackbar v-model="sick" v-if="true" timeout="-1" dense top right color="error">
                <b>Patient is sick!</b>
            </v-snackbar> -->
            <!-- Customization -->
    
            
            <!-- Laptop and above -->
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
        props: {
        },
        data() {
            return {

            };
        },
        methods: {
        },
        computed: {
        },
        watch: {
        },
        mounted() {
        },
        beforeDestroy() {
        }
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
    