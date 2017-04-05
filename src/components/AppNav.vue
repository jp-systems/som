<template>
  <div class="app-nav">
    <div class="max-width">
      <h1>SOM</h1>
      <div class="spacer"></div>
      <md-button @click.native="login">Login</md-button>
      <md-button class="md-raised md-primary" @click.native="register">Register</md-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppNav',
  methods: {
    login () {
      this.$root.modalStatus = 'login'
    },
    register () {
      this.$root.modalStatus = 'register'
    }
  }
}
</script>

<style lang="scss" scoped>
.app-nav {
  background-image: linear-gradient(120deg, lighten(#fccb90, 15%) 0%, lighten(#d57eeb, 25%) 100%);
  box-shadow: 0 3px 9px rgba(0, 0, 0, .2);
  z-index: 9;

  > .max-width {
    display: flex;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;

    > h1 {
      font-size: 1.2rem;
    }
    
    > .spacer {
      flex: 1;
    }
  }
}
</style>
