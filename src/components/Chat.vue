<template>
  <div class='chat'>
    <div class="messages" ref="messages">
      <p v-for="(message, key) in messages" :key="key" class="message">
        <span class="user">{{ message.username || '?' }}</span>
        <span class="content" v-html="messageHTML(message.message)"></span>
        <span class="time">{{ formatTime(message.timestamp) }}</span>
      </p>
      <div v-if="!messages || messages.length === 0" class="no-messages">
        <i class="material-icons">mood_bad</i>
        <p>There are no messages!</p>
      </div>
    </div>
    <div class="text-entry">
      <md-input-container v-if="$root.loggedIn">
        <md-icon>message</md-icon>
        <label>Message</label>
        <md-input v-model="message" placeholder="Enter a message..." @keypress.native.enter="post" autofocus></md-input>
      </md-input-container>
      <md-button class="desktop-only md-icon-button md-primary" :class="{ 'md-raised': message !== '' }" @click.native="post">
        <md-icon>local_post_office</md-icon>
        <md-tooltip md-direction="left">Post Message</md-tooltip>
      </md-button>
    </div>
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
      // Scroll down the messages div
      this.$nextTick(() => {
        this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight
      })
    },
    messageHTML (msg) {
      let escaped = String(msg).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')
      return marked(escaped)
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

  .messages {
    flex: 1;
    overflow-x: hidden;
    overflow-y: auto;

    > .no-messages {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;

      > i {
        color: rgba(255, 130, 0, .9);
        font-size: 4rem;
      }

      > p {
        font-style: italic;
        color: rgba(0, 0, 0, .5);
      }
    }
  }

  .message {
    transition: background-color 300ms ease-out;
    display: flex;
    align-items: center;
    padding: .2rem .5rem;

    > .user {
      font-size: 1rem;
      font-weight: bold;
      color: royalblue;
      margin-right: .5rem;
    }

    > .content {
      font-size: 1rem;
    }

    > .time {
      font-size: .7rem;
      margin-left: auto;
      color: rgba(0, 0, 0, .2);
      background-color: rgba(0, 0, 0, .02);
      padding: 0 .25rem;
      border-radius: 3px;
    }

    &:hover {
      background-color: rgba(0, 0, 0, .05);

      & > .time {
        background-color: rgba(0, 0, 0, .3);
        color: white;
      }
    }

    @media screen and (max-width: 600px) {
      > .user, > .content {
        font-size: 1rem;
      }
    }
  }

  .text-entry {
    display: flex;
    align-items: center;
    margin: .5rem;
    padding: .5rem;
    border: 2px solid rgba(0, 0, 0, .2);
    border-radius: 3px;
    background: white;

    > .md-input-container {
      margin: 0;
    }
  }
}
</style>
