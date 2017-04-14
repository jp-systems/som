<template>
  <div class='app-sidebar' v-show="$root.loggedIn">
    <md-list class="md-dense list">
      <md-subheader>Modules</md-subheader>
      <md-divider></md-divider>
      <transition-group name="fade">
        <md-list-item v-for="mod in sortedModules" :key="mod.code">
          <router-link :to="moduleLink(mod)" exact>{{ mod.name }}</router-link>
        </md-list-item>
      </transition-group>
    </md-list>
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
  computed: {
    sortedModules () {
      return this.$root.modules.sort((a, b) => a.name > b.name)
    }
  },
  methods: {
    fetchModules () {
      if (this.$root.loggedIn) {
        api.post('user_modules')
        .then(response => {
          if (response.data.success) {
            this.$root.modules = response.data.result
          }
        })
      }
    },
    moduleLink (module) {
      return '/module/' + (module.ref || String(module.code).toLowerCase())
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
  width: 20vw;
  min-width: 200px;
  max-width: 250px;
  flex-shrink: 0;
  background-color: rgba(255, 255, 255, .6);
  overflow: hidden;

  > .md-list {
    margin: .25rem;
    box-shadow: 0 3px 9px rgba(0, 0, 0, .1);
  }

  .active {
    background-color: rgba(200, 245, 230, .9);
    border-right: 5px solid rgba(0, 0, 0, .05);
  }
}
</style>
