<template>
  <div class="module">
    <template v-if="module !== null">
      <div class="toolbar">
        <h2 class="md-title"><md-icon>book</md-icon> {{ module.name }}</h2>
        <div class="buttons mobile-only">
          <router-link :to="'/module/' + id" tag="button" class="md-button" exact>
            <md-icon>info</md-icon> Outline
            <md-ink-ripple></md-ink-ripple>
          </router-link>
          <router-link :to="'/module/' + id + '/chat'" tag="button" class="md-button" exact>
            <md-icon>chat</md-icon> Chat
            <md-tooltip md-direction="bottom">Chat</md-tooltip>
            <md-ink-ripple></md-ink-ripple>
          </router-link>
          <router-link :to="'/module/' + id + '/faq'" tag="button" class="md-button" exact>
            <md-icon>question_answer</md-icon> FAQ
            <md-tooltip md-direction="bottom">Q & A</md-tooltip>
            <md-ink-ripple></md-ink-ripple>
          </router-link>
        </div>
        <div class="buttons desktop-only">
          <router-link :to="'/module/' + id" tag="button" class="md-button md-icon-button" exact>
            <md-icon>info</md-icon>
            <md-tooltip md-direction="bottom">Outline</md-tooltip>
            <md-ink-ripple></md-ink-ripple>
          </router-link>
          <router-link :to="'/module/' + id + '/chat'" tag="button" class="md-button md-icon-button" exact>
            <md-icon>chat</md-icon>
            <md-tooltip md-direction="bottom">Chat</md-tooltip>
            <md-ink-ripple></md-ink-ripple>
          </router-link>
          <router-link :to="'/module/' + id + '/faq'" tag="button" class="md-button md-icon-button" exact>
            <md-icon>question_answer</md-icon>
            <md-tooltip md-direction="bottom">Q & A</md-tooltip>
            <md-ink-ripple></md-ink-ripple>
          </router-link>
        </div>
      </div>
      <h1 class="md-title desktop-only">{{ tabHeader }}</h1>
      <div class="content outline" v-if="tab === '' || tab === undefined || tab === null">
        Outline
      </div>
      <div class="content chat" v-if="tab === 'chat'">
        <chat :module="module"></chat>
      </div>
      <div class="content faq" v-if="tab === 'faq'">
        FAQ
      </div>
    </template>
  </div>
</template>

<script>

import marked from 'marked'

import api from '@/js/api'

import AskQuestion from '@/components/AskQuestion'
import Chat from '@/components/Chat'

export default {
  name: 'Module',
  components: {
    AskQuestion,
    Chat
  },
  props: ['id', 'tab'],
  data () {
    return {
      module: null,
      editMode: false,
      rawOutline: ''
    }
  },
  computed: {
    tabHeader () {
      if (this.tab === 'faq') return 'Questions & Answers'
      if (this.tab === 'chat') return 'Messenger'
      return 'Module Outline'
    },
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
  },
  watch: {
    '$route' (to, from) {
      if (to.params.id !== from.params.id) {
        this.fetch()
      }
    }
  }
}

</script>

<style lang="scss" scoped>
.module {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;

  > .toolbar {
    display: flex;
    background-color: #1E88E5;
    color: #f8f8f8;
    align-items: center;
    padding: 0 .5rem;
    flex-shrink: 0;

    > .md-title {
      flex: 1;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      padding: .75rem 0;
      font-size: 1rem;
      text-align: left;
    }

    > .buttons {
      display: flex;
      align-items: center;

      .active {
        background: rgba(255, 255, 255, .9);
        color: #1e88e5;
      }
    }

    @media screen and (max-width: 600px) {
      flex-direction: column;

      > .md-title {
        font-size: 1.3rem;
      }
    }
  }

  > .md-title {
    padding: .5rem;
    background-color: lighten(#1e88e5, 5%);
    color: white;
    flex-shrink: 0;
  }

  > .content {
    flex: 1;
    overflow: hidden;
  }
}
</style>
