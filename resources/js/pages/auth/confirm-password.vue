<template>
  <guest-layout>
        <v-row align="center" justify="center" style="height: 100vh">
          <v-col cols="12" sm="12" md="10" lg="4">
            <application-logo/>
            <v-card>
              <v-card-text>
                <p class="mb-2">This is a secure area of the application. Please confirm your password before continuing.</p>
              </v-card-text>
              <v-card-text>
                <v-form @submit.prevent="submit">
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
                  <v-btn :loading="form.processing" type="submit" block color="red lighten-1" dark class="mt-3"
                    >Change Password</v-btn
                  >
                </v-form>
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
      form: this.$inertia.form({
        password: null
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(this.route('password.confirm'));
    },
  },
};
</script>
