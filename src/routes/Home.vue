<template>
  <div class="home">
    <h1>Hello</h1>
    <login></login>
    <register></register>
  </div>
</template>

<script>
import Login from '@/components/Login'
import Register from '@/components/Register'

export default {
  name: 'Home',
  data () {
    return {

    }
  },
  components: {
    Login,
    Register
  }
}
</script>

<style lang="scss" scoped>
.home {
  width: 100%;
  height: 100%;
}
</style>
