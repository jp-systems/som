<template>
  <div class="profile">
    <md-card class="card">
      <h1>Avatar</h1>
      <avatar-upload></avatar-upload>
    </md-card>
    <md-card class="card">
      <h1>Change Password</h1>
      <p><md-icon>warning</md-icon> <b>Warning:</b> Changing your password will log you out.</p>
      <md-input-container md-has-password>
        <md-icon>vpn_key</md-icon>
        <label>Current Password</label>
        <md-input type="password" v-model="password" required="required" tabindex="1"></md-input>
      </md-input-container>
      <md-input-container md-has-password :class="{ 'md-input-invalid': newPassword1 !== '' && newPassword1.length < 7 }">
        <md-icon>vpn_key</md-icon>
        <label>New Password</label>
        <md-input type="password" v-model="newPassword1" required="required" tabindex="2"></md-input>
        <span class="md-error">Password must be at least 7 characters long</span>
      </md-input-container>
      <md-input-container md-has-password :class="{ 'md-input-invalid': newPassword2 !== '' && newPassword1 !== newPassword2 }">
        <md-icon>vpn_key</md-icon>
        <label>Confirm New Password</label>
        <md-input type="password" v-model="newPassword2" required="required" @keypress.native.enter="tryRegister" tabindex="3"></md-input>
        <span class="md-error">Passwords do not match!</span>
      </md-input-container>
      <md-button class="md-raised md-primary" :disabled="validateForm" @click.native="changePassword">Change Password</md-button>
    </md-card>
  </div>
</template>

<script>
import api from '@/js/api'

import AvatarUpload from '@/components/AvatarUpload'

export default {
  name: 'Profile',
  components: {
    AvatarUpload
  },
  data () {
    return {
      password: '',
      newPassword1: '',
      newPassword2: ''
    }
  },
  computed: {
    validateForm () {
      return !(this.password !== '' && this.newPassword1 !== '' &&
             this.newPassword2 !== '' && this.newPassword1 === this.newPassword2 &&
             this.newPassword1.length > 6)
    }
  },
  methods: {
    changePassword () {
      api.post('change_password', {
        passwordOld: this.password,
        passwordNew: this.newPassword1,
        passwordNew2: this.newPassword2
      })
      .then(r => {
        if (r.data.success) this.logout()
        else {
          // Show error message
          this.changePasswordError = r.data.message
        }
      })
    },
    logout () {
      api.post('destroy_session')
      window.sessionStorage.removeItem('login_token')
      window.localStorage.removeItem('login_token')
      this.$root.loggedIn = false
      this.$root.userID = null
      this.$root.user = null
      this.$router.push('/')
    }
  },
  mounted () {
    if (!this.$root.loggedIn) this.$router.go(-1)
  }
}
</script>

<style lang="scss" scoped>
.profile {
  width: 100%;
  height: 100%;
  overflow-y: auto;

  > .card {
    padding: .5rem;
    margin: 1rem;

    > h1 {
      font-size: 1rem;
      padding: .5rem;
      border-bottom: 1px solid rgba(0, 0, 0, .1);
    }

    > p {
      padding: .5rem;
      margin: 1rem .5rem .5rem .5rem;
      color: rgba(0, 0, 0, .7);
      text-align: center;
      background: rgba(255, 50, 25, .15);
      border: 1px solid rgba(0, 0, 0, .1);
      border-radius: 3px;
      font-size: .8rem;

      > .md-icon {
        color: rgba(255, 50, 25, .75);
      }
    }
  }
}
</style>
