<template>
  <div class='app-sidebar'
       v-show="$root.loggedIn"
       @click="data+=1">
    <h1>SOM</h1>
    <p><b>UserID: </b> {{ $root.userID }}</p>
    <p><b>SessID: </b> {{ $root.loginToken }}</p>
    <hr>
    <h2>Modules</h2>
    <br>
    <ul v-for="mod in modules">
      <router-link :key="mod.code" :to="'/module/' + (mod.ref || mod.moduleID)">
        {{ mod.name }}
      </router-link>
    </ul>
  </div>
</template>

<script>
import api from '@/js/api'

export default {
  name: 'AppSidebar',
  data() {
    return {
      modules: [],
      data: 1
    }
  },
  methods: {
    fetchModules() {
      if (this.$root.loggedIn) {
        api.post('user_modules')
        .then(response => {
          if (response.data.success) {
            this.modules = response.data.result
          }
        })
      }
    }
  },
  watch: {
    '$root.loggedIn': function () {
      if (this.$root.loggedIn) {
        this.fetchModules()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.app-sidebar {
  width: 300px;
  background-color: #e8e8e8;
  overflow: hidden;
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
