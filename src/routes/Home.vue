<template>
  <div class="home">
    <div class="center">
      <h1>Home</h1>
      <hr>
      <h2><md-icon>bookmark</md-icon> Your Modules</h2>
      <div class="modules">
        <router-link v-for="mod in $root.modules" :key="mod.code" :to="'/module/' + (mod.ref || mod.code)" class="mod-link">
          <mod-card :mod="mod"></mod-card>
        </router-link>
      </div>
      <hr>
      <h2><md-icon>chat</md-icon> Your Questions</h2>
      <div class="questions" v-if="questions">
        <router-link :to="'/module/' + (q.ref || q.code) + '/q/' + q.questionID" tag="p" v-for="q in questions" :key="q.questionID" class="q">
          {{ questionTitle(q) }} in<span class="module">{{ q.name }}</span><span class="replies"><md-icon>chat</md-icon> {{ q.replies }}</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/js/api'

import ModCard from '@/components/ModCard'

export default {
  name: 'Home',
  components: {
    ModCard
  },
  data () {
    return {
      questions: null
    }
  },
  methods: {
    questionTitle (q) {
      return q.text.split('\n\n')[0]
    }
  },
  beforeMount () {
    if (!this.$root.loggedIn) this.$router.replace('/')
  },
  mounted () {
    api.post('user_questions')
    .then(response => {
      this.questions = response.data.result
    })
    .catch(error => {
      console.error(error)
    })
  }
}
</script>

<style lang="scss" scoped>
.home {
  width: 100%;
  height: 100%;
  padding: .75rem .5rem;
  overflow-y: auto;

  .modules {
    display: flex;
    flex-wrap: wrap;

    > div {
      margin: .5rem 0;
    }
  }

  hr {
    margin-top: .75rem;
    margin-bottom: .75rem;
    border: none;
    width: 100%;
    height: 2px;
    background-color: rgba(0, 0, 0, .1);
  }

  .mod-link {
    text-decoration: none !important;
  }

  .questions {
    width: 100%;
    margin: .5rem 0;
    border-radius: 3px;
    border: 1px solid rgba(0, 0, 0, .2);
    background-color: rgba(255, 255, 255, .9);

    > .q {
      cursor: pointer;
      display: flex;
      align-items: center;
      padding: .5rem;
      border-bottom: 1px dashed rgba(0, 0, 0, .1);
      border-left: 2px solid transparent;

      > .module {
        font-weight: bold;
        font-size: .8rem;
        margin-left: .25rem;
        padding: .1rem .5rem;
        background-color: rgba(40, 40, 50, .05);
        color: rgba(0, 0, 0, .5);
      }

      > .replies {
        margin-left: auto;
        padding: 0 1rem;
        color: rgba(0, 0, 20, .8);

        > i {
          transform: scale(.8);
        }
      }

      &:last-child {
        border-bottom: none;
      }

      &:hover {
        background-color: rgba(53, 148, 232, .05);
        border-left: 2px solid rgba(0, 0, 0, .2);
      }
    }
  }
}
</style>
