<template>
  <div id="app">
    <h1>HELLSJAHO!</h1>
    <test-login></test-login>
  </div>
</template>

<script>
import TestLogin from '@/components/TestLogin'

export default {
  name: 'app',
  data () {
    return {
      
    }
  },
  components: {
    TestLogin
  }
}
</script>

<style lang="scss">
html {
  background: white;
}
</style>
