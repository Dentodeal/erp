import Vue from 'vue'
import Vuex from 'vuex'
import { axiosInstance } from 'boot/axios'
import { Notify } from 'quasar'
Vue.use(Vuex)

export default function (/* { ssrContext } */) {
  const Store = new Vuex.Store({
    modules: {
      // example
    },
    state: {
      authenticated: null,
      appLoading: true,
      loadedRoute: null,
      user: null,
      roles: [],
      permissions: [],
      pageTitle: 'ApexionERP',
      toolbarButtons: null,
      apiURL: process.env.PROD ? 'https://app.connectapexion.xyz/api' : 'http://api.erp.ss/api',
      baseURL: process.env.PROD ? 'https://app.connectapexion.xyz' : 'http://api.erp.ss'
    },
    getters: {
      isAuthenticated: (state) => {
        return state.authenticated
      },
      hasPermissionTo: (state) => (val) => {
        const ind = state.permissions.findIndex((item) => {
          return item.name === val
        })
        return ind !== -1
      },
      hasRole: (state) => (val) => {
        const ind = state.roles.findIndex((item) => {
          return item === val
        })
        return ind !== -1
      },
      user: (state) => {
        return state.user
      },
      apiURL: (state) => {
        return state.apiURL
      },
      baseURL: (state) => {
        return state.baseURL
      }
    },
    mutations: {
      setAuthenticated (state, status) {
        state.authenticated = status
      },
      setAppLoading (state, status) {
        state.appLoading = false
      },
      setLoadedRoute (state, obj) {
        state.loadedRoute = obj
      },
      setUser (state, obj) {
        state.user = obj
      },
      setRoles (state, data) {
        state.roles = data
      },
      setPermissions (state, data) {
        state.permissions = data
      },
      setPageTitle (state, data) {
        state.pageTitle = data
      },
      setToolbarButtons (state, data) {
        state.toolbarButtons = data
      }
    },
    actions: {
      init ({ state, commit }) {
        axiosInstance.get(axiosInstance.defaults.authURL + '/sanctum/csrf-cookie').then(() => {
          axiosInstance.get('user').then((res) => {
            if (res.status === 200 && res.data.model.active) {
              commit('setAuthenticated', true)
              commit('setUser', res.data.model)
              commit('setRoles', res.data.roles)
              commit('setPermissions', res.data.permissions)
              // commit('setAppLoading', false)
            } else {
              commit('setAuthenticated', false)
              commit('setAppLoading', false)
            }
          }).catch((e) => {
            commit('setAuthenticated', false)
            commit('setAppLoading', false)
          })
        })
      },
      login ({ state, commit, dispatch }, credentials) {
        const data = {
          route: axiosInstance.defaults.authURL + '/login',
          credentials: credentials,
          mode: 'spa'
        }
        return dispatch('processLogin', data)
      },
      logout ({ commit }) {
        return new Promise((resolve, reject) => {
          axiosInstance.post(axiosInstance.defaults.authURL + '/logout').then(() => {
            commit('setAuthenticated', false)
            resolve()
          // eslint-disable-next-line node/handle-callback-err
          }).catch((err) => {
            reject()
          })
        })
      },
      processLogin ({ state, commit }, data) {
        return new Promise((resolve, reject) => {
          axiosInstance.post(data.route, data.credentials).then((res) => {
            axiosInstance.get('user').then((res2) => {
              if (res2.status === 200 && res2.data.model.active) {
                commit('setAuthenticated', true)
                commit('setUser', res2.data.model)
                commit('setRoles', res2.data.roles)
                commit('setPermissions', res2.data.permissions)
                resolve()
              } else if (res2.status === 200 && !res2.data.model.active) {
                reject(new Error('user-inactive'))
              } else {
                reject()
              }
            })
          }).catch((err) => {
            if (err.response.status === 422) {
              reject('error-422')
            } else {
              reject()
            }
          })
        })
      },
      copyToClipboard ({ commit }, data) {
        const el = document.createElement('textarea')
        el.value = data
        el.setAttribute('readonly', '')
        el.style.position = 'absolute'
        el.style.left = '-9999px'
        document.body.appendChild(el)
        el.select()
        document.execCommand('copy')
        document.body.removeChild(el)
        Notify.create({ message: 'Copied to clipboard' })
      }
    },
    // enable strict mode (adds overhead!)
    // for dev mode only
    strict: process.env.DEV
  })
  return Store
}
