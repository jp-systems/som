<template>
  <div id="app">
    <div class="wrapper" :class="{ blur: $root.modalStatus != null }">
      <app-nav></app-nav>
      <div class="page">
        <app-sidebar></app-sidebar>
        <div class="route">
          <transition name="fade"><router-view></router-view></transition>
        </div>
      </div>
    </div>
    <transition name="fade">
      <div class="modal-overlay" v-if="$root.modalStatus">
        <login v-if="$root.modalStatus == 'login'"></login>
        <register v-if="$root.modalStatus == 'register'"></register>
        <logout v-if="$root.modalStatus == 'logout'"></logout>
      </div>
    </transition>
  </div>
</template>

<script>
import AppNav from '@/components/AppNav'
import AppSidebar from '@/components/AppSidebar'
import Login from '@/components/Login'
import Logout from '@/components/Logout'
import Register from '@/components/Register'

export default {
  name: 'App',
  components: {
    AppNav,
    AppSidebar,
    Login,
    Logout,
    Register
  },
  methods: {
    unblur () {
      this.$root.modalStatus = null
    }
  }
}
</script>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body, #app, #app > .wrapper {
  width: 100%;
  height: 100%;
  overflow: hidden;
}

#app > .wrapper {
  display: flex;
  flex-direction: column;
  background: #fffbfe;
  font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;

  > .page {
    display: flex;
    background: rgba(0, 0, 0, .04);
    flex: 1;

    @media (max-width: 600px) {
      flex-direction: column;
    }

    > .route {
      flex: 1;
      position: relative;

      > div {
        position: absolute;
      }
    }
  }
}

#app > .modal-overlay {
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, .6);
  z-index: 999;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 200ms
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0
}

.desktop-only {
  @media (max-width: 600px) {
    display: none !important;
  }
}

.mobile-only {
  @media (min-width: 600px) {
    display: none !important;
  }
}
</style>
