<template>
<admin-layout>
    <v-snackbar v-model="$page.props.flash.success" v-if="$page.props.flash.success" timeout="5000" dense bottom right color="success">
        <v-icon dark class="mr-2">
            mdi-checkbox-marked-circle
        </v-icon>
        <b>{{ $page.props.flash.success }}</b>
        <template v-slot:action="{ attrs }">
            <v-icon dark right v-bind="attrs" @click="$page.props.flash.success = false">
                mdi-close-octagon
            </v-icon>
        </template>
    </v-snackbar>
    <div class="px-5 pt-2">
        <div v-for="(item,index) in items" :key="index">
            <v-row>
                <v-col>
                    <div>
                        <div class="text-h6 font-weight-regular">{{item.title}}</div>
                        <div class=" text-subtitle-2 font-weight-light">{{item.subtitle}}</div>
                    </div>
                </v-col>
                <v-col cols="8">
                    <v-card>
                        <v-form @submit.prevent="item.button.method" lazy-validation :ref="item.form" v-model="item.valid">
                            <v-card-text class="pb-2" v-if="$page.props.flash.error && $page.props.flash.form == item.id">
                                <v-alert dense outlined text class="mb-0 mt-2" type="error">{{$page.props.flash.error}}</v-alert>
                            </v-card-text>
                            <v-card-text v-for="(error,index) in requestErrors" :key="index" class="pb-2">
                                <v-alert v-if="error.id == item.id" dense outlined text class="mb-0 mt-2" type="error">{{error.text}}</v-alert>
                            </v-card-text>
                            <!-- User Forms -->
                            <v-card-text v-if="item.id == 0">
                                <v-text-field v-for="input in item.inputs" :key="input.label" v-model="input.data" :label="input.label" :readonly="input.readonly" :rules="[item.rules.required]" outlined dense></v-text-field>
                            </v-card-text>
                            <!-- Bracelet Forms -->
                            <v-card-text v-if="item.id == 2">
                                <v-text-field v-for="input in item.inputs" :key="input.label" v-model="input.data" :label="input.label" :readonly="input.readonly" :rules="[item.rules.required, item.rules.connected]" :type="input.show ? 'text' : 'password'" :append-icon="input.show ? 'mdi-eye' : 'mdi-eye-off'" @click:append="input.show = !input.show" outlined dense></v-text-field>
                            </v-card-text>
                            <!-- Change Password -->
                            <v-card-text v-if="item.id == 1">
                                <v-text-field v-for="input in item.inputs" :key="input.label" v-model="input.data" :label="input.label" :type="input.show ? 'text' : 'password'" :append-icon="input.show ? 'mdi-eye' : 'mdi-eye-off'" @click:append="input.show = !input.show" hint="At least 8 characters" :rules="[item.rules.required, item.rules.min]" counter outlined dense></v-text-field>
                            </v-card-text>
                            <v-card-actions class="justify-end bg-gray">
                                <div>
                                    <v-btn outlined color="red accent-2" type="submit">{{item.button.name}}</v-btn>
                                </div>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
            <v-divider v-if="index < 2" class="my-4" />
        </div>
    </div>
</admin-layout>
</template>

<script>
import AdminLayout from '../layouts/AdminLayout.vue'
import {
    changeNameEmail,
    generateApiToken
} from "../methods/profile/profile.js"
import socketio from "socket.io-client";

