<template>
  <div class='app-sidebar' v-show="$root.loggedIn">
    <h1>SOM</h1>
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
  background-color: #e8e8e8;
}

h1 {
  padding: .5rem;
}
</style>
