<template>
  <div class="ask-question">
    <md-button class="md-icon-button md-warn md-raised md-dense close" @click.native="$emit('close')">
      <md-icon>close</md-icon>
    </md-button>
    <md-input-container>
      <md-icon>question_answer</md-icon>
      <label>Title</label>
      <md-input type="text" v-model="title" required></md-input>
    </md-input-container>
    <md-input-container>
      <md-icon>message</md-icon>
      <label>Question</label>
      <md-textarea v-model="content" required></md-textarea>
    </md-input-container>
    <div class="section">
      <div class="flex">
        <md-switch v-model="anonymous">Post Anonymously?</md-switch>
        <p :class="{ anonymous : anonymous }">
          <md-icon v-if="!anonymous">warning</md-icon>
          <span v-if="anonymous">This question will be asked anonymously on your behalf.</span>
          <span v-else>This question will not be asked anonymously, and your username will be visible to other users.</span>
        </p>
      </div>
      <div>
        <md-button class="md-raised md-primary" @click.native="postQuestion">
          <md-icon>add_circle</md-icon> Post Question
        </md-button>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/js/api'

export default {
  name: 'AskQuestion',
  props: ['module-id'],
  data () {
    return {
      title: '',
      content: '',
      anonymous: false
    }
  },
  methods: {
    postQuestion () {
      api.post('post_question', {
        module_ID: this.moduleId,
        text: this.title + '\n\n' + this.content,
        anonymous: this.anonymous ? 1 : 0
      })
      .then(r => {
        this.$emit('done')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.ask-question {
  position: relative;
  margin: .5rem;
  padding: .5rem;
  border-radius: 5px;
  border: 1px solid rgba(0, 0, 0, .1);
  background-color: white;

  > .close {
    position: absolute;
    right: -.5rem;
    top: -.5rem;
    z-index: 2;
  }

  > .section {
    display: flex;
    align-items: stretch;
    justify-content: center;
    max-width: 900px;
    margin: 0 auto;

    > div {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: .5rem;
      margin: .5rem;
      background: rgba(0, 0, 0, .05);
      border-radius: 5px;
      border: 1px solid rgba(0, 0, 0, .1);

      &.flex {
        flex: 1;
      }

      > p {
        font-size: .7rem;
        color: rgba(0, 0, 0, .5);
        font-weight: bold;
        margin: .5rem;
        padding: .5rem;
        border: 1px solid rgba(0, 0, 0, .1);
        background: rgba(255, 90, 90, .15);
        border-radius: 5px;

        &.anonymous {
          background: rgba(0, 255, 30, .15);
        }
      }
    }

    @media screen and (max-width: 600px) {
      flex-direction: column;
    }
  }
}
</style>
