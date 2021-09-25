<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" />
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="product_bundles"
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
  name: 'ProductBundlesIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Product Bundles', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'product_bundles_index_visible_columns',
        page_index_pagination: 'product_bundles_index_pagination',
        page_index_search: 'product_bundles_index_search',
        page_index_autosearch: 'product_bundles_index_autosearch',
        page_index_filtered: 'product_bundles_index_filtered'
      }
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_product')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/product_bundles/create',
          type: 'a',
          color: 'teal',
          icon: 'add'
        })
      }
      return arr
    }
  },
  methods: {},
  mounted () {
    this.$store.commit('setPageTitle', 'Product Bundles')
  }
}
</script>
