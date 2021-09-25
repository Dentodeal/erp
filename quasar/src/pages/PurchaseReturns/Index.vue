<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="purchase_returns"
        :page-preferences="pagePreferences"
        edit-permission="update_purchase"
        delete-permission="delete_purchase"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'PurchaseReturnsIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Purchase Returns')
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Purchases', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'purchase_returns_index_visible_columns',
        page_index_pagination: 'purchase_returns_index_pagination',
        page_index_search: 'purchase_returns_index_search',
        page_index_autosearch: 'purchase_returns_index_autosearch',
        page_index_filtered: 'purchase_returns_index_filtered'
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
