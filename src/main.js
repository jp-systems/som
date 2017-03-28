import Vue from 'vue'
import App from '@/App'

import axios from 'axios'

const vm = new Vue({
  render: h => h(App)
})

vm.$mount('#app')

axios.get('api/ajax.php', {
  params: {
    request: 'get_username',
    user_id: 'e726e548293871a9'
  }
})
.then(response => {
  console.log(response)
})
