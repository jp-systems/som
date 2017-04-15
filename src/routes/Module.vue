<template>
  <div class="module">
    <template v-if="module !== null">
      <md-tabs md-fixed>
        <md-tab md-label="Outline" md-icon="info">
          <template v-if="!editMode">
            <div v-html="outlineHTML"></div>
            <p>Changes were last made on {{ module.updatedOn }}</p>
            <md-button class="md-raised md-warn" @click.native="enableEdit">Edit this page</md-button>
          </template>
          <template v-else>
            <section class="editPage">
              <article>
                <textarea cols="30" rows="10" v-model="rawOutline" ref="editArea"></textarea>
                <div>
                  <md-button class="md-raised md-warn" @click.native="cancelEdit">Cancel</md-button>
                  <md-button class="md-raised" @click.native="saveEdit">Save changes</md-button>
                </div>
              </article>
              <article>
                <h2>{{ module.code }}: {{ module.name }}</h2>
                <div v-html="editModeHTML"></div>
              </article>
            </section>
          </template> 
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
      module: null,
      editMode: false,
      rawOutline: ''
    }
  },
  computed: {
    outlineHTML () {
      return marked(this.module.outline || '')
    },
    editModeHTML () {
      return marked(this.rawOutline || '', { sanitize: true })
    }
  },
  methods: {
    fetch () {
      api.get('get_module', {
        module_id: this.id
      })
      .then(response => {
        if (response.data.success) this.module = response.data.result; this.rawOutline = this.module.outline
      })
      .catch(error => {
        console.error(error)
      })
    },
    endEdit () {
      this.editMode = false
    },
    enableEdit () {
      this.editMode = true
      this.$nextTick(() => this.$refs.editArea.focus())
    },
    saveEdit () {
      // Do stuff
      api.post('update_module', {
        module_ID: this.id,
        module_outline: this.rawOutline
      }).then(response => {
        this.endEdit()
      })
    },
    cancelEdit () {
      this.rawOutline = this.module.outline
      this.endEdit()
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
  padding: .25rem;
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

.editPage {
  display: flex;
  justify-content: space-between;

  article {
    flex: 1;
    margin: 0 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;

    h2 {
      margin-bottom: 2rem;
    }
  }

  textarea {
    width: 100%;
    padding: .6rem;
    border-radius: 5px;
    box-shadow: 0px 2px 8px rgba(0,0,0,.3);
    border: 0;
    font-size: 1rem;
    outline: none;
    background-color: #F4F1F3;
  }
}


</style>
