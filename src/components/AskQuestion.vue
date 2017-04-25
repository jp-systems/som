<template>
  <div class="ask-question">
    <md-input-container>
      <md-icon>question_answer</md-icon>
      <label>Title</label>
      <md-input type="text" v-model="title"></md-input>
    </md-input-container>
    <md-input-container>
      <md-icon>message</md-icon>
      <label>Question</label>
      <md-textarea v-model="content"></md-textarea>
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
        <md-button class="md-raised md-primary">
          <md-icon>add_circle</md-icon> Post Question</md-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AskQuestion',
  data () {
    return {
      title: '',
      content: '',
      anonymous: false
    }
  }
}
</script>

<style lang="scss" scoped>
.ask-question {
  margin: .5rem;
  padding: .5rem;
  border-radius: 5px;
  border: 1px solid rgba(0, 0, 0, .1);
  background-color: white;

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
        background: rgba(255, 30, 0, .2);
        border-radius: 5px;

        &.anonymous {
          background: rgba(0, 255, 30, .2);
        }
      }
    }
  }
}
</style>
