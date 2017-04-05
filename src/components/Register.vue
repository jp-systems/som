<template>
  <div class="register">
        
    <md-card md-with-hover>
      <md-card-header><h2>Register Form</h2></md-card-header>
  
      <md-card-content>
        <md-input-container>
          <label>Username</label>
          <md-textarea v-model="username" @blur="checkUnique" required="required"></md-textarea>
        </md-input-container>

        <md-input-container>
          <label>Email</label>
          <md-textarea type="email" v-model="email" required="required"></md-textarea>
        </md-input-container>
    
        <md-input-container md-has-password>
          <label>Password</label>
          <md-input type="password" v-model="password" required="required"></md-input>
        </md-input-container>
        
        
        <md-input-container md-has-password>
          <label>Confirm Password</label>
          <md-input type="password" v-model="confPassword" required="required" @keyup.enter="checkForm"></md-input>
        </md-input-container>
    
        <md-button class="md-raised md-primary" :disabled="btnDisabled" @click.native="checkForm">SignUp</md-button>
      </md-card-content>
    </md-card>

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

</style>
