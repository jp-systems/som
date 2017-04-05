<template>
  <transition name="fade">
    <div class="logout">
      <md-card md-with-hover>
        <md-card-header class="card-header">
          <div class="md-subheading">Are you sure you wish to log out?</div>
          <md-button class="top-right md-icon-button md-raised" @click.native="close">
            <md-icon>close</md-icon>
          </md-button>
        </md-card-header>

        <md-card-content>
          <md-button class="md-raised md-warn" @click.native="logout"><b>Yes</b>, Log Out</md-button>
        </md-card-content>
      </md-card>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Logout',
  methods: {
    close () {
      this.$root.modalStatus = null
    },
    logout () {
      window.localStorage.removeItem('login_token')
      this.$root.loggedIn = false
      this.$root.userID = null
      this.$root.user = null
      this.close()
      this.$router.push('/')
    }
  }
}
</script>

<style lang="scss" scoped>

.md-card {
  margin: auto;
  padding: 10px;
  width: 400px;
  cursor: default;
}

.md-button {
  display: block;
  margin: auto;
}

.card-header {
  position: relative;
}

.top-right {
  position: absolute;
  right: 0;
  top: 0;
}

.error {
  padding-top: 2rem;
  text-align: center;
  color: red;
}

.fade-enter-active, .fade-leave-active {
  transition: transform 400ms ease-out, opacity 400ms ease-out;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  transform: scale(0.8);
  opacity: 0;
}
</style>
