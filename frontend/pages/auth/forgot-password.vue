<template>
  <div>
    <v-card class="text-center pa-1">
      <template  v-if="step===0">
        <v-card-title class="justify-center display-1 mb-2">{{ $t('forgot.title') }}</v-card-title>
        <v-card-subtitle>
          {{ $t('forgot.subtitle') }}
        </v-card-subtitle>

        <!-- reset form -->
        <v-card-text>
          <validation-observer ref="observer" v-slot="{ invalid }">
            <v-form ref="form" v-model="isFormValid" lazy-validation @submit.prevent="submit">
              <validation-provider
                v-slot="{ errors }"
                name="phone number"
                vid="phone number"
                rules="max:13|required"
              >
                <v-text-field
                  v-model="email"
                  :error-messages="errors"
                  label="Phone Number"
                  hide-details="auto"
                  placeholder="015XXXXXXXX"
                  outlined
                ></v-text-field>
              </validation-provider>
              <v-btn
                style="margin-top: 15px;"
                :disabled="invalid"
                :loading="isLoading"
                block
                x-large
                color="primary"
                @click="submit"
              >{{ $t('forgot.button') }}</v-btn>
            </v-form>
          </validation-observer>
        </v-card-text>
      </template>
      <template v-if="step===1">
        <v-card-title class="justify-center display-1 mb-2">{{ 'OTP' }}</v-card-title>

        <!-- reset form -->
        <v-card-text>
          <validation-observer ref="observer2" v-slot="{ invalid }">
            <v-form ref="form2" v-model="isFormValid" lazy-validation @submit.prevent="submit">
              <validation-provider
                v-slot="{ errors }"
                name="otp"
                vid="otp"
                rules="max:6|required"
              >
                <v-otp-input
                  v-model="token"
                  length="6"
                  type="number"
                ></v-otp-input>
              </validation-provider>
              <v-btn
                style="margin-top: 15px;"
                :disabled="invalid"
                :loading="isLoading"
                block
                x-large
                color="primary"
                @click="verifyToken"
              >{{ 'Submit' }}</v-btn>
            </v-form>
          </validation-observer>
        </v-card-text>
      </template>
      <template v-if="step===2">
        <new-password :access_token="access_token" :access_name="access_name"></new-password>
      </template>

      <div class="text-center my-2">
        <router-link :to="localePath('/auth/signin')" style="text-decoration: none;">
          {{ $t('forgot.backtosign') }}
        </router-link>
      </div>

    </v-card>
  </div>
</template>

<script>
/*
|---------------------------------------------------------------------
| Forgot Page Component
|---------------------------------------------------------------------
|
| Template to send email to remember/replace password
|
*/
import NewPassword from "@/components/auth/NewPassword";
export default {
  layout: 'auth',
  auth: false,
  components:{NewPassword},
  data() {
    return {
      // reset button
      isLoading: false,

      // form
      isFormValid: true,
      email: '',
      access_token:'',
      access_name:'',

      // form error
      error: false,
      errorMessages: '',
      step:0,
      token:null,

      // input rules
      rules: {
        required: (value) => (value && Boolean(value)) || 'Required'
      }
    }
  },
  methods: {
    submit(e) {
      if (this.$refs.form.validate()) {
        this.isLoading=true
       this.$axios
        .get(`/forgot-password/${this.email}`)
        .then((response) => {
          if (response.data.status == 'success') {
            this.$toaster.success(response.data.data);
            this.step=1
          } else {
            this.$toaster.error(response.data.data);
          }
        })
        .catch(() => {
          this.$toaster.error('Invalid request');
        }).finally(()=>{
         this.isLoading=false
       })
      }
    },
    verifyToken(e) {
      if (this.$refs.form2.validate()) {
        this.isLoading=true
        let data = new FormData();
        data.append('phone_number',this.email)
        data.append('token',this.token)
        this.$axios
          .post(`/forgot-password/verify/token`,data)
          .then((response) => {
            if (response.data.status == 'success') {
              //this.$toaster.success(response.data.data);
              let dd = response.data.data
              this.access_token = dd.token;
              this.access_name = dd.name;
              this.step=2
            } else {
              this.$toaster.error(response.data.data);
            }
          })
          .catch(() => {
            if (error.response.status === 422) {
              this.$refs.observer.setErrors(error?.response?.data?.errors)
              Object.keys(error.response.data.errors).map((field) => {
                this.$toaster.error(error.response.data.errors[field][0]);
              });
            } else this.$toaster.error(error.response.data.message);
          }).finally(()=>{
            this.isLoading=false
        })
      }
    },
    resetEmail(email, password) {
    },
    resetErrors() {
      this.error = false
      this.errorMessages = ''
    },
  }
}
</script>
