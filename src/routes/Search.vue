<template>
  <div class="search">
    <input type="text" v-model="query" @keypress="search">
    <hr>
    <router-link v-for="module in modules" :to="'/module/' + (module.ref || module.moduleID)">
      {{ module.code }}: {{ module.name }}
    </router-link>
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

</style>
