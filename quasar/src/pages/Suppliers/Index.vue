<template>
  <q-page>
    <page-toolbar page-title="Suppliers" :buttons="toolbarButtons" v-on:export="exportFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="suppliers"
        :page-preferences="pagePreferences"
        edit-permission="update_supplier"
        delete-permission="delete_supplier"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SupplierIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Suppliers', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'suppliers_index_visible_columns',
        page_index_pagination: 'suppliers_index_pagination',
        page_index_search: 'suppliers_index_search',
        page_index_autosearch: 'suppliers_index_autosearch',
        page_index_filtered: 'suppliers_index_filtered'
      }
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Suppliers')
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasRole('Super Admin')) {
        arr.push({
          label: 'Export',
          id: 'export',
          type: 'button',
          color: 'teal',
          icon: 'get_app',
          flat: true
        })
        arr.push({
          label: 'Import',
          id: 'import',
          link: '/suppliers/import',
          type: 'a',
          color: 'teal',
          icon: 'publish',
          flat: true
        })
      }
      if (this.$store.getters.hasPermissionTo('create_supplier')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/suppliers/create',
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
        url: 'suppliers/export',
        method: 'GET',
        responseType: 'blob' // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'suppliers.xlsx')
        document.body.appendChild(link)
        link.click()
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  }
}
</script>
