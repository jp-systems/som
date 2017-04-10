<template>
  <transition name="fade">
    <div class="register">
      <md-card md-with-hover>
        <md-card-header class="card-header">
          <div class="md-title">Register</div>
          <md-button class="top-right md-icon-button md-raised" @click.native="close">
            <md-icon>close</md-icon>
          </md-button>
        </md-card-header>
        <md-card-content>
          <template v-if="!loading">
            <md-input-container :class="{ 'md-input-invalid': usernameError !== null }">
              <md-icon>person</md-icon>
              <label>Username</label>
              <md-input v-model="username" @blur.native="checkUsername" required="required" tabindex="1" ref="first"></md-input>
              <span class="md-error">{{ usernameError }}</span>
            </md-input-container>
            <md-input-container :class="{ 'md-input-invalid': emailError !== null }">
              <md-icon>email</md-icon>
              <label>Email</label>
              <md-input type="email" v-model="email" @blur.native="checkEmail" required="required" tabindex="2"></md-input>
              <span class="md-error">{{ emailError }}</span>
            </md-input-container>
            <md-input-container md-has-password :class="{ 'md-input-invalid': password !== '' && !passwordLength }">
              <md-icon>vpn_key</md-icon>
              <label>Password</label>
              <md-input type="password" v-model="password" required="required" tabindex="3"></md-input>
              <span class="md-error">Password must be at least 7 characters long</span>
            </md-input-container>
            <md-input-container md-has-password :class="{ 'md-input-invalid': confPassword !== '' && !passwordMatch }">
              <md-icon>vpn_key</md-icon>
              <label>Confirm Password</label>
              <md-input type="password" v-model="confPassword" required="required" @keypress.native.enter="tryRegister" tabindex="4"></md-input>
              <span class="md-error">Passwords do not match!</span>
            </md-input-container>
            <md-button class="md-raised md-primary" :disabled="btnDisabled" @click.native="tryRegister" tabindex="5">Sign Up</md-button>
          </template>
          <template v-else>
            <p style="text-align:center;"><md-spinner md-indeterminate></md-spinner></p>
          </template>
        </md-card-content>
      </md-card>
    </div>
  </transition>
</template>

<script>
import api from '@/js/api'

export default {
  name: 'Register',
  data () {
    return {
      loading: false,
      username: '',
      email: '',
      password: '',
      confPassword: '',
      usernameError: null,
      emailError: null,
      passwordError: null,
      error: null
    }
  },

  computed: {
    btnDisabled () {
      return !!this.usernameError || !!this.emailError || !this.passwordMatch || !this.passwordLength || !this.username || !this.email || !this.password || !this.confPassword
    },

    passwordMatch () {
      return (this.password === this.confPassword)
    },

    passwordLength () {
      return (this.password.length > 6)
    }
  },

  methods: {
    close () {
      this.$root.modalStatus = null
    },
    checkUsername () {
      if (this.username === null || this.username === '') {
        this.usernameError = null
      } else {
        // Fire of an API request
        api.get('username_taken', {
          username: this.username
        })
        .then(response => {
          let data = response.data
          if (data.success) {
            if (data.result) {
              this.usernameError = 'Username already taken!'
            } else {
              this.usernameError = null
            }
          } else {
            this.usernameError = 'An unexpected error occured!'
          }
        })
        .catch(error => {
          this.usernameError = 'An unexpected error occured!\n' + error
        })
      }
    },
    checkEmail () {
      if (this.email === null || this.email === '') {
        this.emailError = null
      } else {
        // Fire of an API request
        api.get('email_taken', {
          email: this.email
        })
        .then(response => {
          let data = response.data
          if (data.success) {
            if (data.result) {
              this.emailError = 'Email already in use!'
            } else {
              this.emailError = null
            }
          } else {
            this.emailError = 'An unexpected error occured!'
          }
        })
        .catch(error => {
          this.emailError = 'An unexpected error occured!\n' + error
        })
      }
    },
    tryRegister () {
      this.loading = true
      this.error = null
      api.post('create_user', {
        email: this.email,
        password: this.password,
        username: this.username
      })
      .then(response => {
        let data = response.data
        if (data.success) {
          // It worked, we also get passed a login session token
          window.localStorage.setItem('login_token', data.result)
          this.$root.modalStatus = null
          this.$root.attemptLogin()
        } else {
          // Something went wrong, show an error
          this.error = data.message
        }
        this.loading = false
      })
      .catch(error => {
        this.error = error
        this.loading = false
      })
    }
  },
  mounted () {
    this.$nextTick(_ => this.$refs.first.$el.focus())
  }
}
</script>

<style lang="scss" scoped>

.md-card {
  margin: auto;
  width: 400px;
  padding: 10px;
  cursor: default;
}

.md-button {
  display: block;
  margin: auto;
}

.card-header {
  position: relative;
}

.top-right {
  position: absolute;
  right: 0;
  top: 0;
}

.fade-enter-active, .fade-leave-active {
  transition: transform 400ms ease-out, opacity 400ms ease-out;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  transform: scale(0.8);
  opacity: 0;
}

</style>
