<template>
    <q-page>
        <page-toolbar
        :buttons="toolbarButtons"
        />
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <simple-data-table
        route="user_roles"
        :page-preferences="pagePreferences"
        edit-permission="update_user_role"
        delete-permission="delete_user_role"
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
    // Check for permission
    if (!this.$store.getters.hasPermissionTo('viewAny_user_role')) {
      this.$router.push({ name: 'Error403' })
      this.$store.commit('setPageTitle', 'User Roles')
    }
  },
  data () {
    return {
      toolbarButtons: [
        {
          label: 'Create',
          id: 'create',
          type: 'a',
          link: '/user_roles/create',
          color: 'teal'
        }
      ],
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'User Roles', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_visible_columns: 'user_roles_index_visible_columns',
        page_index_pagination: 'user_roles_index_pagination'
      }
    }
  }
}
</script>
