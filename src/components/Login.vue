<template>
  <transition name="fade">
    <div class="login">
      <md-card md-with-hover>
        <md-card-header class="card-header">
          <div class="md-title">Login</div>
          <md-button class="top-right md-icon-button md-raised md-dense" @click.native="close">
            <md-icon>close</md-icon>
          </md-button>
        </md-card-header>
        <md-card-content>
          <template v-if="!loading">
            <md-input-container>
              <md-icon>person</md-icon>
              <label>Username</label>
              <md-input v-model="username" required="required" ref="first" tabindex="1"></md-input>
            </md-input-container>
            <md-input-container md-has-password>
              <md-icon>vpn_key</md-icon>
              <label>Password</label>
              <md-input type="password" v-model="password" required="required" tabindex="2" @keypress.native.enter="checkUser"></md-input>
            </md-input-container>
            <div class="remember-me">
              <md-checkbox v-model="rememberMe" class="md-primary remember-me" tabindex="3">Remember Me</md-checkbox>
              <span>(Will keep you logged in for 1 month on this device)</span>
            </div>
            <md-button class="md-raised md-primary" :disabled="btnDisabled" @click.native="checkUser" tabindex="4">Login</md-button>
          </template>
          <template v-if="loading">
            <p style="text-align:center;"><md-spinner md-indeterminate></md-spinner></p>
          </template>
          <template v-if="error">
            <p class="error"><md-icon>error</md-icon> {{ error }}</p>
          </template>
        </md-card-content>
      </md-card>
    </div>
  </transition>
</template>

<script>

import api from '@/js/api'

export default {
  name: 'Login',
  data () {
    return {
      username: '',
      password: '',
      rememberMe: true,
      loading: false,
      error: null
    }
  },
  computed: {
    btnDisabled () {
      return (!this.username || !this.password)
    }
  },
  methods: {
    close () {
      this.$root.modalStatus = null
    },
    checkUser () {
      this.loading = true
      api.post('verify_user', {
        username: this.username,
        password: this.password
      })
      .then(r => {
        if (r.data.success) {
          // Login success!
          window.localStorage.setItem('login_token', r.data.result)
          this.$root.modalStatus = null
          this.$root.attemptLogin()
        } else {
          this.loading = false
          this.error = r.data.message
        }
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
  padding: 10px;
  width: 400px;
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

.remember-me {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  > div {
    margin-right: 0;
    margin-bottom: 0;
    flex-direction: row;
  }

  > span {
    font-size: .6rem;
    color: rgba(0, 0, 0, .5);
    margin-bottom: .5rem;
  }
}

.error {
  padding-top: 2rem;
  text-align: center;
  color: red;
}

.fade-enter-active, .fade-leave-active {
  transition: transform 400ms ease-out, opacity 400ms ease-out;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  transform: scale(0.8);
  opacity: 0;
}
</style>
