// Main file to set up Vue instance, router and user's data
import 'vue-material/dist/vue-material.css'

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueMaterial from 'vue-material'

import App from '@/App'

import Default from '@/routes/Default'
import Home from '@/routes/Home'
import Module from '@/routes/Module'
import Profile from '@/routes/Profile'
import Search from '@/routes/Search'

// TO DELETE
// import ModInput from '@/routes/ModInput'

import api from '@/js/api'

Vue.config.productionTip = false
Vue.use(VueRouter)
Vue.use(VueMaterial)

Vue.material.registerTheme('default', {
  primary: {
    color: 'blue',
    hue: '600'
  },
  accent: {
    color: 'orange',
    hue: 'A700'
  },
  warn: {
    color: 'red',
    hue: 'A400'
  },
  background: 'white'
})

const router = new VueRouter({
  linkActiveClass: 'active',
  routes: [
    { path: '/', component: Default },
    { path: '/home', component: Home },
    { path: '/module/:id', component: Module, props: true },
    { path: '/module/:id/:tab', component: Module, props: true },
    { path: '/module/:id/q/:qid', component: Module, props: true },
    { path: '/profile', component: Profile },
    { path: '/search', component: Search },
    { path: '/search/:query', component: Search, props: true }
    // { path: '/modinput', component: ModInput }
  ]
})

const vm = new Vue({
  render: h => h(App),
  router: router,
  data: {
    modalStatus: null,
    loggedIn: false,
    userID: null,
    user: null,
    modules: null
  },
  methods: {
    attemptLogin () {
      api.post('verify_session')
      .then(r => {
        if (r.data.success) {
          this.loggedIn = true
          this.userID = r.data.result
          this.loginToken = window.sessionStorage.getItem('login_token') || window.localStorage.getItem('login_token')
          api.post('user_data')
          .then(r => {
            if (r.data.success) {
              this.user = r.data.result
              this.$router.push('/home')
            } else {
              throw new Error(r.data.message)
            }
          })
          .catch(e => {
            console.error(e)
          })
        } else {
          window.localStorage.removeItem('login_token')
          window.sessionStorage.removeItem('login_token')
          throw new Error('The login token was rejected by the server, deleting token...')
        }
      })
      .catch(e => {
        console.warn(e)
      })
    }
  },
  mounted () {
    if (window.localStorage.getItem('login_token') || window.sessionStorage.getItem('login_token')) this.attemptLogin()
  }
})

vm.$mount('#app')
