<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" v-on:print="printFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        :update-on-visibility="false"
        route="purchases"
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
  name: 'PurchasesIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Purchases')
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Purchases', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'purchases_index_visible_columns',
        page_index_pagination: 'purchases_index_pagination',
        page_index_search: 'purchases_index_search',
        page_index_autosearch: 'purchases_index_autosearch',
        page_index_filtered: 'purchases_index_filtered'
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
    printFn () {}
  }
}
</script>
