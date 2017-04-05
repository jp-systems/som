<template>
  <div class="home">
    <div class="center">
      <h1>Student Online Messenger</h1>
      <hr>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Home',
  beforeMount () {
    if (!this.$root.loggedIn) this.$router.replace('/')
  }
}
</script>

<style lang="scss" scoped>
.home {
  width: 100%;
  height: 100%;
}
</style>
