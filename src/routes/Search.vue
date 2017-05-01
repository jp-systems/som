<template>
  <div class="search">
    <md-input-container>
      <label>Search For Module</label>
      <md-input v-model="activeQuery" @keydown.native="fetching = true" @keyup.native="search"></md-input>
    </md-input-container>
    <div v-if="fetching" class="loading">
      <p style="text-align:center;"><md-spinner :md-size="100" md-indeterminate></md-spinner></p>
    </div>
    <div v-if="!fetching" class="modules">
      <router-link v-for="mod in modules" :key="mod.code" :to="'/module/' + (mod.ref || mod.moduleID)" class="module">
        <mod-card :mod="mod"></mod-card>
      </router-link>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'

import api from '@/js/api'

import ModCard from '@/components/ModCard'

export default {
  name: 'Search',
  components: {
    ModCard
  },
  props: ['query'],
  data () {
    return {
      activeQuery: this.query,
      fetching: false,
      modules: []
    }
  },
  mounted () {
    if (this.activeQuery) this.search()
  },
  methods: {
    isFollowing (mod) {
      return this.$root.modules.findIndex(m => m.code === mod.code) !== -1
    },
    search: _.debounce(function () {
      if (!this.activeQuery) {
        this.fetching = false
        this.modules = []
        return
      }
      this.fetching = true
      api.get('search_modules', {
        query: this.activeQuery
      })
      .then(response => {
        this.fetching = false
        this.modules = response.data.result
      })
    }, 500),
    changeFollow (mod) {
      if (this.isFollowing(mod)) {
        // Unfollow the module
        this.$root.modules.splice(this.$root.modules.findIndex(m => m.code === mod.code), 1)
        api.post('unfollow_module', {
          module_ID: mod.moduleID
        }).then(response => {
          console.log(response)
        })
      } else {
        // Follow the module
        api.post('fav_module', {
          module_ID: mod.moduleID
        })
        .then(response => {
          this.$root.modules.push(mod)
          this.$root.modules = _.uniqBy(this.$root.modules, m => m.code)
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>

.search {
  display: flex;
  flex-direction: column;
  padding: 1rem;
  width: 100%;
  height: 100%;
}

.modules {
  display: flex;
  justify-content: flex-start;
  flex-wrap: wrap;
  overflow: scroll;
}

.module {
  max-width: 340px;
  margin-bottom: 1.5rem;
}

a:hover {
  text-decoration: none !important;
}

.loading {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

</style>
