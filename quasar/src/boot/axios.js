// eslint-disable-next-line import/no-extraneous-dependencies
import Vue from 'vue'
import axios from 'axios'

let axiosInstance = axios.create({
  baseURL: 'http://api.erp.ss/api',
  authURL: 'http://api.erp.ss',
  withCredentials: true
})

if (process.env.PROD) {
  axiosInstance = axios.create({
    baseURL: 'https://app.apexion.ml/api',
    authURL: 'https://app.apexion.ml',
    withCredentials: true
  })
}

window.intercepted = new Vue()
axiosInstance.interceptors.response.use((response) => {
  return response
}, (error) => {
  if (error.response.status === 401) {
    window.intercepted.$emit('error401')
  } else if (error.response.status === 403) {
    window.intercepted.$emit('error403', error.response.data.message)
  } else if (error.response.status === 500) {
    window.intercepted.$emit('error500')
  } else if (error.response.status === 404) {
    window.intercepted.$emit('error404')
  } else {
    return Promise.reject(error)
  }
})
// for use inside Vue files through this.$axios
Vue.prototype.$axios = axiosInstance

// Here we define a named export
// that we can later use inside .js files:
export { axiosInstance }
