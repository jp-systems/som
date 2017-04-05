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
  mounted: function () {
    api.get('get_module', {
      module_id: this.$route.params.id
    })
    .then(response => {
      console.log(response)
      if(response.data.success) {
        this.module = response.data.result
      }
    })
  }

}

</script>

<style lang="scss" scoped>


</style>
