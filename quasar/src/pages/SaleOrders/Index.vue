<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" v-on:print="printFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <advanced-data-table
        server-sync
        route="sale_orders"
        :page-preferences="pagePreferences"
        edit-permission="update_sale_order"
        delete-permission="delete_sale_order"
        cancel
        no-delete
        cancel-route="sale_orders/cancel"
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
    this.$store.commit('setPageTitle', 'Sale Orders')
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders', link: '', disabled: true }
      ],
      pagePreferences: {
        page_index_autosearch: 'sale_orders_index_autosearch'
      }
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_sale_order')) {
        arr.push({
          label: 'Create',
          id: 'create',
          link: '/sale_orders/create',
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
