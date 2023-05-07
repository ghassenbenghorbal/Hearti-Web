<template>
  <guest-layout>
        <v-row align="center" justify="center" style="height: 100vh">
          <v-col cols="12" sm="12" md="10" lg="4">
            <application-logo/>
            <v-card>
              <v-card-text>
                <v-form @submit.prevent="register">
                  <v-select
                    v-model="form.is_patient"
                    :items="isPatientItems"
                    prepend-inner-icon="mdi-account"
                    label="Are you a ..."
                    outlined
                    dense
                    :error-messages="form.errors.is_patient"
                  />
                  <v-text-field
                    v-model="form.name"
                    prepend-inner-icon="mdi-account"
                    label="Name"
                    outlined
                    dense
                    type="text"
                    :error-messages="form.errors.name"
                  />
                  <v-text-field
                    v-model="form.email"
                    prepend-inner-icon="mdi-email"
                    label="Email"
                    type="email"
                    outlined
                    dense
                    :error-messages="form.errors.email"
                  />
                  <v-text-field
                    v-model="form.password"
                    prepend-inner-icon="mdi-lock"
                    label="Password"
                    outlined
                    dense
                    :error-messages="form.errors.password"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    @click:append="showPassword = !showPassword"
                  />
                  <v-text-field
                    v-model="form.password_confirmation"
                    prepend-inner-icon="mdi-lock"
                    label="Password Confirmation"
                    :error-messages="form.errors.password_confirmation"
                    outlined
                    dense
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    @click:append="showPassword = !showPassword"
                  />
                  <v-btn :loading="form.processing" type="submit" block color="red lighten-1" dark class="mt-3"
                    >Register</v-btn
                  >
                </v-form>
              </v-card-text>
              <v-card-text
                class="d-flex align-center justify-center flex-wrap "
              >
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
export default {
  components: { ApplicationLogo, GuestLayout },
  data() {
    return {
      showPassword: false,
      isLoading: false,
      isPatientItems: [{text:'Patient',value:1}, {text:'Doctor',value:0}],
      form: this.$inertia.form({
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
        is_patient: 1,
      }),
    };
  },
  methods: {
    register() {
      this.form.post("/register");
    },
  },
};
</script>
