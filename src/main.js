import Vue from 'vue'
import VueRouter from 'vue-router'

import App from '@/App'

import Home from '@/routes/Home'
import Module from '@/routes/Module'
import Search from '@/routes/Search'
import Test from '@/routes/Test'

import api from '@/js/api'

Vue.config.productionTip = false
Vue.use(VueRouter)

const vm = new Vue({
  render: h => h(App),
  router: new VueRouter({
    linkActiveClass: 'active',
    routes: [
      { path: '/', component: Home },
      { path: '/module/:id', component: Module },
      { path: '/search', component: Search },
      { path: '/search/:query', component: Search },
      { path: '/test', component: Test }
    ]
  }),
  data: {
    loggedIn: false,
    userID: null
  },
  mounted () {
    let loginToken = window.localStorage.getItem('login_token')
    api.post('verify_session', {
      login_token: loginToken
    })
    .then(r => {
      if (r.data.success) {
        this.loggedIn = true
        this.userID = r.data.result
      }
    })
  }
})

vm.$mount('#app')
