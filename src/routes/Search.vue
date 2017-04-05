<template>
  <div class="search">
    <md-input-container>
      <label>Search For Module</label>
      <md-textarea v-model="query" @keyup.native="search"></md-textarea>
    </md-input-container>

    <div class="modules">
      <router-link v-for="module in modules" :key="module.id" :to="'/module/' + (module.ref || module.moduleID)">
        <md-card md-with-hover>
          <md-card-header><h2>{{ module.code }}</h2></md-card-header>
          <md-card-content>
            <h3>{{ module.name }}</h3>
          </md-card-content>     
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
  data () {
    return {
      query: this.$route.params.query,
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
    }, 500)
  }
}
</script>

<style lang="scss" scoped>

.search {
  display: flex;
  flex-direction: column;
  padding: 15px;
}

.modules {
  display: flex;
  margin: 40px auto;
  justify-content: space-around;
  width: 80%;
}

</style>
