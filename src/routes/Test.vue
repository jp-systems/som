<template>
  <div class="test">
    <login></login>
    <register></register>
  </div>
</template>

<script>
import Login from '@/components/Login'
import Register from '@/components/Register'

export default {
  name: 'Test',
  data () {
    return { }
  },
  components: {
    Login,
    Register
  }
}
</script>

<style lang="scss" scoped>
.test {
  width: 100%;
  height: 100%;
}
</style>
