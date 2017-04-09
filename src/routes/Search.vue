<template>
  <div class="search">
    <md-input-container>
      <label>Search For Module</label>
      <md-input v-model="query" @keyup.native="search"></md-input>
    </md-input-container>

    <div class="modules">
      <template v-for="mod in modules">
        <router-link :key="mod.code" :to="'/module/' + (mod.ref || mod.moduleID)">
          <md-card md-with-hover>
            <div class="header-colour"></div>
            <md-card-header><h2>{{ mod.code }}</h2></md-card-header>
            <md-card-content>
              <h3>{{ mod.name }}</h3>
            </md-card-content>
            <md-card-actions v-if="$root.loggedIn">
              <md-button class="md-icon-button" @click.prevent.native="favModule(mod.moduleID)">
                <md-icon>bookmark_border</md-icon>
              </md-button>
            </md-card-actions>
          </md-card>
        </router-link>
      </template>
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
      query: this.query,
      modules: []
    }
  },
  components: {

  },
  methods: {
    search: _.debounce(function () {
      if(!this.query) {this.modules = []; return}
      api.get('search_modules', {
        query: this.query
      })
      .then(response => {
        this.modules = response.data.result
      })
    }, 500),
    favModule (moduleID) {
      // Do thing
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
}

.modules {
  display: flex;
  justify-content: flex-start;
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

.header-colour {
  background-image: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%);
  width: 100%;
  height: 10px;
}

</style>
