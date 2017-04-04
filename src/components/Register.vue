<template>
  <div class="register">
        
    <h2>Register Form</h2>

    <input type="text" id="username" v-model="username" @blur="checkUnique" placeholder="bobbossman" required="required" >

    <input type="email" id="email" v-model="email" placeholder="name@domain.com" required="required">

    <input type="password" id="password" v-model="password" placeholder="Password" required="required">

    <input type="password" id="confPassword" v-model="confPassword" placeholder="Confirm Password" required="required" @keyup.enter="checkForm">

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
      return (!!!this.username || !!!this.email || !!!this.password || !this.passwordMatch)
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
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: space-around;
  margin: auto;
  width: 33vw;
  height: 400px;
  padding: 20px;
  box-shadow: 0 4px 5px rgba(0, 0, 0, 0.2);
}

input {
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
