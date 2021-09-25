<template>
    <q-page>
        <page-toolbar page-title="Phones" :buttons="toolbarButtons"
            v-on:create="createFn"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
         <advanced-data-table
            v-on:edit-fn="editFn"
            server-sync
            route="phones"
            :page-preferences="pagePreferences"
            ref="advancedDataTable"
            edit-permission="update_phone"
            delete-permission="delete_phone"
            :page-edit="true"
        ></advanced-data-table>
        <q-dialog v-model="createDialog" persistent >
            <q-card style="width: 700px; max-width: 80vw;">
                <q-card-section class="row items-center q-pb-none">
                    <div v-if="edit_mode">Edit phone: {{phone}}</div>
                    <div v-else>Create new phone</div>
                    <q-space />
                    <q-btn icon="close" flat round dense @click="closeCreateDialog()" />
                </q-card-section>
                <q-card-section>
                    <div class="row q-mt-sm">
                        <div class="col-2">
                            <q-select outlined square v-model="country_code"
                                :options="countryCodeOptions"
                                label="Code"
                                emit-value
                                map-options/>
                        </div>
                        <div class="col-10">
                            <q-input
                                v-model="phone" ref="phone"
                                label="Phone" outlined square
                                :rules="[v=> !!v||'Required',v=>Number.isInteger(Number(v)) || 'Invalid']"
                                :error="phoneError" :error-message="phoneErrorMessage"
                                >
                            </q-input>
                        </div>
                    </div>
                    <div class="row q-mt-xs">
                        <div class="col">
                            <q-input outlined square v-model="source" label="Source"/>
                        </div>
                    </div>
                    <div class="row q-mt-md">
                        <div class="col">
                             <q-select
                                square outlined
                                v-model="tags" label="Tags"
                                :options="tagOptions"
                                use-input
                                use-chips
                                multiple
                                input-debounce="0"
                                @new-value="newTag"
                                @filter="tagFilterFn"/>
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
import AdvancedDataTable from 'components/AdvancedDataTable.vue'

export default {
  name: 'PhonesIndex',
  components: {
    PageToolbar,
    Breadcrumbs,
    AdvancedDataTable
  },
  data () {
    return {
      edit_mode: false,
      edit_id: null,
      phone: null,
      phoneError: null,
      phoneErrorMessage: '',
      country_code: '+91',
      tags: [],
      tagOptions: [],
      source: null,
      createDialog: false,
      breadcrumbs: [
        { label: 'Dashboard', link: '/' },
        { label: 'Phones', link: '/phones' }
      ],
      pagePreferences: {
        page_index_visible_columns: 'phones_index_visible_columns',
        page_index_pagination: 'phones_index_pagination',
        page_index_search: 'phones_index_search',
        page_index_autosearch: 'phones_index_autosearch',
        page_index_filtered: 'phones_index_filtered'
      },
      countryCodeOptions: []
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Phones')
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_phone')) {
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
      if (this.countryCodeOptions.length === 0) {
        this.$axios.get('phonecodes').then((res) => {
          this.countryCodeOptions = res.data
        })
      }
      this.resetForm()
      this.edit_mode = false
      this.createDialog = true
      this.$nextTick(() => {
        this.$refs.phone.focus()
      })
    },
    editFn (row) {
      if (this.countryCodeOptions.length === 0) {
        this.$axios.get('phonecodes').then((res) => {
          this.countryCodeOptions = res.data
        })
      }
      this.resetForm()
      this.edit_id = row.id
      this.phone = row.content
      this.source = row.source
      this.tags = row.tags.split(',')
      this.edit_mode = true
      this.createDialog = true
      this.$nextTick(() => {
        this.$refs.phone.focus()
        this.$refs.phone.select()
      })
    },
    closeCreateDialog () {
      this.resetForm()
      this.edit_id = null
      this.edit_mode = false
      this.createDialog = false
    },
    resetForm () {
      this.phone = null
      this.phoneError = false
      this.phoneErrorMessage = ''
      this.country_code = '+91'
      this.tags = []
      this.tagOptions = []
      this.source = null
    },
    saveFn () {
      if (this.$refs.phone.validate()) {
        this.phoneError = false
        this.phoneErrorMessage = ''
        let route = 'phones'
        if (this.edit_mode) {
          route = 'phones/' + this.edit_id
        }
        const obj = {
          content: this.phone,
          country_code: this.country_code,
          _method: this.edit_mode ? 'PUT' : 'POST',
          tags: this.tags,
          source: this.source
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
            this.phoneError = true
            this.phoneErrorMessage = error.response.data.errors.content[0]
          }
        })
      }
    },
    refreshFn () {
      const child = this.$refs.advancedDataTable
      child.getDataFromApi({ pagination: child.pagination, filter: child.search })
    },
    tagFilterFn (val, update, abort, i) {
      update(() => {
        if (val) {
          const str = ''
          this.$axios.get('tag_search?' + str + '&search=' + encodeURIComponent(val)).then((res) => {
            this.tagOptions = res.data
          })
        }
      })
    },
    newTag (val, done) {
      if (val.length > 0) {
        const model = (this.tags || []).slice()

        val
          .split(/[,;|]+/)
          .map(v => v.trim())
          .filter(v => v.length > 0)
          .forEach(v => {
            if (model.includes(v) === false) {
              model.push(this.capitaliseFn(v))
            }
          })

        done(null)
        this.tags = model
      }
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
