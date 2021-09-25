<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" v-on:export="exportFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="products"
        ref="advancedDataTable"
        :page-preferences="pagePreferences"
        edit-permission="update_product"
        delete-permission="delete_product"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ProductIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'products_index_visible_columns',
        page_index_pagination: 'products_index_pagination',
        page_index_search: 'products_index_search',
        page_index_autosearch: 'products_index_autosearch',
        page_index_filtered: 'products_index_filtered'
      }
    }
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
          link: '/products/import',
          type: 'a',
          color: 'teal',
          icon: 'publish',
          flat: true
        })
      }
      if (this.$store.getters.hasPermissionTo('create_product')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/products/create',
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
        url: 'products/export',
        method: 'GET',
        responseType: 'blob' // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'products.xlsx')
        document.body.appendChild(link)
        link.click()
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  },
  mounted () {
    // Defined in store/index.js as window.Echo
    // eslint-disable-next-line no-undef
    /*
    Echo.channel('product-events')
      .listen('ProductOpened', (e) => {
        const child = this.$refs.advancedDataTable
        child.items.forEach((item, index) => {
          if (item.id === e.product.id) {
            child.items[index].oldStatus = child.items[index].status
            child.items[index].status = 'processing'
          }
        })
        // console.log(e.product)
      })
      */
    this.$store.commit('setPageTitle', 'Products')
  }
}
</script>
