<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" v-on:export="exportFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="customers"
        :page-preferences="pagePreferences"
        edit-permission="update_customer"
        delete-permission="delete_customer"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'CustomerIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Customers', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_autosearch: 'customers_index_autosearch'
      }
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Customers')
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_customer')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/customers/create',
          type: 'a',
          color: 'teal',
          icon: 'add'
        })
      }
      return arr
    }
  },
  methods: {
    exportFn () {
      this.$q.loading.show()
      this.$axios({
        url: 'customers/export',
        method: 'GET',
        responseType: 'blob' // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'customers.xlsx')
        document.body.appendChild(link)
        link.click()
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  }
}
</script>
