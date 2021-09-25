// import something here

// "async" is optional;
// more info on params: https://quasar.dev/quasar-cli/cli-documentation/boot-files#Anatomy-of-a-boot-file
export default ({ Vue }) => {
  Vue.mixin({
    methods: {
      getLocaleString (val) {
        if (val) {
          const d = new Date(val)
          return d.toLocaleString('en-IN')
        }
        return ''
      }
    }
  })
}
