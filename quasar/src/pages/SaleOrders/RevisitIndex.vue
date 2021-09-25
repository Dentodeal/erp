<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="sale_order_revisits"
        :page-preferences="pagePreferences"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SaleOrdersRevisitIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Sale Orders for revisit')
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Order Revisits', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'sale_orders_revisit_index_visible_columns',
        page_index_pagination: 'sale_orders_revisit_index_pagination',
        page_index_search: 'sale_orders_revisit_index_search',
        page_index_autosearch: 'sale_orders_revisit_index_autosearch',
        page_index_filtered: 'sale_orders_revisit_index_filtered'
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
