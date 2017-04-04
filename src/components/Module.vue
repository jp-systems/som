<template>
  <div class="module">
    <h2>Module Outline for {{name}}, COMP {{code}}</h2>
    <br>  
    <p>{{outline}}</p>
    <br>
    <p>Last Updated On : {{lastUpdate}}</p>


  </div>
</template>


<script>

import api from '@/js/api'

export default {

  name: 'module',
  data () {
    return {
      code: '',
      name: '',
      outline: '',
      lastUpdate: ''
    }
  },

  mounted: function () {
    api.get('get_module', {module_id: 'abcdef1234567890'})
    .then(response => {
      if(response.data.success) {
        let result = response.data.result[0]
        this.code = result.code
        this.name = result.name
        this.outline = result.outline
        this.lastUpdate = result.updatedOn
      }
    })
  }

}

</script>

<style lang="scss" scoped>


</style>