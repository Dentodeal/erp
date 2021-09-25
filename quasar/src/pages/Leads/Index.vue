<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="leads"
        :page-preferences="pagePreferences"
        edit-permission="update_lead"
        delete-permission="delete_lead"
    ></advanced-data-table>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import AdvancedDataTable from 'components/AdvancedDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'LeadsIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Leads', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'leads_index_visible_columns',
        page_index_pagination: 'leads_index_pagination',
        page_index_search: 'leads_index_search',
        page_index_autosearch: 'leads_index_autosearch',
        page_index_filtered: 'leads_index_filtered'
      }
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Leads')
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_lead')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/leads/create',
          type: 'a',
          color: 'teal',
          icon: 'add'
        })
      }
      return arr
    }
  }
}
</script>
