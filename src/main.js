import 'vue-material/dist/vue-material.css'

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueMaterial from 'vue-material'

import App from '@/App'

import Default from '@/routes/Default'
import Home from '@/routes/Home'
import Module from '@/routes/Module'
import Search from '@/routes/Search'
import Test from '@/routes/Test'

import api from '@/js/api'

Vue.config.productionTip = false
Vue.use(VueRouter)
Vue.use(VueMaterial)

const router = new VueRouter({
  linkActiveClass: 'active',
  routes: [
    { path: '/', component: Default },
    { path: '/home', component: Home },
    { path: '/module/:id', component: Module },
    { path: '/search', component: Search },
    { path: '/search/:query', component: Search },
    { path: '/test', component: Test }
  ]
})

const vm = new Vue({
  render: h => h(App),
  router: router,
  data: {
    modalStatus: null,
    loggedIn: false,
    userID: null,
    user: null
  },
  methods: {
    attemptLogin () {
      let loginToken = window.localStorage.getItem('login_token')
      api.post('verify_session', {
        login_token: loginToken
      })
      .then(r => {
        if (r.data.success) {
          this.loggedIn = true
          this.userID = r.data.result
          this.loginToken = window.localStorage.getItem('login_token')
          this.$router.push('/home')
        }
      })
    }
  },
  mounted () {
    this.attemptLogin()
  }
})

vm.$mount('#app')
