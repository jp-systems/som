<template>
  <div class="login">
    <h2>Enter your Username and Password</h2>
    <p>
        <label for="username">Username:</label>
        <input type="text" id="username" v-model="username" placeholder="username" required="required">
    </p>
    <p>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="password" required="required">
    </p>
    
    <p>
      <label><input type="checkbox" name="checkbox" v-model="rememberMe">Stay Logged In</label>
    </p>
    <button :class="{disabled : btnDisabled}" :disabled="btnDisabled" @click="checkUser">Login</button>
  </div>
</template>

<script>
import axios from 'axios'
import qs from 'qs'

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
    return (this.username == '' || this.password == '')
    }
  },

  methods: {
    checkUser () {
      
    }
  }
}
</script>

<style lang="scss" scoped>
.login {
  background: #bada55;
}

button {
  background-color: #00C4A7;
  padding: 7px;
  border: 0px;
  border-radius: 7px;
  color: white;  
}

button.disabled {
  background-color: #FF2B56;
  cursor: default;
}

</style>