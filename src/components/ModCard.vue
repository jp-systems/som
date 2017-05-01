<template>
  <div class="mod-card">
    <md-card md-with-hover class="mod_content">
      <div class="header-colour"></div>
      <md-card-header><h2>{{ mod.code }}</h2></md-card-header>
      <md-card-content>
        <h3>{{ mod.name }}</h3>
      </md-card-content>
      <md-card-actions>
        <md-button v-if="$root.loggedIn" class="md-icon-button bookmark" @click.prevent.native="changeFollow(mod)">
          <md-icon>{{ isFollowing(mod) ? 'bookmark' : 'bookmark_border' }}</md-icon>
        </md-button>
      </md-card-actions>
      <div class="followers" v-if="mod.followers">
        <md-icon>person</md-icon> {{ mod.followers }}
      </div>
    </md-card>
  </div>
</template>

<script>
import _ from 'lodash'

import api from '@/js/api'

export default {
  name: 'ModCard',
  props: ['mod'],
  data () {
    return {
    }
  },
  methods: {
    isFollowing (mod) {
      return this.$root.modules.findIndex(m => m.code === mod.code) !== -1
    },
    changeFollow (mod) {
      if (this.isFollowing(mod)) {
        // Unfollow the module
        this.$root.modules.splice(this.$root.modules.findIndex(m => m.code === mod.code), 1)
        api.post('unfollow_module', {
          module_ID: mod.moduleID
        }).then(response => {
          console.log(response)
        })
      } else {
        // Follow the module
        api.post('fav_module', {
          module_ID: mod.moduleID
        })
        .then(response => {
          this.$root.modules.push(mod)
          this.$root.modules = _.uniqBy(this.$root.modules, m => m.code)
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.md-card {
  margin: 0px 10px;
  overflow: hidden;

  h2,h3 {
    color: rgba(0, 0, 0, .87);
  }
}

.mod_content {
  height: 180px;
}

.bookmark {
  position: absolute;
  bottom: 1px;
  right: 1px;
}

.followers {
  position: absolute;
  bottom: 1px;
  left: 1px;
  color: black;
  padding: .5rem;
  color: rgba(0, 90, 255, .5);
}

.header-colour {
  background-image: linear-gradient(120deg, #fccb90 0%, #d57eeb 100%);
  width: 100%;
  height: 10px;
}
</style>
