<template>
  <div class="module">
    <template v-if="module !== null">
      <md-tabs md-fixed>
        <md-tab md-label="Outline" md-icon="info">
          <div v-html="outlineHTML"></div>
          <p>{{ module.updatedOn }}</p>
        </md-tab>
        <md-tab md-label="Chat" md-icon="chat">
          <chat :module="module"></chat>
        </md-tab>
        <md-tab md-label="FAQ" md-icon="help">
          <p>FAQ HERE!</p>
        </md-tab>
      </md-tabs>
    </template>
  </div>
</template>

<script>

import marked from 'marked'

import api from '@/js/api'

import Chat from '@/components/Chat'

export default {
  name: 'Module',
  components: {
    Chat
  },
  props: ['id'],
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
        module_id: this.id
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

  .md-tabs {
    flex: 1;

    .md-tabs-content {
      flex: 1;
      height: 100%;
    }
  }
}
</style>
