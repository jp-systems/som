<template>
  <div class='app-sidebar' v-show="$root.loggedIn">
    <h1>SOM</h1>
    <p><b>UserID: </b> {{ $root.userID }}</p>
    <p><b>SessID: </b> {{ $root.loginToken }}</p>
    <hr>
    <h2>Modules</h2>
    <br>
    <ul v-for="module in modules">
      <router-link :key="module.code" :to="'/module/' + (module.ref || module.moduleID)">
        <li>{{ module.name }}</li>
      </router-link> 
    </ul>
  </div>
</template>

<script>
import api from '@/js/api'


export default {
  name: 'AppSidebar',
  data () {
    return {
      modules: []
    }
  },
  mounted () {
      if(this.$root.loggedIn) {
        api.post('user_modules')
        .then(response => {
          console.log(response)
          if(response.data.success) {
            this.modules = response.data.result
          }
        })
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
