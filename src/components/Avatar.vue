<template>
  <div class="avatar">
    <img v-if="imageURL !== null" :src="imageURL">
    <md-icon v-else>person</md-icon>
  </div>
</template>

<script>
import bus from '@/js/bus'
import fb from '@/js/fb'

export default {
  name: 'Avatar',
  props: ['userID'],
  data () {
    return {
      imageURL: null,
      imageError: null
    }
  },
  methods: {
    loadAvatar () {
      fb.getUserAvatar(this.userID)
      .then(response => {
        this.imageURL = response
      })
    }
  },
  mounted () {
    this.loadAvatar()
    bus.$on('reload-avatar', e => {
      this.imageURL = null
      this.loadAvatar()
    })
  }
}
</script>

<style lang="scss" scoped>
.avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, .1);
  width: 100%;
  height: 100%;

  > img {
    width: 100%;
    height: 100%;
  }
}
</style>
