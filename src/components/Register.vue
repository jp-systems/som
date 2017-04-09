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
          <md-button class="md-raised md-primary" :disabled="btnDisabled" @click.native="checkForm">Sign Up</md-button>
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
    close () {
      this.$root.modalStatus = null
    },
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
