<template>
  <transition name="fade">
    <div class="login">
      <md-card md-with-hover>
        <md-card-header class="card-header">
          <div class="md-title">Login</div>
          <md-button class="top-right md-icon-button md-raised" @click.native="close">
            <md-icon>close</md-icon>
          </md-button>
        </md-card-header>

        <md-card-content>
          <md-input-container>
            <label>Username</label>
            <md-textarea v-model="username" required="required"></md-textarea>
          </md-input-container>
      
          <md-input-container md-has-password>
            <label>Password</label>
            <md-input type="password" v-model="password" required="required"></md-input>
          </md-input-container>
      
          <md-checkbox v-model="rememberMe" class="md-primary">Remember Me</md-checkbox>
          
          <md-button class="md-raised md-primary" :disabled="btnDisabled" @click.native="checkUser">Login</md-button>

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
      rememberMe: false
    }
  },

  computed: {
    btnDisabled() {
      return (!this.username || !this.password)
    }
  },

  methods: {
    close () {
      this.$root.modalStatus = null
    },
    checkUser () {
      api.post('verify_user', {
        username: this.username,
        password: this.password
      })
      .then(r => {
        if (r.data.success) {
          // Login success!
          window.localStorage.setItem('login_token', r.data.result)
          window.location.reload()
        }
      })
    }
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

.fade-enter-active, .fade-leave-active {
  transition: transform 400ms ease-out, opacity 400ms ease-out;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  transform: scale(0.8);
  opacity: 0;
}
</style>
