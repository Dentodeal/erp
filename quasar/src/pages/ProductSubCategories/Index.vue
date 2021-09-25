<template>
    <q-page>
        <page-toolbar page-title="Product Sub Categories" :buttons="toolbarButtons" v-on:create="createFn"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
         <simple-data-table
            route="product_sub_categories"
            :page-preferences="pagePreferences"
            edit-permission="update_product"
            delete-permission="delete_product"
            :page-edit="true"
            ref="simpleDataTable"
            v-on:edit-fn="editFn"
        ></simple-data-table>
        <q-dialog v-model="createDialog" persistent >
            <q-card style="width: 700px; max-width: 80vw;">
                <q-card-section class="row items-center q-pb-none">
                    <div v-if="edit_mode">Edit product sub category: {{name}}</div>
                    <div v-else>Create new product sub category</div>
                    <q-space />
                    <q-btn icon="close" flat round dense @click="closeCreateDialog()" />
                </q-card-section>
                <q-card-section>
                    <div class="row">
                        <div class="col">
                            <q-input
                                v-model="name" ref="name"
                                label="Name" outlined square
                                v-on:keyup.enter="saveFn()"
                                :error="nameError" :error-message="nameErrorMessage"
                                @blur="name = capitaliseFn(name)">
                            </q-input>
                            <q-input
                                v-model="code" ref="code"
                                label="Code" outlined square
                                v-on:keyup.enter="saveFn()"
                                :error="codeError" :error-message="codeErrorMessage"
                                @blur="code = code.toUpperCase()">
                            </q-input>
                        </div>
                    </div>
                </q-card-section>
                <q-card-actions>
                    <q-btn label="Save" color="green-10" icon="save" @click="saveFn()"/>
                    <q-btn label="cancel" color="secondary" flat @click="closeCreateDialog()"/>
                </q-card-actions>
            </q-card>
        </q-dialog>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
import SimpleDataTable from 'components/SimpleDataTable.vue'

export default {
  name: 'ProductSubCategoriesIndex',
  components: {
    PageToolbar,
    Breadcrumbs,
    SimpleDataTable
  },
  data () {
    return {
      edit_mode: false,
      edit_id: null,
      name: null,
      nameError: null,
      nameErrorMessage: '',
      code: null,
      codeError: null,
      codeErrorMessage: '',
      createDialog: false,
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Product Departments', link: '/product_sub_categories' }
      ],
      pagePreferences: {
        page_index_visible_columns: 'product_sub_categories_index_visible_columns',
        page_index_pagination: 'product_sub_categories_index_pagination'
      }
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Product Sub Categories')
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_product')) {
        arr.push({
          label: 'Create',
          id: 'create',
          type: 'button',
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
      this.edit_id = row.id
      this.name = row.name
      this.nameError = false
      this.nameErrorMessage = ''
      this.code = row.code
      this.codeError = false
      this.codeErrorMessage = ''
      this.edit_mode = true
      this.createDialog = true
      this.$nextTick(() => {
        this.$refs.name.focus()
        this.$refs.name.select()
      })
    },
    closeCreateDialog () {
      this.resetFn()
      this.edit_id = null
      this.edit_mode = false
      this.createDialog = false
    },
    resetFn () {
      this.name = null
      this.nameError = false
      this.nameErrorMessage = ''
      this.code = null
      this.codeError = false
      this.codeErrorMessage = ''
    },
    saveFn () {
      let route = 'product_sub_categories'
      if (this.edit_mode) {
        route = 'product_sub_categories/' + this.edit_id
      }
      const obj = {
        name: this.capitaliseFn(this.name),
        code: this.code.toUpperCase(),
        _method: this.edit_mode ? 'PUT' : 'POST'
      }
      this.$q.loading.show()
      this.$axios.post(route, obj).then((res) => {
        if (res.data.message === 'success') {
          this.$q.loading.hide()
          this.$q.notify({
            type: 'positive',
            message: 'Data Saved Successfully'
          })
          this.closeCreateDialog()
          this.refreshFn()
        }
      }).catch((error) => {
        this.$q.loading.hide()
        if (error.response.status === 422) {
          this.$q.notify({
            type: 'negative',
            message: error.response.data.message
          })
          this.nameError = true
          if (error.response.data.errors.name) {
            this.nameError = true
            this.nameErrorMessage = error.response.data.errors.name[0]
          }
          if (error.response.data.errors.code) {
            this.codeError = true
            this.codeErrorMessage = error.response.data.errors.code[0]
          }
        }
      })
    },
    refreshFn () {
      const child = this.$refs.simpleDataTable
      child.getDataFromApi()
    },
    capitaliseFn (str) {
      if (str) {
        const splitStr = str.split(' ')
        for (let i = 0; i < splitStr.length; i++) {
          splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1)
        }
        return splitStr.join(' ')
      }
      return ''
    }
  }
}
</script>
