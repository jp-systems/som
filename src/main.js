import Vue from 'vue'
import VueRouter from 'vue-router'

import App from '@/App'

import Home from '@/routes/Home'

Vue.config.productionTip = false
Vue.use(VueRouter)

const vm = new Vue({
  render: h => h(App),
  router: new VueRouter({
    linkActiveClass: 'active',
    routes: [
      { path: '/', component: Home }
    ]
  })
})

vm.$mount('#app')

import axios from 'axios'

axios.get('/api/ajax.php', {
  params: {
    data: 123
  }
})
.then(r => {
  console.log(r)
})
.catch(e => {
  console.log(e)
})