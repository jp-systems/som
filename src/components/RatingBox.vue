<template>
  <div class="rating-box" :class="colorClass()">
    <button class="button button-up" @click="upvote">
      <md-icon>thumb_up</md-icon>
    </button>
    <span class="score"><span class="title">Rating</span><br>{{ formatScore }}</span>
    <button class="button button-down" @click="downvote">
      <md-icon>thumb_down</md-icon>
    </button>
    <div class="loading" v-if="loading">
      <md-spinner md-indeterminate></md-spinner>
    </div>
  </div>
</template>

<script>
import api from '@/js/api'

export default {
  name: 'RatingBox',
  props: ['answer'],
  data () {
    return {
      score: (this.answer.positive - this.answer.negative) || 0,
      loading: false
    }
  },
  computed: {
    formatScore () {
      return this.score > 0 ? '+' + this.score : this.score
    }
  },
  methods: {
    upvote () {
      this.vote(1)
    },
    downvote () {
      this.vote(0)
    },
    vote (positive) {
      this.loading = true
      api.post('add_rating', {
        answer_ID: this.answer.answerID,
        positive: positive
      })
      .then(r => {
        // Emit a re-fetch signal
        this.loading = false
        this.$emit('update-rating')
      })
    },
    colorClass () {
      return this.score === 0 ? '' : this.score > 0 ? 'positive' : 'negative'
    }
  }
}
</script>

<style lang="scss" scoped>
.rating-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;

  > .score {
    cursor: default;
    user-select: none;
    padding: 1rem;
    font-size: 1.2rem;
    color: rgba(0, 0, 0, .5);
    font-weight: bold;
    text-align: center;

    > .title {
      color: rgba(0, 0, 0, .4);
      font-size: .7rem;
    }
  }

  > .button {
    cursor: pointer;
    transition: transform 300ms ease-in-out;
    position: absolute;
    width: 100%;
    height: 50%;
    border: none;
    outline: none;
    color: rgba(0, 0, 0, .6);

    &.button-up {
      top: 0;
      background: rgba(100, 200, 100, 1);
      transform: translateY(-100%);
    }

    &.button-down {
      bottom: 0;
      background: rgba(200, 100, 100, 1);
      transform: translateY(100%);
    }
  }

  &:hover {
    > .button-up {
      transform: translateY(0%);
    }

    > .button-down {
      transform: translateY(0%);
    }
  }

  &.positive {
    background-color: rgba(90, 255, 90, .1);
  }

  &.negative {
    background-color: rgba(255, 90, 90, .1);
  }

  > .loading {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background: rgba(220, 220, 220, .9);
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>
