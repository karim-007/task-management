<template>
  <div>
    <v-card class="text-center pa-1">
      <v-card-title class="justify-center title mb-2">Sign up</v-card-title>

      <!-- sign in form -->
      <v-card-text>
        <validation-observer ref="observer" v-slot="{ invalid, validate }">
          <v-form ref="form" @submit.prevent="submit()" lazy-validation>
            <validation-provider
                v-slot="{ errors }"
                name="name"
                vid="name"
                rules="required|max:191"
            >
              <v-text-field
                  v-model="form.name"
                  :validate-on-blur="false"
                  :label="'name'"
                  :error-messages="errors"
                  name="name"
                  dense
                  outlined
                  auto-grow
                  no-resize
                  @keyup.enter="submit"
              />
            </validation-provider>
            <validation-provider
              v-slot="{ errors }"
              name="email"
              vid="email"
              rules="required|email"
            >
              <v-text-field
                v-model="form.email"
                :validate-on-blur="false"
                :label="'Email'"
                :error-messages="errors"
                name="email"
                dense
                outlined
                auto-grow
                no-resize
                @keyup.enter="submit"
              />
            </validation-provider>

            <validation-provider
              v-slot="{ errors }"
              name="password"
              vid="password"
              rules="required"
            >
              <v-text-field
                v-model="form.password"
                :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[rules.required]"
                :type="showPassword ? 'text' : 'password'"
                :error-messages="errors"
                :label="$t('login.password')"
                name="password"
                dense
                outlined
                auto-grow
                no-resize
                @keyup.enter="submit"
                @click:append="showPassword = !showPassword"
              />
            </validation-provider>

            <v-btn
              :loading="isLoading"
              :disabled="isSignInDisabled"
              block
              x-large
              dense
              color="primary"
              @click="submit"
            >{{ 'Submit'}}
            </v-btn>
            <div class="mt-5">
              <router-link :to="localePath('/auth/signin')" style="text-decoration: none;">
                {{ $t('login.button') }}
              </router-link>
            </div>
          </v-form>
        </validation-observer>
      </v-card-text>
    </v-card>

  </div>
</template>

<script>
import {common as commonMixin} from "@/mixins/common";
export default {
  name:'Registration',
  layout: 'auth',
  auth:false,
  mixins: [commonMixin],
  data() {
    return {
      // sign in buttons
      isLoading: false,
      isSignInDisabled: false,
      // form
      form: {
        name: null,
        email: null,
        password: null,
      },
      // show password field
      showPassword: false,

      // input rules
      rules: {
        required: (value) => (value && Boolean(value)) || 'Required'
      }
    }
  },
  methods: {
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true
        this.isSignInDisabled = true
        this.signUp()
      }
    },
    check() {
      if (this.$auth.user) {
        this.$router.push('/dashboard')
      }
    },
    signUp() {
      const formData = new FormData()

      formData.append('name', this.form.name)
      formData.append('email', this.form.email)
      formData.append('password', this.form.password)

      this.$axios.post('/registration', formData)
        .then((response) => {
          this.$toaster.success('Registration success');
          this.$router.push('/auth/signin')
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.$refs.observer.setErrors(error?.response?.data?.errors)
            Object.keys(error.response.data.errors).map((field) => {
              this.$toaster.error(error.response.data.errors[field][0]);
            });
          } else this.$toaster.error(error.response.data.message);
        })
        .finally(() => {
          this.isLoading = false
          this.isSignInDisabled = false
        })
    },
  }
}
</script>
