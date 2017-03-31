<template>
  <div class="home">
    <h1>Hello</h1>
    <login></login>
  </div>
</template>

<script>
import Login from '@/components/Login'

export default {
  name: 'Home',
  data () {
    return {

    }
  },
  components: {
    Login
  }
}
</script>

<style lang="scss" scoped>
.home {
  width: 100%;
  height: 100%;
}
</style>
