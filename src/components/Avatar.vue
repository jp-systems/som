<template>
  <div class="avatar">
    <img v-if="imageURL !== null" :src="imageURL">
    <md-icon v-else>person</md-icon>
  </div>
</template>

<script>
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
  mounted () {
    fb.getUserAvatar(this.userID)
    .then(response => {
      this.imageURL = response
    })
  }
}
</script>

<style lang="scss" scoped>
.avatar {
  background-color: rgba(0, 0, 0, .1);
  width: 100%;
  height: 100%;
}
</style>
