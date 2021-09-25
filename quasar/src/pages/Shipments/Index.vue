<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="shipments"
        :page-preferences="pagePreferences"
        edit-permission="update_shipment"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ShipmentsIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Shipments', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'shipments_index_visible_columns',
        page_index_pagination: 'shipments_index_pagination',
        page_index_search: 'shipments_index_search',
        page_index_autosearch: 'shipments_index_autosearch',
        page_index_filtered: 'shipments_index_filtered'
      }
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Shipments')
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
