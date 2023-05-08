<template>
<guest-layout>
    <v-row align="center" justify="center" style="height: 100vh">
        <v-col cols="7" sm="6" md="5" lg="4" xl="4">
            <application-logo />
            <v-card>
                <v-card-text>
                    <v-form @submit.prevent="register">
                        <v-select v-model="form.is_patient" :items="isPatientItems" prepend-inner-icon="mdi-account" label="Are you a ..." outlined dense :error-messages="form.errors.is_patient" />

                        <div v-if="form.is_patient">
                            <v-text-field v-model="form.bracelet_url" prepend-inner-icon="mdi-link" label="Bracelet URL" :error-messages="form.errors.bracelet_url" type="text" outlined dense />
                            <v-text-field v-model="form.secret_phrase" prepend-inner-icon="mdi-shield-lock" label="Secret Phrase" :error-messages="form.errors.secret_phrase" type="password" outlined dense />
                        </div>

                        <v-text-field v-model="form.name" prepend-inner-icon="mdi-account" label="Name" outlined dense type="text" :error-messages="form.errors.name" />
                        <v-text-field v-model="form.email" prepend-inner-icon="mdi-email" label="Email" type="email" outlined dense :error-messages="form.errors.email" />
                        <v-text-field v-model="form.password" prepend-inner-icon="mdi-lock" label="Password" outlined dense :error-messages="form.errors.password" :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" :type="showPassword ? 'text' : 'password'" @click:append="showPassword = !showPassword" />
                        <v-text-field v-model="form.password_confirmation" prepend-inner-icon="mdi-lock" label="Password Confirmation" :error-messages="form.errors.password_confirmation" outlined dense :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'" :type="showPassword ? 'text' : 'password'" @click:append="showPassword = !showPassword" />
                        <v-btn :loading="form.processing" type="submit" block color="red lighten-1" dark class="mt-3">Register</v-btn>
                    </v-form>
                </v-card-text>
                <v-card-text class="d-flex align-center justify-center flex-wrap ">
                    <span class="me-2"> Already have an account? </span>
                    <Link :href="route('login')"> Sign in instead </Link>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</guest-layout>
</template>

<script>
import ApplicationLogo from "../../components/ApplicationLogo.vue";
import GuestLayout from '../../layouts/GuestLayout.vue';
import socketio from "socket.io-client";

export default {
    components: {
        ApplicationLogo,
        GuestLayout
    },
    data() {
        return {
            showPassword: false,
            isLoading: false,
            isPatientItems: [{
                text: 'Patient',
                value: 1
            }, {
                text: 'Doctor',
                value: 0
            }],
            isConnecting: false,
            socketConnectionError: false,
            form: this.$inertia.form({
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
                is_patient: 1,
                bracelet_url: null,
                secret_phrase: null,
                connected: false,
                processing: false,
            }),
            dataSocket: socketio("http://localhost:3001", {
                transports: ['websocket'],
                autoConnect: false,
                debug: true,
            }),
        };
    },
    methods: {
        register() {
            this.processing = true;
            if (this.form.is_patient) {
                this.testConnectionToBracelet()
                if (this.form.connected)
                    console.log("hello")

                // this.form.post("/register");
            } else {
                this.form.post("/register");
            }
        },
        testConnectionToBracelet() {
            if (this.form.bracelet_url && this.form.secret_phrase) {
                this.isConnecting = true;
                this.dataSocket.auth = {
                    braceletId: this.form.secret_phrase,
                    username: this.form.email
                };
                this.dataSocket.io.uri = this.form.bracelet_url;
                try {
                    this.dataSocket.connect();
                } catch (e) {
                    console.log(e)
                    this.socketConnectionError = true;
                    this.isConnecting = false;
                }
            }
        }
    },
    mounted() {
        this.dataSocket.on('connect', (data) => {
            // Fired when the socket connects.
            console.log("connected")
            this.processing = false;
            this.dataSocket.disconnect(true)
            this.form.post("/register", {
                onError: () => {
                    this.processing = false;
                },
                onSuccess: () => {
                    this.processing = false;
                },
                onFinish: () => {
                    this.processing = false;
                },
            })
            this.isConnecting = false;
            this.form.connected = true;
            this.socketConnectionError = false;

        })
        this.dataSocket.on('connect_error', (data) => {
            this.dataSocket.disconnect(true)
            this.processing = false;
            this.form.errors.bracelet_url = ["Could not connect to the bracelet"];
            // Fired when couldn’t establish a connection with the server.
            console.log("connection_error")
            this.isConnecting = false;
            this.form.connected = false;
            this.socketConnectionError = true;
        })
        this.dataSocket.on('disconnect', (data) => {
            // Fired when the socket disconnects.
            console.log("disconnect")
            this.form.connected = false;
        })
    },
    beforeDestroy() {
        this.dataSocket.disconnect(true)
    },
};
</script>
