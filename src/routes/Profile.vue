<template>
  <div class="profile">
    <md-card class="card">
      <h1>Avatar</h1>
      <avatar-upload></avatar-upload>
    </md-card>
    <md-card class="card">
      <h1>Change Password</h1>
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
      <md-input-container md-has-password :class="{ 'md-input-invalid': newPassword !== '' && newPassword1 !== newPassword2 }">
        <md-icon>vpn_key</md-icon>
        <label>Confirm New Password</label>
        <md-input type="password" v-model="newPassword2" required="required" @keypress.native.enter="tryRegister" tabindex="3"></md-input>
        <span class="md-error">Passwords do not match!</span>
      </md-input-container>
      <md-button class="md-raised md-primary" :disabled="validateForm">Change Password</md-button>
    </md-card>
  </div>
</template>

<script>
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
  mounted () {
    if (!this.$root.loggedIn) this.$router.go(-1)
  }
}
</script>

<style lang="scss" scoped>
.profile {
  width: 100%;
  height: 100%;

  > .card {
    padding: .5rem;
    margin: 1rem;

    > h1 {
      font-size: 1rem;
      padding: .5rem;
      border-bottom: 1px solid rgba(0, 0, 0, .1);
    }
  }
}
</style>