export default {
    components: {
        AdminLayout
    },
    props: {
        patient: Object
    },
    data() {
        return {
            socketConnectionError: false,
            items: [{
                    id: 0,
                    valid: true,
                    form: 'profile',
                    title: "Profile Information",
                    subtitle: "Update your account's profile information and email address.",
                    inputs: [{
                            label: "Name",
                            data: "",
                            readonly: false
                        },
                        {
                            label: "Email",
                            data: "",
                            readonly: false
                        }
                    ],
                    rules: {
                        required: value => !!value || 'Required.',
                        min: v => v.length >= 8 || 'Minimum 8 characters',
                    },
                    button: {
                        name: "Save",
                        method: this.changeNameEmail
                    }
                },
                {
                    id: 2,
                    valid: true,
                    form: 'bracelet',
                    title: "Bracelet Information",
                    subtitle: "Update your bracelet URL and secret phrase.",
                    inputs: [{
                            label: "Bracelet URL",
                            data: "",
                            readonly: false,
                            show: true,
                        },
                        {
                            label: "Secret Phrase",
                            data: "",
                            readonly: false,
                            show: false,
                        }
                    ],
                    rules: {
                        required: value => !!value || 'Required.',
                        connected: () => {
                            console.log(this.socketConnectionError)
                            if (this.socketConnectionError)
                                return 'Unable to connect to bracelet.'
                            else
                                return true
                        },
                    },
                    button: {
                        name: "Save",
                        method: this.changeBracelet
                    }
                },
                {
                    id: 1,
                    valid: true,
                    form: 'password',
                    title: "Update Password",
                    subtitle: "Ensure your account is using a long, random password to stay secure.",
                    inputs: [{
                            label: "Current Password",
                            data: "",
                            show: false,
                        },
                        {
                            label: "New Password",
                            data: "",
                            show: false,
                        }

                    ],
                    rules: {
                        required: value => !!value || 'Required.',
                        min: v => v.length >= 8 || 'Minimum 8 characters',
                    },
                    button: {
                        name: "Save",
                        method: this.changePassword
                    }
                },
            ],
            dataSocket: socketio("http://localhost:3001", {
                transports: ['websocket'],
                autoConnect: false,
                debug: true,
            }),
        }
    },
    computed: {
        requestErrors() {
            const errors = []
            if (this.$page.props.errors.hasOwnProperty('email'))
                errors.push({
                    id: 0,
                    text: this.$page.props.errors.email
                })
            if (this.$page.props.errors.hasOwnProperty('name'))
                errors.push({
                    id: 0,
                    text: this.$page.props.errors.name
                })
            if (this.$page.props.errors.hasOwnProperty('token_name'))
                errors.push({
                    id: 2,
                    text: this.$page.props.errors.token_name
                })
            if (this.$page.props.errors.hasOwnProperty('currentPassword'))
                errors.push({
                    id: 1,
                    text: this.$page.props.errors.currentPassword
                })
            if (this.$page.props.errors.hasOwnProperty('newPassword'))
                errors.push({
                    id: 1,
                    text: this.$page.props.errors.newPassword
                })
            return errors
        }
    },
    methods: {
        changeBracelet() {
            this.testConnectionToBracelet()
        },
        async testConnectionToBracelet() {
            if (this.items[1].inputs[0].data && this.items[1].inputs[1].data) {
                this.dataSocket.auth = {
                    braceletId: this.items[1].inputs[1].data,
                    username: this.$page.props.auth.user.email
                };
                this.dataSocket.io.uri = this.items[1].inputs[0].data;
                try {
                    await this.dataSocket.connect();
                } catch (e) {
                    console.log(e)
                }
            }
        },
        changeNameEmail() {
            this.$refs['profile'][0].validate()
            const form = this.$inertia.form({
                name: this.items[0].inputs[0].data,
                email: this.items[0].inputs[1].data,
            })
            form.put(`/profile/change-profile`, {
                preserveScroll: true
            });
        },
        changePassword() {
            this.$refs['password'][0].validate()
            const form = this.$inertia.form({
                currentPassword: this.items[1].inputs[0].data,
                newPassword: this.items[1].inputs[1].data,
            })
            form.put(`/profile/change-password`, {
                preserveScroll: true
            });
        },
        // getApiToken() {
        //     this.$refs['token'][0].validate()
        //     generateApiToken(this.items[2].inputs[0].data).then(resp => {
        //         this.items[2].inputs[1].data = resp
        //     })
        // }

    },
    mounted() {
        this.items[0].inputs[0].data = this.$page.props.auth.user.name
        this.items[0].inputs[1].data = this.$page.props.auth.user.email
        this.items[1].inputs[0].data = this.patient.bracelet_url
        this.items[1].inputs[1].data = this.patient.secret_phrase
        this.dataSocket.on('connect', (data) => {
            // Fired when the socket connects.
            console.log("connected")
            this.dataSocket.disconnect(true)
            const form = this.$inertia.form({
                bracelet_url: this.items[1].inputs[0].data,
                secret_phrase: this.items[1].inputs[1].data,
            })
            form.put(route('profile.bracelet'), {
                onError: () => {
                    this.processing = false;
                },
                onSuccess: () => {
                    this.processing = false;
                },
                onFinish: () => {
                    this.processing = false;
                },
                preserveScroll: true
            });
        })
        this.dataSocket.on('connect_error', (data) => {
            console.log("connection_error")
            this.socketConnectionError = true;
            this.$refs['bracelet'][0].validate()
            this.dataSocket.disconnect(true)
            this.processing = false;
            // Fired when couldn’t establish a connection with the server.

        })
        this.dataSocket.on('disconnect', (data) => {
            // Fired when the socket disconnects.
            console.log("disconnect")
        })
    },
    beforeDestroy() {
        this.dataSocket.disconnect(true)
    },

}
</script>

<style>
.bg-gray {
    --tw-bg-opacity: 1;
    background-color: rgb(249 250 251 / var(--tw-bg-opacity));
}
</style>
