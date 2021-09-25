<template>
    <q-page>
        <page-toolbar :buttons="toolbarButtons" v-on:create="createFn"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
         <simple-data-table
            route="quotation_templates"
            :page-preferences="pagePreferences"
            edit-permission="update_quotation"
            delete-permission="delete_quotation"
            :page-edit="false"
            ref="simpleDataTable"
            v-on:edit-fn="editFn"
        ></simple-data-table>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
import SimpleDataTable from 'components/SimpleDataTable.vue'

export default {
  name: 'QuotationTemplatesIndex',
  components: {
    PageToolbar,
    Breadcrumbs,
    SimpleDataTable
  },
  data () {
    return {
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Quotation Templates', link: '/quotation_templates' }
      ],
      pagePreferences: {
        page_index_visible_columns: null,
        page_index_pagination: null
      }
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Quotation Templates')
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_quotation')) {
        arr.push({
          label: 'Create',
          id: 'create',
          type: 'a',
          link: '/quotation_templates/create',
          color: 'teal',
          icon: 'add'
        })
      }
      return arr
    }
  },
  methods: {
    createFn () {
      this.resetFn()
      this.edit_mode = false
      this.createDialog = true
      this.$nextTick(() => {
        this.$refs.name.focus()
      })
    },
    editFn (row) {
    },
    refreshFn () {
      const child = this.$refs.simpleDataTable
      child.getDataFromApi()
    }
  }
}
</script>
