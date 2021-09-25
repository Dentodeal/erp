<template>
    <q-page>
        <page-toolbar page-title="Users" :buttons="toolbarButtons"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <simple-data-table
        route="users"
        :page-preferences="pagePreferences"
        edit-permission="update_user"
        delete-permission="delete_user"
        ></simple-data-table>
    </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import SimpleDataTable from 'components/SimpleDataTable.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  components: {
    Breadcrumbs,
    PageToolbar,
    SimpleDataTable
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Users')
  },
  data () {
    return {
      toolbarButtons: [
        {
          label: 'Create',
          id: 'create',
          type: 'a',
          link: '/users/create',
          color: 'teal'
        }
      ],
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Users', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'users_index_visible_columns',
        page_index_pagination: 'users_index_pagination'
      }
    }
  }
}
</script>
