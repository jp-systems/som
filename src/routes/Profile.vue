<template>
  <div class="profile">
    <md-card class="card">
      <avatar-upload></avatar-upload>
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
    margin: .25rem;

    > form {
      margin: 1rem;
    }
  }
}
</style>
