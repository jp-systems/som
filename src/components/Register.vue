<template>
  <div class="register">
        <h2>Register Form</h2>
        <p>
          <label for="username">Username</label>
          <input type="text" id="username" v-model="username" @blur="checkUnique" placeholder="bobbossman" required="required" >
          <span class="usernameErr"></span>
        </p>
        <p>
          <label for="email">Email</label>
          <input type="email" id="email" v-model="email" placeholder="name@domain.com" required="required">
        </p>
        <p>
          <label for="password">Password</label>
          <input type="password" id="password" v-model="password" required="required">
        </p>
        <p>
          <label for="confPassword">Confirm Password</label>
          <input type="password" id="confPassword" v-model="confPassword" required="required">
        </p>
        <button :class="{disabled: btnDisabled}" :disabled="btnDisabled" @click="checkForm">Sign Up</button>

  </div>
</template>

<script>
import api from '@/js/api'

export default {
  name: 'Register',
  data () {
    return {
      username: '',
      email: '',
      password: '',
      confPassword: ''
    }
  },

  computed: {
    btnDisabled () {
      return (this.username == '' || this.email == '' || this.password == '' || !this.passwordMatch)
    },

    passwordMatch () {
      return (this.password === this.confPassword)
    }
  },

  methods: {
    checkForm () {
      //TODO
      this.registerUser()
    },

    checkUnique () {
      //TODO
         
    },

    registerUser () {
      api.post('create_user', {
        email: this.email,
        password: this.password,
        username: this.username
      })
      .then(response => {
        console.log(response)
      })
    }
  }
}
</script>

<style lang="scss" scoped>

.register {
  background: #bada55;
}

input:focus {
  outline: none;
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
