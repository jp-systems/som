<template>
  <div class='app-sidebar' v-show="$root.loggedIn">
    <h1>SOM</h1>
    <p><b>UserID: </b> {{ $root.userID }}</p>
    <p><b>SessID: </b> {{ $root.loginToken }}</p>
    <hr>
    <h2>Modules</h2>
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
  text-align: center;
}

h2 {
  font-size: 1rem;
  padding: .25rem;
}
</style>
