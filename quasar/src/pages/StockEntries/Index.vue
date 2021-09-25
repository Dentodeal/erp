<template>
    <q-page>
        <page-toolbar :buttons="toolbarButtons"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
         <simple-data-table
            route="stock_entries"
            :page-preferences="{}"
            edit-permission="update_warehouse"
            delete-permission="delete_warehouse"
            :page-edit="true"
            ref="simpleDataTable"
            infinite-rows
        ></simple-data-table>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
import SimpleDataTable from 'components/SimpleDataTable.vue'

export default {
  name: 'StockEntriesIndex',
  components: {
    PageToolbar,
    Breadcrumbs,
    SimpleDataTable
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Stock Entries')
  },
  data () {
    return {
      edit_mode: false,
      edit_id: null,
      name: null,
      nameError: null,
      nameErrorMessage: '',
      createDialog: false,
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Stock Entries', link: '/stock_entries' }
      ]
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_warehouse')) {
        arr.push({
          label: 'Create',
          id: 'create',
          type: 'a',
          link: '/stock_entries/create',
          color: 'teal',
          icon: 'add'
        })
      }
      return arr
    }
  },
  methods: {}
}
</script>
