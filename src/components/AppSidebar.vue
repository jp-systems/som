<template>
  <div class='app-sidebar' v-show="$root.loggedIn">
    <md-list class="md-dense list desktop-only">
      <md-subheader>Modules</md-subheader>
      <md-divider></md-divider>
      <transition-group name="fade">
        <md-list-item v-for="mod in sortedModules" :key="mod.code">
          <router-link :to="moduleLink(mod)" exact>{{ mod.name }}</router-link>
        </md-list-item>
      </transition-group>
    </md-list>
    <div class="mobile-only">
      <div class="header">
        <p class="text">Your Modules</p>
        <span class="toggle" @click="mobileMenuOpen = !mobileMenuOpen">
          <md-icon :class="{ open: mobileMenuOpen }">keyboard_arrow_down</md-icon>
        </span>
      </div>
      <transition name="fold-down">
        <div class="modules" v-if="mobileMenuOpen">
          <router-link class="router-link" v-for="mod in sortedModules" :key="mod.code" :to="moduleLink(mod)" exact>{{ mod.name }}</router-link>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import api from '@/js/api'

export default {
  name: 'AppSidebar',
  data () {
    return {
      modules: [],
      mobileMenuOpen: false
    }
  },
  computed: {
    sortedModules () {
      return this.$root.modules ? this.$root.modules.sort((a, b) => a.name > b.name) : []
    }
  },
  methods: {
    fetchModules () {
      if (this.$root.loggedIn) {
        api.post('user_modules')
        .then(response => {
          if (response.data.success) {
            this.$root.modules = response.data.result
          } else {
            throw Error('Bad request: ' + JSON.stringify(response.data))
          }
        })
        .catch(error => {
          console.error(error)
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
  background-color: rgba(0, 0, 0, .03);
  overflow: hidden;

  @media (max-width: 600px) {
    width: 100%;
    min-width: none;
    max-width: none;
    overflow: hidden;
  }

  > .md-list {
    margin: .25rem;
    box-shadow: 0 3px 9px rgba(0, 0, 0, .1);
  }

  .active {
    background-color: rgba(144, 202, 249, .7);
    border-right: 5px solid rgba(0, 0, 0, .05);
    text-decoration: none;
  }

  .mobile-only {
    display: flex;
    background-color: rgba(255, 255, 255, .8);
    flex-direction: column;

    > .header {
      background-color: rgba(0, 0, 0, .7);
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;

      > .text, > .toggle {
        font-weight: bold;
        padding: .75rem 1rem;
      }

      > .toggle {

        > .md-icon {
          transition: transform 300ms ease-out;
          transform: rotateZ(0);

          &.open {
            transform: rotateZ(180deg);
          }
        }
      }
    }

    > .modules {
      display: flex;
      flex-direction: column;

      > .router-link {
        padding: .75rem 1rem;
        color: black;
      }
    }
  }
}

.fold-down-enter-active, .fold-down-leave-active {
  transition: opacity 200ms;
}
.fold-down-enter, .fold-down-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
