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
        <md-card md-with-hover class="mod_content">
          <div class="header-colour"></div>
          <md-card-header><h2>{{ mod.code }}</h2></md-card-header>
          <md-card-content>
            <h3>{{ mod.name }}</h3>
          </md-card-content>
          <md-card-actions v-if="$root.loggedIn">
            <md-button class="md-icon-button bookmark" @click.prevent.native="changeFollow(mod)">
              <md-icon>{{ isFollowing(mod) ? 'bookmark' : 'bookmark_border' }}</md-icon>
            </md-button>
          </md-card-actions>
        </md-card>
      </router-link>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'

import api from '@/js/api'

export default {
  name: 'Search',
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

.mod_content {
  height: 180px;
}

.bookmark {
  position: absolute;
  bottom: 1px;
  right: 1px;
}

.md-card {
  margin: 0px 10px;
  overflow: hidden;

  h2,h3 {
    color: rgba(0, 0, 0, .87);
  }
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

.header-colour {
  background-image: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%);
  width: 100%;
  height: 10px;
}

</style>
