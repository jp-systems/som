<template>
  <div class="default">
    <div class="h75">
      <h1><md-icon class="md-size-5x">school</md-icon><br>Student Online Messenger</h1>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Default',
  mounted () {
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
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 75vh;
    background-image: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%);

    > h1 {
      text-align: center;
      font-size: 4rem;
    }
  }
}
</style>
