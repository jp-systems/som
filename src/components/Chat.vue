<template>
  <div class='chat'>
    <h1>Chat!</h1>
    <div class="messages">
      <p v-for="(message, key) in messages" :key="key" class="message">
        <span class="user">{{ message.username || '?' }}</span>
        <span class="content" v-html="messageHTML(message.message)"></span>
        <span class="time">{{ formatTime(message.timestamp) }}</span>
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
    formatTime (timestamp) {
      let now = new Date()
      let date = new Date(timestamp)
      if (now.getDate() === date.getDate() && now.getMonth() === date.getMonth() && date.getFullYear() === now.getFullYear()) {
        // Same day
        let hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours()
        let mins = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()
        return hours + ':' + mins
      } else {
        // In the past
        return date.getDate() + '/' + date.getMonth() + '/' + String(date.getFullYear()).slice(2, 4)
      }
    },
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
    padding: .2rem .5rem;

    > .user {
      font-size: .8rem;
      font-weight: bold;
      color: royalblue;
      margin-right: .5rem;
    }

    > .content {
      font-size: .8rem;
    }

    > .time {
      font-size: .7rem;
      margin-left: .25rem;
      color: rgba(0, 0, 0, .4);
    }

    &:hover {
      background-color: rgba(0, 0, 0, .1);
    }

    @media screen and (max-width: 600px) {
      > .user, > .content {
        font-size: 1rem;
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
