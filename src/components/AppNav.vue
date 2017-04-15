<template>
  <div class="app-nav">
    <nav class="max-width">
      <h1 class="logo" @click="home"><md-icon>school</md-icon> SOM</h1>
      <md-button class="md-primary" @click.native="$router.push('/search')"><md-icon>search</md-icon> Modules</md-button>
      <div class="spacer"></div>
      <template v-if="$root.loggedIn">
        <md-button class="md-primary md-dense desktop-only" @click.native="profile">
          <md-avatar>
            <img v-if="$root.user && $root.user.avatar" :src="$root.user.avatar">
            <md-icon v-else>person</md-icon>
          </md-avatar>
          <span v-if="$root.user">{{ $root.user.username }}</span>
          <md-tooltip md-direction="bottom">My Profile</md-tooltip>
        </md-button>
        <md-button class="md-icon-button md-dense desktop-only" @click.native="logout">
          <md-icon>exit_to_app</md-icon>
          <md-tooltip md-direction="bottom">Logout</md-tooltip>
        </md-button>
      </template>
      <template v-else>
        <md-button class="desktop-only" @click.native="login"><md-icon>vpn_key</md-icon> Login</md-button>
        <md-button class="md-raised md-primary desktop-only" @click.native="register"><md-icon>add</md-icon> Register</md-button>
      </template>
      <md-button class="md-icon-button md-dense mobile-only" @click.native="mobileNavOpen = !mobileNavOpen">
        <md-icon>menu</md-icon>
      </md-button>
    </nav>
    <div v-if="mobileNavOpen" class="subnav mobile-only">
      <template v-if="$root.loggedIn">
        <md-button class="md-primary md-dense" @click.native="profile">
          <md-avatar>
            <img v-if="$root.user && $root.user.avatar" :src="$root.user.avatar">
            <md-icon v-else>person</md-icon>
          </md-avatar>
          <span v-if="$root.user">{{ $root.user.username }}</span>
        </md-button>
        <md-button class="md-dense" @click.native="logout">
          <md-icon>exit_to_app</md-icon> Logout
        </md-button>
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
  data () {
    return {
      mobileNavOpen: false
    }
  },
  methods: {
    login () {
      this.$root.modalStatus = 'login'
    },
    register () {
      this.$root.modalStatus = 'register'
    },
    logout () {
      this.$root.modalStatus = 'logout'
    },
    profile () {
      this.$router.push('/profile')
    },
    home () {
      this.$router.push(this.$root.loggedIn ? '/home' : '/')
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

    > .logo {
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      padding: 0 .5rem;

      > i {
        margin-right: .5rem;
      }
    }
  }

  > .subnav {
    background-color: rgba(0, 0, 0, .05);
    display: flex;
    flex-direction: column;
    max-width: 1200px;
    margin: 0 auto;
    align-items: center;
    justify-content: stretch;

    > * {
      width: 100%;
      margin: 0;
      padding: .5rem;
    }
  }

  .spacer {
    flex: 1;
  }
}
</style>
