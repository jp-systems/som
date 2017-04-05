<template>
  <div class="login">
    <md-card md-with-hover>
      <md-card-header>
        <h2>Enter your Username and Password</h2>
      </md-card-header>

      <md-card-content>
        <md-input-container>
          <label>Username</label>
          <md-textarea v-model="username" required="required"></md-textarea>
        </md-input-container>
    
        <md-input-container md-has-password>
          <label>Password</label>
          <md-input type="password" v-model="password" required="required" @keyup.enter.native="checkUser"></md-input>
        </md-input-container>
    
        <md-checkbox v-model="rememberMe" class="md-primary">Remember Me</md-checkbox>
        
        <md-button class="md-raised md-primary" :disabled="btnDisabled" @click.native="checkUser">Login</md-button>

      </md-card-content>
    </md-card>
  </div>
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
      return (!!!this.username || !!!this.password)
    }
  },

  methods: {
    checkUser () {
      api.post('verify_user', {
        username: this.username,
        password: this.password
      })
      .then(r => {
        if (r.data.success) {
          console.log(r.data);
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

</style>
