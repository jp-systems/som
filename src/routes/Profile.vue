<template>
  <div class="profile">
    <md-card class="card">
      <form novalidate @submit.stop.prevent="submit">
        <md-input-container>
          <label>Initial value</label>
          <md-input v-model="initialValue"></md-input>
        </md-input-container>
      </form>
    </md-card>
  </div>
</template>

<script>
export default {
  name: 'Profile',
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
    margin: 1rem;

    > form {
      margin: 1rem;
    }
  }
}
</style>
