<template>
  <div class="home">
    <h1>Hello</h1>
    <test-login></test-login>
  </div>
</template>

<script>
import TestLogin from '@/components/TestLogin'

export default {
  name: 'Home',
  data () {
    return {

    }
  },
  components: {
    TestLogin
  }
}
</script>

<style lang="scss" scoped>
.home {
  width: 100%;
  height: 100%;
}
</style>
