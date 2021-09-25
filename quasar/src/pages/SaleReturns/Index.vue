<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="sale_returns"
        :page-preferences="pagePreferences"
        edit-permission="update_sale_order"
        delete-permission="delete_sale_order"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SaleReturnsIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Sale Returns')
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'sale_returns_index_visible_columns',
        page_index_pagination: 'sale_returns_index_pagination',
        page_index_search: 'sale_returns_index_search',
        page_index_autosearch: 'sale_returns_index_autosearch',
        page_index_filtered: 'sale_returns_index_filtered'
      }
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      return arr
    }
  },
  methods: {
  }
}
</script>
