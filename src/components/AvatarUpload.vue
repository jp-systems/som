<template>
  <div class="avatar-upload">
    <div class="input">
      <avatar :userID="$root.userID"></avatar>
    </div>
    <div class="toolbar">
      <input @change="photoUpload" ref="photoUpload" type="file" class="hide">
      <md-button class="md-raised" @click.native="triggerUpload">
        <md-icon>file_upload</md-icon> Upload New Avatar
      </md-button>
    </div>
  </div>
</template>

<script>
import bus from '@/js/bus'
import fb from '@/js/fb'

import Avatar from '@/components/Avatar'

export default {
  name: 'AvatarUpload',
  components: {
    Avatar
  },
  data () {
    return {
      photo: ''
    }
  },
  computed: {
    fileUploaded () {
      return this.photo !== ''
    }
  },
  methods: {
    triggerUpload () {
      this.$refs.photoUpload.click()
    },
    photoUpload (e) {
      let file = e.target.files[0]
      fb.saveUserAvatar(this.$root.userID, file)
      .then(r => {
        bus.$emit('reload-avatar')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.avatar-upload {
  display: flex;
  width: auto;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  margin: 1rem 0;
}

.img_container {
  max-width: 50%;
  max-height: auto;

  img {
    box-shadow: 0px 5px 5px -3px rgba(0,0,0,.90);
  }
}

.hide {
  height: 1px;
  width: 1px;
  overflow: hidden;
}

.input {
  margin: 1rem 1rem 0 1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 40vw;
  height: 40vw;
  max-width: 240px;
  max-height: 240px;
  min-width: 100px;
  min-height: 100px;
  border: 1px solid rgba(0, 0, 0, .2);
  border-radius: 3px;
}

.toolbar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40vw;
  max-width: 240px;
  min-width: 100px;
}
</style>
