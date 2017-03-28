<template>
  <div class="test-login">
    Username
    <input type="text" v-model="username">
    Password
    <input type="password" v-model="password">
    <input type="submit" @click="doSomething">
  </div>
</template>

<script>
import axios from 'axios'
import qs from 'qs'

export default {
  name: 'TestLogin',
  data () {
    return {
      username: '',
      password: ''
    }
  },
  methods: {
    doSomething () {
      axios.post('./api/ajax.php', qs.stringify({
        request: 'create_user',
        username: this.username,
        password: this.password
      }))
      .then(response => {
        console.log(response)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.test-login {
  background: #bada55;
}
</style>
