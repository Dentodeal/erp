<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="quotations"
        :page-preferences="pagePreferences"
        edit-permission="update_quotation"
        delete-permission="delete_quotation"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SaleOrderIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Quotations')
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Quotations', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'quotations_index_visible_columns',
        page_index_pagination: 'quotations_index_pagination',
        page_index_search: 'quotations_index_search',
        page_index_autosearch: 'quotations_index_autosearch',
        page_index_filtered: 'quotations_index_filtered'
      }
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_quotation')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/quotations/create',
          type: 'a',
          color: 'teal',
          icon: 'add'
        })
      }
      return arr
    }
  },
  methods: {
    printFn () {}
  }
}
</script>
