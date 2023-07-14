<template>
  <div>
    <v-card class="text-center pa-1">
      <v-card-title class="justify-center title mb-2">Sign in to your account</v-card-title>

      <!-- sign in form -->
      <v-card-text>
        <validation-observer ref="observer" v-slot="{ invalid, validate }">
          <v-form ref="form" @submit.prevent="submit()" lazy-validation>
            <validation-provider
              v-slot="{ errors }"
              name="email"
              vid="email"
              rules="required|email"
            >
              <v-text-field
                v-model="form.email"
                :validate-on-blur="false"
                :error="error"
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
                :error="error"
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
            >{{ $t('login.button') }}
            </v-btn>
            <div v-if="errorProvider" class="error--text">{{ errorProviderMessages }}</div>

            <div class="mt-5">
              <router-link :to="localePath('/auth/forgot-password')" style="text-decoration: none;">
                {{ $t('login.forgot') }}
              </router-link>
              <v-btn
                class="ml-1"
                color="primary"
                to="/"
              >{{ 'Request for access?' }}
              </v-btn>
            </div>
          </v-form>
        </validation-observer>
      </v-card-text>
    </v-card>

  </div>
</template>

<script>
export default {
  layout: 'auth',
  data() {
    return {
      // sign in buttons
      isLoading: false,
      isSignInDisabled: false,

      // form
      form: {
        email: '',
        password: '',
      },

      // form error
      error: false,
      errorMessages: '',

      errorProvider: false,
      errorProviderMessages: '',

      // show password field
      showPassword: false,

      providers: [{
        id: 'google',
        label: 'Google',
        isLoading: false
      }, {
        id: 'facebook',
        label: 'Facebook',
        isLoading: false
      }],

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
        this.signIn()
      }
    },
    check() {
      if (this.$auth.user) {
        this.$router.push('/dashboard')
      }
    },
    signIn() {
      const formData = new FormData()

      formData.append('email', this.form.email)
      formData.append('password', this.form.password)

      this.$auth.loginWith('auth', {data: formData})
        .then((response) => {
          const data = response?.data
          if (data) {
            this.$axios.setHeader('Authorization', 'Bearer ' + data.access_token)
            this.$axios.setToken(data.access_token)
            this.$auth.setUserToken(data.access_token)
            this.$auth.setUser(data.user)
            //this.$ability.update(data.user.ability)
            this.check();
          }
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
    signInProvider(provider) {
    },
    resetErrors() {
      this.error = false
      this.errorMessages = ''

      this.errorProvider = false
      this.errorProviderMessages = ''
    }
  }
}
</script>
