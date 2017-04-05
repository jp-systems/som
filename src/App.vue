<template>
  <div id="app">
    <div class="wrapper" :class="{ blur: $root.modalStatus != null }">
      <app-nav></app-nav>
      <div class="page">
        <app-sidebar></app-sidebar>
        <router-view></router-view>
      </div>
    </div>
    <transition name="fade">
      <div class="modal-overlay" v-if="$root.modalStatus">
        <login v-if="$root.modalStatus == 'login'"></login>
        <register v-if="$root.modalStatus == 'register'"></register>
      </div>
    </transition>
  </div>
</template>

<script>
import api from '@/js/api'

import AppNav from '@/components/AppNav'
import AppSidebar from '@/components/AppSidebar'
import Login from '@/components/Login'
import Register from '@/components/Register'

export default {
  name: 'App',
  components: {
    AppNav,
    AppSidebar,
    Login,
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
    flex: 1;
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

h1, h2, h3, h4 {
  font-family: 'Raleway', cursive;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 200ms
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0
}
</style>
