<template>
  <div class="dashboard">
    <div v-if="fileUploaded" class="img_container">
      <img :src="photo" alt="User Photo" ref="img">
    </div>
    <div class="input">
      <input @change="photoUpload" ref="photoUpload" type="file" class="hide">
      <button @click="triggerUpload"><md-icon class="md-size-3x">camera_alt</md-icon></button>
      <p>Upload A Photo</p>
      <p>UserID: {{ $root.userID }}</p>
    </div>
  </div>
</template>

<script>
  import fb from '@/js/fb'

  export default {
    name: 'Dashboard',
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
        let self = this
        let file = e.target.files[0]
        fb.saveUserAvatar(this.$root.userID, file)
        let reader = new FileReader()
        reader.onloadend = function (event) {
          self.photo = event.target.result
        }
        reader.readAsDataURL(file)
      }
    }
  }
</script>

<style lang="scss" scoped>

  .dashboard {
    display: flex;
    flex-direction: column;
    width: 100%;
    align-items: center;
    margin: 3rem 0;
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
    display: flex;
    flex-direction: column;
    align-items: center;
    
    button {
    border-style: none;
    border-width: 0;
    background-color: transparent;
    cursor: pointer;
    color: #80aba3;

      &:hover {
        background-color: #dadada;
      }
    }
  }
</style>
