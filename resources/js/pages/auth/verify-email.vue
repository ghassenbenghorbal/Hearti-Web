<template>
  <guest-layout>
        <v-row align="center" justify="center" style="height: 100vh">
          <v-col cols="12" sm="12" md="10" lg="4">
            <application-logo/>
            <v-card>
              <v-card-text>
                <p class="font-weight-semibold text--primary mb-2">
                  Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
              </v-card-text>
              <v-card-text>
                <v-form @submit.prevent="submit">
                  <v-btn type="submit" block color="red lighten-1" dark class="mt-3"
                    >Send Verification Email</v-btn
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
      form: this.$inertia.form(),
    };
  },
  methods: {
    submit() {
      this.form.post(this.route('verification.send'));
    },
  },
};
</script>
