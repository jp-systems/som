import { get, post } from 'axios'
import { stringify } from 'qs'

const uri = '/api/ajax.php'

export default {
  get (request, params) {
    params = params || {}
    params.request = request
    let token = window.localStorage.getItem('login_token')
    if (token) params.login_token = token
    return get(uri, {
      params: params
    })
  },

  post (request, data) {
    data = data || {}
    data.request = request
    let token = window.localStorage.getItem('login_token')
    if (token) data.login_token = token
    return post(uri, stringify(data))
  }
}
