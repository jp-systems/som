<template>
  <div id="app">
    <app-sidebar></app-sidebar>
    <div class="page">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import api from '@/js/api'

import AppSidebar from '@/components/AppSidebar'

export default {
  name: 'App',
  data () {
    return {
      
    }
  },
  components: {
    AppSidebar
  },
}
</script>

<style lang="scss">

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html, body, #app {
  width: 100%;
  height: 100%;
  overflow: hidden;
}

#app {
  display: flex;
  background: #fffbfe;
  font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;

  > .page {
    flex: 1;
  }
}

h1, h2, h3, h4 {
  font-family: 'Comfortaa', cursive;
}
</style>
