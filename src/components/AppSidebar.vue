<template>
  <div class='app-sidebar' v-if="$root.loggedIn">
    <h1>SOM</h1>
    <button>Logout</button>
  </div>
</template>

<script>
export default {
  name: 'AppSidebar',
  data () {
    return {

    }
  }
}
</script>

<style lang="scss" scoped>

.app-sidebar {
  width: 12vw;
  background-image: linear-gradient(to top, #B2DBBF 0%, #70C1B3 100%);
}
</style>
