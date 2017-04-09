<template>
  <div class="module">
    <template v-if="module !== null">
      <md-tabs md-fixed>
        <md-tab md-label="Outline" md-icon="info">
          <div v-html="outlineHTML"></div>
          <p>{{ module.updatedOn }}</p>
        </md-tab>
        <md-tab md-label="Chat" md-icon="chat">
          <p>Messenger</p>
        </md-tab>
        <md-tab md-label="FAQ" md-icon="help">
          <p>FAQ HERE!</p>
        </md-tab>
      </md-tabs>
      <!--<h1 class="header">{{ module.code }}: {{ module.name }}</h1>
      <div v-html="outlineHTML"></div>
      <p>Last Updated On : {{ module.updatedOn }}</p>-->
    </template>
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
  flex-direction: column;
  padding: 1rem;
  width: 100%;
  height: 100%;

  .md-tabs-content {
    background-color: rgba(255, 255, 255, .5);
  }
}
</style>
