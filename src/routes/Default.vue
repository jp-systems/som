<template>
  <div class="default">
    <div class="h75">

    </div>
  </div>
</template>

<script>

export default {
  name: 'Default',
  beforeMount () {
    // Go to Home view if already logged in
    if (this.$root.loggedIn) this.$router.replace('/home')
  }
}
</script>

<style lang="scss" scoped>
.default {
  width: 100%;
  height: 100%;

  > .h75 {
    height: 75vh;
    background-image: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%);
  }
}
</style>
