<template>
    <q-page>
        <page-toolbar :buttons="toolbarButtons" v-on:create="createFn"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
         <simple-data-table
            route="logistic_partners"
            :page-preferences="pagePreferences"
            edit-permission="update_logistic_partner"
            delete-permission="delete_logistic_partner"
            :page-edit="true"
            ref="simpleDataTable"
            v-on:edit-fn="editFn"
        ></simple-data-table>
        <q-dialog v-model="createDialog" persistent >
            <q-card style="width: 700px; max-width: 80vw;">
                <q-card-section class="row items-center q-pb-none">
                    <div v-if="edit_mode">Edit logistic partner: {{name}}</div>
                    <div v-else>Create new logistic partner</div>
                    <q-space />
                    <q-btn icon="close" flat round dense @click="closeCreateDialog()" />
                </q-card-section>
                <q-card-section>
                    <div class="row">
                        <div class="col">
                            <q-input
                                v-model="name" ref="name"
                                label="Name" outlined square
                                :error="nameError" :error-message="nameErrorMessage"
                                @blur="name = capitaliseFn(name)">
                            </q-input>
                        </div>
                    </div>
                    <div class="row q-mt-md">
                        <div class="col">
                            <q-input
                                type="textarea"
                                v-model="address" ref="address"
                                label="Address" outlined square
                                @blur="name = capitaliseFn(name)">
                            </q-input>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md q-mt-md" v-for="(contact,i) in contacts" :key="i">
                        <div class="col">
                            <q-input
                                v-model="contacts[i].name"
                                label="Contact Person" outlined square dense
                                @blur="name = capitaliseFn(name)">
                            </q-input>
                        </div>
                        <div class="col">
                            <q-input

                                v-model="contacts[i].phone"
                                label="Phone" outlined square  dense
                                @blur="name = capitaliseFn(name)">
                            </q-input>
                        </div>
                        <div class="col">
                            <q-input
                                v-model="contacts[i].designation"
                                label="Designation" outlined square  dense
                                @blur="name = capitaliseFn(name)">
                            </q-input>
                        </div>
                        <div>
                            <q-btn color="grey-7" round icon="delete" flat @click="contacts.splice(i,1)"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <q-btn label="Add Contact Person" color="primary" @click="contacts.push({name:'',phone:'',designation:''})"/>
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
  name: 'PricelistsIndex',
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
      address: '',
      contacts: [{
        name: '', phone: '', designation: ''
      }],
      createDialog: false,
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Pricelists', link: '/pricelists' }
      ],
      pagePreferences: {
        page_index_visible_columns: 'logistic_partners_index_visible_columns',
        page_index_pagination: 'logistic_partners_index_pagination'
      }
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Logistic Partners')
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_logistic_partner')) {
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
      this.name = null
      this.nameError = false
      this.nameErrorMessage = ''
      this.address = ''
      this.contacts = [{
        name: '', phone: '', designation: ''
      }]
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
      this.address = row.address
      this.contacts = row.contacts
      this.edit_mode = true
      this.createDialog = true
      this.$nextTick(() => {
        this.$refs.name.focus()
        this.$refs.name.select()
      })
    },
    closeCreateDialog () {
      this.nameError = false
      this.nameErrorMessage = ''
      this.name = null
      this.address = ''
      this.contacts = [{
        name: '', phone: '', designation: ''
      }]
      this.edit_id = null
      this.edit_mode = false
      this.createDialog = false
    },
    saveFn () {
      let route = 'logistic_partners'
      if (this.edit_mode) {
        route = 'logistic_partners/' + this.edit_id
      }
      const obj = {
        name: this.capitaliseFn(this.name),
        _method: this.edit_mode ? 'PUT' : 'POST',
        address: this.address,
        contacts: this.contacts
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
          this.nameErrorMessage = error.response.data.errors.name[0]
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
