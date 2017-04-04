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
  background: #98ee47;
  width: 12vw;
}
</style>
