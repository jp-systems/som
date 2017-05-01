<template>
  <div class="mod-card">
    <md-card md-with-hover class="mod_content">
      <md-card-area>
        <div :style="styleHeader" class="header-colour"></div>
        <md-card-header>
          <div class="md-title">{{ mod.code }}</div>
          <div class="md-subhead">{{ mod.name }}</div>  
        </md-card-header>
        <md-card-content>
          <div class="content"></div>
        </md-card-content>
      </md-card-area>
      <md-card-actions>
        <md-button v-if="$root.loggedIn" class="md-icon-button bookmark" @click.prevent.native="changeFollow(mod)">
          <md-icon>{{ isFollowing(mod) ? 'bookmark' : 'bookmark_border' }}</md-icon>
          <md-tooltip md-direction="bottom">Bookmark</md-tooltip>
        </md-button>
      </md-card-actions>
      <div class="followers" v-if="mod.followers">
        <md-icon>person</md-icon> {{ mod.followers }}
        <md-tooltip md-direction="bottom">Followers</md-tooltip>
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
      styleHeader: {
        backgroundImage: 'linear-gradient(120deg,' + 'hsl(' + Math.floor(Math.random() * 360) + ',' + Math.floor(Math.random() * 100) + '%,' + Math.floor(Math.random() * 100) + '%)0%,' + 'hsl(' + Math.floor(Math.random() * 360) + ',' + Math.floor(Math.random() * 100) + '%,' + Math.floor(Math.random() * 100) + '%)100%)'
      }
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
  color: rgba(0, 0, 0, .87);

  .md-card-content {
    padding-top: 20px;

    > i {
      color: #1e88e5;
    }
  }
  .md-card-actions {
    margin-top: auto;
    height: 40px;
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
  width: 100%;
  height: 10px;
}
</style>
