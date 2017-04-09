<template>
  <div class='app-sidebar'
       v-show="$root.loggedIn"
       @click="data+=1">
    <h1>SOM</h1>
    <p><b>UserID: </b> {{ $root.userID }}</p>
    <p><b>SessID: </b> {{ $root.loginToken }}</p>
    <hr>
    <h2>Modules</h2>
    <div class="module-list">
      <router-link class="router-link" v-for="mod in modules" :key="mod.code" :to="'/module/' + (mod.ref || mod.moduleID)">
        <md-icon>chevron_right</md-icon> {{ mod.name }}
      </router-link>
    </div>
  </div>
</template>

<script>
import api from '@/js/api'

export default {
  name: 'AppSidebar',
  data() {
    return {
      modules: [],
      data: 1
    }
  },
  methods: {
    fetchModules() {
      if (this.$root.loggedIn) {
        api.post('user_modules')
        .then(response => {
          if (response.data.success) {
            this.modules = response.data.result
          }
        })
      }
    }
  },
  watch: {
    '$root.loggedIn': function () {
      if (this.$root.loggedIn) {
        this.fetchModules()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.app-sidebar {
  width: 25vw;
  max-width: 290px;
  flex-shrink: 0;
  background-color: #e8e8e8;
  overflow: hidden;
}

h1 {
  padding: .5rem;
  text-align: center;
}

h2 {
  font-size: 1rem;
  padding: .25rem;
}

.module-list {
  display: flex;
  flex-direction: column;
  width: 100%;

  > a.router-link {
    color: rgba(0, 0, 0, .4);
    padding: .25rem .25rem .25rem .25rem;
    font-weight: bold;
    border-bottom: 1px solid rgba(0, 0, 0, .1);
    text-decoration: none;
    font-size: .9rem;

    &.active {
      border-right: 4px solid rgba(0, 0, 0, .1);
      background-color: rgba(255, 255, 255, .1);
      color: rgba(34, 32, 155, .6);
    }

    &:hover {
      text-decoration: none;
    }
  }
}
</style>
