<template>
  <div class="module">
    <template v-if="module !== null">
      <div class="toolbar">
        <h2 class="md-title">
          <md-button class="md-icon-button" @click.native="changeFollow">
            <md-icon>{{ isFollowing ? 'bookmark' : 'bookmark_border' }}</md-icon>
            <md-tooltip md-direction="bottom">{{ isFollowing ? 'Unfollow' : 'Follow' }}</md-tooltip>
          </md-button>
          {{ module.name }}
        </h2>
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
      <div class="content outline" v-if="(tab === '' || tab === undefined) && qid === undefined">
        <template v-if="!editMode">
          <section>
            <div v-html="outlineHTML"></div>
            <p>Last updated by {{ module.username }} @ {{ module.updatedOn }}</p>
            <md-button class="md-raised" @click.native="editMode = true">Edit</md-button>
          </section>
        </template>
        <template v-else>
          <section class="edit-page">
            <article>
              <textarea cols="30" rows="20" v-model="rawOutline"></textarea>
              <div class="edit-btns">
                <md-button class="md-raised md-warn" @click.native="cancelEdit">Cancel</md-button>
                <md-button class="md-raised md-primary" @click.native="saveEdit">Save changes</md-button>
              </div>
            </article>
            <article>
              <h2>{{ module.code }}: {{ module.name }}</h2>
              <div v-html="editModeHTML"></div>
            </article>
          </section>
        </template>
      </div>
      <div class="content chat" v-if="tab === 'chat'">
        <chat :module="module"></chat>
      </div>
      <div class="content faq" v-if="tab === 'faq'">
        <div class="questions">
          <router-link v-for="q in questions" :key="q.questionID" :to="'/module/' + id + '/q/' + q.questionID" class="q">
            <md-icon>chat_bubble</md-icon> {{ questionTitle(q) }}
            <span class="posted-on">{{ datetime(q.createdOn) }}</span>
          </router-link>
          <p v-if="questions.length === 0" class="notice"><md-icon>mood_bad</md-icon> Nobody has asked any questions...</p>
        </div>
        <transition name="fade">
          <ask-question v-if="askQuestionOpen" :module-id="module.moduleID" @close="askQuestionOpen=false" @done="questionAsked"></ask-question>
        </transition>
        <md-button v-if="!askQuestionOpen" class="md-fab md-fab-bottom-right" @click.native="askQuestionOpen=true">
          <md-icon>add</md-icon>
          <md-tooltip md-direction="top">Ask Question</md-tooltip>
        </md-button>
      </div>
      <div class="content q" v-if="qid && question">
        <div class="title">
          <h1><md-icon>question_answer</md-icon> {{ question.text.split('\n\n')[0] }}</h1>
          <p class="askedOn">Asked on {{ question.createdOn }}</p>
        </div>
        <p class="text">{{ question.text.split('\n\n')[1] }}</p>
        <hr>
        <div class="answers" v-if="!postReplyOpen">
          <h1>ANSWERS</h1>
        </div>
        <transition name="fade">
          <post-reply v-if="postReplyOpen" :question-id="qid" @close="postReplyOpen=false" @done="replyPosted"></post-reply>
        </transition>
        <md-button v-if="!postReplyOpen" class="md-fab md-fab-bottom-right" @click.native="postReplyOpen=true">
          <md-icon>add</md-icon>
          <md-tooltip md-direction="top">Post Reply</md-tooltip>
        </md-button>
      </div>
    </template>
  </div>
</template>

<script>
import _ from 'lodash'
import marked from 'marked'

import api from '@/js/api'
import datetime from '@/js/datetime'

import AskQuestion from '@/components/AskQuestion'
import Chat from '@/components/Chat'
import PostReply from '@/components/PostReply'

