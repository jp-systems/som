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
  </div>
</template>

<script>
export default {
  name: 'AskQuestion',
  data () {
    return {
      title: '',
      content: ''
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
}
</style>
