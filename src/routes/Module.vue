<template>
  <div class="module" v-if="module !== null">
    <h2>{{ module.code }}: {{ module.name }}</h2>
    <br>  
    <div v-html="outlineHTML"></div>
    <br>
    <p>Last Updated On : {{ module.updatedOn }}</p>
  </div>
</template>

<script>

import marked from 'marked'

import api from '@/js/api'

export default {
  name: 'Module',
  data () {
    return {
      module: null
    }
  },
  computed: {
    outlineHTML () {
      return marked(this.module.outline || '')
    }
  },
  methods: {
    fetch () {
      api.get('get_module', {
        module_id: this.$route.params.id
      })
      .then(response => {
        if (response.data.success) this.module = response.data.result
      })
      .catch(error => {
        console.error(error)
      })
    }
  },
  mounted: function () {
    this.fetch()
  },
  watch: {
    '$route': function () {
      this.fetch()
    }
  }
}

</script>

<style lang="scss" scoped>
.module {
  display: flex;
  padding: 1rem;
  width: 100%;
}
</style>