export default {
  name: 'Module',
  components: {
    AskQuestion,
    Chat,
    PostReply
  },
  props: ['id', 'tab', 'qid'],
  data () {
    return {
      module: null,
      editMode: false,
      rawOutline: '',
      questions: null,
      askQuestionOpen: false,
      postReplyOpen: false,
      question: null,
      answers: null
    }
  },
  computed: {
    isFollowing () {
      if (!this.$root.modules) return false
      return this.$root.modules.findIndex(m => m.code === this.module.code) !== -1
    },
    tabHeader () {
      if (this.qid) return 'Question'
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
      this.editMode = false
      this.question = null
      this.answers = null
      this.askQuestionOpen = false
      this.postReplyOpen = false
      api.get('get_module', {
        module_id: this.id
      })
      .then(response => {
        if (response.data.success) {
          this.module = response.data.result
          this.rawOutline = this.module.outline
          this.getQuestions()
        }
      })
      .catch(error => {
        console.error(error)
      })
    },
    fetchQuestion () {
      api.get('get_question', {
        questionID: this.qid
      })
      .then(r => {
        this.question = r.data.result
      })
    },
    questionAsked () {
      this.getQuestions()
      this.askQuestionOpen = false
    },
    replyPosted () {
      this.getAnswers()
      this.postReplyOpen = false
    },
    getQuestions () {
      api.get('get_questions', {
        moduleID: this.module.moduleID
      })
      .then(r => {
        this.questions = r.data.result
      })
      .catch(error => {
        console.error(error)
      })
    },
    getAnswers () {

    },
    endEdit () {
      this.editMode = false
      this.module.outline = this.rawOutline
    },
    enableEdit () {
      this.editMode = true
      this.$nextTick(() => this.$refs.editArea.focus())
    },
    questionTitle (q) {
      return q.text.split('\n\n')[0]
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
    },
    changeFollow () {
      if (this.isFollowing) {
        // Unfollow the module
        this.$root.modules.splice(this.$root.modules.findIndex(m => m.code === this.module.code), 1)
        api.post('unfollow_module', {
          module_ID: this.module.moduleID
        }).then(response => {
          console.log(response)
        })
      } else {
        // Follow the module
        api.post('fav_module', {
          module_ID: this.module.moduleID
        })
        .then(response => {
          this.$root.modules.push(this.module)
          this.$root.modules = _.uniqBy(this.$root.modules, m => m.code)
        })
      }
    },
    datetime (format) {
      return datetime(format)
    }
  },
  mounted: function () {
    this.fetch()
    if (this.qid) this.fetchQuestion()
  },
  watch: {
    '$route' (to, from) {
      if (to.params.id !== from.params.id) {
        this.fetch()
      }
      if (to.params.qid) {
        this.question = null
        this.fetchQuestion()
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
      display: flex;
      align-items: center;
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
        padding: 0;
        font-size: .8rem;
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
    display: flex;
    flex-direction: column;
    flex: 1;
    overflow: hidden;
  }
  > .outline {
    margin: 1rem .5rem;

    > .edit-page {
      display: flex;
      justify-content: space-between;

      > * {
        flex: 1;
        padding: 0 .6rem;
      }

      h2 {
        margin-bottom: 1rem;
      }

      textarea {
        width: 100%;
        padding: .3rem .4rem;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0,0,0,.3);
        border: 0;
        font-size: 1rem;
        outline: none;
        background-color: rgba(255, 255, 255, .87);
      }

      .edit-btns {
        display: flex;
        justify-content: space-between;
      }
    }
  }
  > .faq {
    overflow-y: auto;

    > .ask-question {
      margin-top: auto;
    }

    > .questions {
      margin: .5rem;
      border-radius: 3px;
      border: 1px solid rgba(0, 0, 0, .2);
      background-color: rgba(255, 255, 255, .9);


      > .q {
        border-left: 2px solid transparent;
        display: flex;
        color: black;
        cursor: pointer;
        padding: .5rem;
        border-bottom: 1px dashed rgba(0, 0, 0, .1);
        text-decoration: none;
        align-items: center;

        > .md-icon {
          color: lighten(#3594E8, 30%);
          margin-right: .5rem;
          margin-left: 0;
        }

        > .posted-on {
          margin-left: auto;
          font-size: .8rem;
          color: rgba(0, 0, 0, .5);
        }

        &:last-child {
          border-bottom: none;
        }

        &:hover {
          background-color: rgba(53, 148, 232, .05);
          border-left: 2px solid rgba(0, 0, 0, .2);
        }
      }

      > .notice {
        padding: 1rem;
        font-size: 1.2rem;
        text-align: center;
      }
    }
  }

  > .q {
    > .title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: .5rem;
      padding: 1rem;
      background-color: rgba(30, 136, 229, .8);
      color: white;
      border-radius: 3px;
      border: 2px solid rgba(0, 0, 0, .1);
    }

    > .text {
      margin: -.5rem 1rem .5rem 1rem;
      padding: 1rem;
      background: rgba(255, 255, 255, .7);
      border: 1px solid rgba(0, 0, 0, .1);
      border-top: none;
      border-radius: 0 0 3px 3px;
    }

    > .post-reply {
      margin-top: auto;
    }
  }
}
</style>
