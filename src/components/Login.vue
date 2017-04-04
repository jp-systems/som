<template>
  <div class="login">
    <h2>Enter your Username and Password</h2>

    <input type="text" id="username" v-model="username" placeholder="Username" required="required">

    <input type="password" id="password" v-model="password" placeholder="Password" required="required" @keyup.enter="checkUser">
    
    <div>
      <input type="checkbox" id="checkbox" v-model="rememberMe">
      <label for="checkbox">Remember Me</label>
    </div>
    
    <button :class="{disabled : btnDisabled}" :disabled="btnDisabled" @click="checkUser">Login</button>
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
      console.log("hello")
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

.login {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: space-around;
  margin: auto;
  padding: 20px;
  width: 33vw;
  height: 400px;
  box-shadow: 0 4px 5px rgba(0, 0, 0, 0.2);
}

input[type]:not([type=checkbox]) {
  border: none;
  width: 50%;
  padding: 10px 0;
  border-bottom: solid 1px #ccc;
  background-color: #fffbfe;

  &:focus {
    outline: none;
    border-bottom-color: #a8e2dc;

    &::-webkit-input-placeholder {
      color: #a8e2dc;
      transform: translateY(-17px);
      visibility: visible !important;
    }
  }
}

input[type="checkbox"]+label {
  font-size: 0.7em;
}

button {
  background-color: #00C4A7;
  width: 33%;
  margin: 0 auto;
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
