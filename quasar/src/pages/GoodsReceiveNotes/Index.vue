<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        :update-on-visibility="false"
        route="goods_receive_notes"
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
  name: 'GoodsReceiveNotesIndex',
  components: {
    PageToolbar,
    AdvancedDataTable,
    Breadcrumbs
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Goods Receive Notes')
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Goods Receive Notes', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_autosearch: 'goods_receive_notes_index_autosearch'
      }
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_purchase')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/goods_receive_notes/create',
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
