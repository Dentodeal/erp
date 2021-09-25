export default ({ router, store, Vue }) => {
  router.beforeEach((to, from, next) => {
    if (to.name !== 'LoginPage' && !store.getters.isAuthenticated) {
      next({ name: 'LoginPage' })
    } else if (to.name === 'LoginPage' && store.getters.isAuthenticated) {
      next({ name: 'Dashboard' })
    } else {
      next()
    }
  })
}
