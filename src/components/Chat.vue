<template>
  <div class='chat'>
    <h1>Chat!</h1>
    <div class="messages">
      <p v-for="message in messages" class="message">
        <span class="user">{{ message.username }}</span><span class="content" v-html="messageHTML(message.message)"></span>
      </p>
    </div>
    <md-input-container v-if="$root.loggedIn">
      <label>Message</label>
      <md-input v-model="message" @keypress.native.enter="post"></md-input>
    </md-input-container>
    <p class="error" v-else>
      <md-icon>error</md-icon> You must be logged in to chat!
    </p>
  </div>
</template>

<script>
import marked from 'marked'

import fb from '@/js/fb'

export default {
  name: 'Chat',
  props: {
    module: {
      required: true
    }
  },
  data () {
    return {
      messages: {},
      message: ''
    }
  },
  methods: {
    post () {
      if (this.message === null || this.message === '' || this.$root.user === null) return
      fb.postMessage(this.module.moduleID, this.$root.user, this.message)
      this.message = ''
    },
    watch (snapshot) {
      this.messages = snapshot.val()
    },
    messageHTML (msg) {
      return marked(msg)
    }
  },
  mounted () {
    fb.watchMessages(this.module.moduleID, this.watch)
  },
  beforeDestroy () {
    fb.unwatchMessages(this.module.moduleID)
  }
}
</script>

<style lang="scss" scoped>
.chat {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;
  flex: 1;
  color: black;

  .message {
    display: flex;
    align-items: center;
    padding: .25rem 0;

    > .user {
      position: relative;
      font-size: 1rem;
      font-weight: bold;
      width: 10vw;
      min-width: 70px;
      margin-right: 1rem;
      text-align: right;
      padding: .25rem;
      color: royalblue;
    }

    > .content {
      font-size: .8rem;
    }

    @media (max-width: 600px) {
      flex-direction: column;
      align-items: flex-start;
      padding: 2px 0;
      
      > .user {
        text-align: left;
        padding: 0;
        margin: 0;
      }

      > .content {
        padding: 0;
        margin: 0;
      }

    }
  }

  .error {
    text-align: center;
    font-weight: bold;
    color: rgba(230, 140, 140, .8);
    font-size: 1.2rem;
  }
}
</style>
