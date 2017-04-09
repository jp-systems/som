<template>
  <div class="app-nav">
    <div class="max-width">
      <h1><md-icon>school</md-icon> SOM</h1>
      <md-button class="md-primary" @click.native="$router.push('/search')">Search</md-button>
      <div class="spacer"></div>
      <template v-if="$root.loggedIn">
        <md-button @click.native="logout"><md-icon>exit_to_app</md-icon> Logout</md-button>
      </template>
      <template v-else>
        <md-button @click.native="login"><md-icon>vpn_key</md-icon> Login</md-button>
        <md-button class="md-raised md-primary" @click.native="register"><md-icon>add</md-icon> Register</md-button>
      </template>
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
    },
    logout () {
      this.$root.modalStatus = 'logout'
    }
  }
}
</script>

<style lang="scss" scoped>
.app-nav {
  background-color: #f3f3f3;
  box-shadow: 0 3px 9px rgba(0, 0, 0, .2);
  z-index: 9;

  > .max-width {
    display: flex;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;

    > h1 {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;

      > i {
        margin-right: .5rem;
      }
    }
    
    > .spacer {
      flex: 1;
    }
  }
}
</style>
