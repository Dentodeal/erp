<template>
  <div>
    <div :class="$q.screen.gt.sm ? 'q-px-lg': 'q-px-xs'">
      <q-table
        title="Users"
        :data="items"
        :columns="columns"
        :row-key="rowKey"
        :dense="$q.screen.lt.md"
        :visible-columns="visibleColumns"
        :loading="loading"
        :pagination.sync="pagination"
        @request="getDataFromApi"
        :rows-per-page-options="[10, 20, 50, 100]"
        >
        <template v-slot:loading>
          <q-inner-loading showing>
            <q-spinner-gears size="50px" color="primary" />
          </q-inner-loading>
        </template>
        <template v-slot:top="props">
          <div class="row" style="width:100%">
            <div class="col-xs-12 col-md-6">
              <q-input dense debounce="300" placeholder="Search here.." square v-model="search" clearable
              @keyup = "autoSearch ? debouncedSearch() : ''"
              @keyup.enter = "!autoSearch ? (search ? getDataFromApi({pagination:{ page: 1, rowsPerPage: pagination.rowsPerPage, sortBy: pagination.sortBy, descending: pagination.descending },filter:search}) : getDataFromApi({pagination:{ page: 1, rowsPerPage: pagination.rowsPerPage, sortBy: pagination.sortBy, descending: pagination.descending },filter:search,clear_search:1})) : ''"
              @clear="getDataFromApi({pagination:{ page: 1, rowsPerPage: pagination.rowsPerPage, sortBy: pagination.sortBy, descending: pagination.descending },filter:search,clear_search:1})">
                <template v-slot:append>
                  <q-icon name="search" />
                </template>
              </q-input>
            </div>
            <div class="col-xs-12 col-md-6" style="display:flex;justify-content:flex-end;">
              <q-btn flat round icon="refresh" color="green-10" @click="getDataFromApi({pagination:pagination,filter:search})"/>
              <q-toggle v-model="autoSearch" @input="changeAutoSearch" label="Auto Search ?"/>
              <q-btn v-if="!props.inFullscreen" flat round dense icon="filter_alt" @click="filterDrawer = !filterDrawer">
                <q-tooltip>
                  Filter
                </q-tooltip>
              </q-btn>
              <q-btn v-if="!props.inFullscreen" flat round dense icon="visibility" @click="visibilityDrawer = !visibilityDrawer">
                <q-tooltip>
                  Visible Columns
                </q-tooltip>
              </q-btn>
              <q-select class="q-mx-md" v-else :options="columns" multiple emit-value map-options v-model="visibleColumns" option-value="name" outlined dense options-dense display-value="Columns" @input="changeVisibleColumns"/>
              <q-btn
                flat round dense
                :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                @click="props.toggleFullscreen"
                class="q-ml-md"
                >
                <q-tooltip>
                  Fullscreen
                </q-tooltip>
              </q-btn>
            </div>
          </div>
          <div class="row q-mt-md">
            <div class="q-gutter-xs">
              <template v-for="(key,index) in Object.keys(filtered)">
                <q-chip v-if="filtered[key].constructor.toString().indexOf('Object') != -1"  :key="index+'a'" removable @remove="(state) =>{deleteFilter(key)}" color="primary" text-color="white">
                  {{filtered[key].min}} &lt; {{key}} > {{filtered[key].max}}
                </q-chip>
                <q-chip v-else-if="filtered[key].constructor.toString().indexOf('Array') != -1"  :key="index+'a'" removable @remove="(state) =>{deleteFilter(key)}" color="primary" text-color="white">
                  {{key}}: {{filtered[key].toString()}}
                </q-chip>
                <q-chip v-else :key="index+'b'" removable @remove="(state) =>{deleteFilter(key)}" color="primary" text-color="white">
                  {{key}}: {{filtered[key]}}
                </q-chip>
              </template>
            </div>
          </div>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn v-if="canEdit && (props.row.hasOwnProperty('status') ? props.row.status == 'Draft':true)"
              round icon="edit" flat
              color="black" :to="!pageEdit? (editRoute ? editRoute+'/edit/'+props.row.id : route+'/edit/'+props.row.id):undefined"
              @click="$emit('edit-fn',props.row)"/>
            <q-btn v-if="canDelete" round icon="delete" flat color="black" @click="deleteFn(props.row.id)"/>
            <q-btn v-if="cancel && (props.row.status == 'Draft' || props.row.status == 'Pending Confirmation')" round icon="close" flat color="black" @click="cancelFn(props.row.id)"/>
          </q-td>
        </template>
        <template v-slot:body-cell-status="props">
          <q-td key="status" :props="props">
            <q-badge :color="getStatusColor(props.row.status)" class="q-pa-sm">
              {{ props.row.status }}
            </q-badge>
          </q-td>
        </template>
        <template v-slot:body-cell-erp_status="props">
          <q-td key="erp_status" :props="props">
            <q-badge :color="getStatusColor(props.row.erp_status)" class="q-pa-sm">
              {{ props.row.erp_status }}
            </q-badge>
          </q-td>
        </template>
        <template v-slot:body-cell-payment_status="props">
          <q-td key="payment_status" :props="props">
            <q-badge :color="getStatusColor(props.row.payment_status)" class="q-pa-sm">
              {{ props.row.payment_status }}
            </q-badge>
          </q-td>
        </template>
        <template v-slot:body-cell-shipment_status="props">
          <q-td key="shipment_status" :props="props">
            <q-badge v-if="props.row.shipment_status" :color="getStatusColor(props.row.shipment_status)" class="q-pa-sm">
              {{ props.row.shipment_status }}
            </q-badge>
          </q-td>
        </template>
        <template v-slot:body-cell="props">
          <q-td :props="props" v-if="props.col.name == link_key" :class="props.col.align == 'right'?'text-right':'text-left'">
            <router-link :to="route+'/view/'+props.row.id" class="text-ble-9u" style="text-decoration:none; font-weight:700;">
              {{props.value}}
            </router-link>
            <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.value)"></q-btn>
          </q-td>
          <q-td v-else :class="props.col.align == 'right'?'text-right':'text-left'">
            <template v-if="props.col.field_type == 'datetime'">
              {{getLocaleString(props.value)}}
            </template>
            <template v-else-if="props.col.field_type == 'boolean'">
              {{props.value ? 'Yes' : 'No'}}
            </template>
            <template v-else>
              {{props.value}}
            </template>
          </q-td>
        </template>
      </q-table>
    </div>
    <q-drawer v-model="visibilityDrawer" side="right" :breakpoint="700" bordered overlay behavior="mobile">
      <q-list>
        <q-item-label header>Visible Columns</q-item-label>
        <template v-for="(item,j) in columns" >
          <q-item v-if="item.required != true" :key="j">
            <q-item-section><q-toggle v-model="visibleColumns" :val="item.field" :label="item.label" @input="changeVisibleColumns"/></q-item-section>
          </q-item>
        </template>
      </q-list>
    </q-drawer>
    <q-drawer v-model="filterDrawer" side="right" :breakpoint="700" bordered overlay behavior="mobile">
      <q-list>
        <q-item-label header>Filter Options</q-item-label>
        <q-item>
          <q-item-section>
            <q-btn label="Reset All" flat @click="resetFilter()"/>
            <q-btn label="Apply" color="primary" @click="applyFilter()"/>
          </q-item-section>
        </q-item>
        <template v-for="(attr,i) in filterables" >
          <q-item :key="i">
            <q-item-section>
              <q-select
                v-if="attr.field_type == 'Boolean'"
                :options="['','Yes','No']"
                v-model="filterables[i].value"
                square outlined dense
                :label="attr.name"/>
              <q-input
                v-if="attr.field_type == 'String'"
                v-model="filterables[i].value"
                square outlined dense
                :label="attr.name">
              </q-input>
              <div v-if="attr.field_type == 'Decimal' || attr.field_type == 'Integer'">
                <div class="subtitle1">{{attr.name}}</div>
                <q-range
                  v-model="filterables[i].value"
                  label
                  :min="filterables[i].min"
                  :max="filterables[i].max"
                />
              </div>
              <q-select
                v-if="attr.field_type == 'Selection' && attr.searcheable == true"
                v-model="filterables[i].value"
                @filter="(val,update,abort)=>{filterFn({val,update,abort},i)}"
                square outlined dense
                :options="filterables[i].options"
                input-debounce="300"
                multiple
                use-chips
                :label="attr.name">
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No results
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>
              <q-select
                v-if="attr.field_type == 'Selection' && attr.searcheable == false"
                v-model="filterables[i].value"
                :multiple="filterables[i].multiple != false"
                square outlined dense
                :options="filterables[i].options"
                :label="attr.name">
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No results
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>
            </q-item-section>
          </q-item>
        </template>
      </q-list>
    </q-drawer>
  </div>
</template>

<script>
export default {
  name: 'advancedDatatable',
  props: {
    rowKey: {
      type: String,
      default: 'name'
    },
    route: {
      type: String,
      required: true
    },
    pagePreferences: {
      type: Object,
      required: true
    },
    editPermission: {
      type: String,
      default: ''
    },
    deletePermission: {
      type: String,
      default: ''
    },
    editRoute: {
      type: String,
      default: null
    },
    deleteRoute: {
      type: String,
      default: null
    },
    pageEdit: {
      type: Boolean,
      default: false
    },
    updateOnVisibility: {
      type: Boolean,
      default: true
    },
    cancel: {
      type: Boolean,
      default: false
    },
    noDelete: {
      type: Boolean,
      default: false
    },
    cancelRoute: {
      type: String,
      default: null
    }
  },
  data () {
    return {
      search: null,
      autoSearch: false,
      filterDrawer: false,
      visibilityDrawer: false,
      items: [],
      visibleColumns: [],
      columns: [],
      loading: false,
      canEdit: false,
      canDelete: false,
      pagination: {
        rowsNumber: 10
      },
      link_key: null,
      filterables: [],

      filtered: {}
    }
  },
  watch: {},
  mounted () {
    this.initState()
  },
  methods: {
    changeVisibleColumns () {
      if (this.updateOnVisibility) { this.getDataFromApi({ pagination: this.pagination, filter: this.search }) }
    },
    changeAutoSearch () {
      const key = this.pagePreferences.page_index_autosearch
      const obj = {}
      obj[key] = this.autoSearch
      this.$axios.post('preferences', obj)
    },
    deleteFn (id) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        const route = this.deleteRoute ? this.deleteRoute + '/' + id : this.route + '/' + id
        this.$axios.post(route, {
          _method: 'DELETE'
        }).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              type: 'info',
              message: 'Record deleted from database'
            })
          }
        }).finally(() => {
          this.getDataFromApi({ pagination: this.pagination, filter: this.search })
        })
      }).onCancel(() => {

      })
    },
    cancelFn (id) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to cancel this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        const route = this.cancelRoute + '/' + id
        this.$axios.get(route).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              type: 'info',
              message: 'Record cancelled'
            })
          }
        }).finally(() => {
          this.getDataFromApi({ pagination: this.pagination, filter: this.search })
        })
      }).onCancel(() => {

      })
    },
    initState () {
      Promise.all([
        this.$axios.get('preferences?key=' + this.pagePreferences.page_index_autosearch).then((res) => {
          if (res.data) {
            this.autoSearch = res.data.value === '1'
          }
        }),
        this.$axios.get(this.route + '/columns').then((res) => {
          this.columns = res.data
          this.canEdit = this.$store.getters.hasPermissionTo(this.editPermission)
          this.canDelete = this.$store.getters.hasPermissionTo(this.deletePermission) && !this.noDelete
          if (this.canEdit || this.canDelete || this.cancel) {
            this.columns.push({
              name: 'actions',
              label: 'Actions',
              field: 'actions',
              required: true,
              sortable: false,
              align: 'right'
            })
          }
        }),
        this.$axios.get(this.route + '/filterables').then((res) => {
          const filterables = res.data
          filterables.forEach((item, i) => {
            if (item.field_type === 'Selection' && item.searcheable) {
              filterables[i].options = []
            }
            if (item.field_type === 'Decimal' || item.field_type === 'Integer') {
              filterables[i].value = {
                min: parseInt(item.min),
                max: parseInt(item.max)
              }
            } else { filterables[i].value = null }
          })
          this.$nextTick(() => {
            this.filterables = filterables
          })
        }).catch((error) => {
          const response = error.response
          console.log(response)
        })
      ]).finally(() => {
        this.debouncedSearch = this.$_.debounce(() => {
          const pagination = { page: 1, rowsPerPage: this.pagination.rowsPerPage, sortBy: this.pagination.sortBy, descending: this.pagination.descending }
          if (this.search) this.getDataFromApi({ pagination: pagination, filter: this.search })
          else this.getDataFromApi({ pagination: pagination, filter: this.search, clear_search: 1 }, true)
        }, 1000)
        if (this.$route.query.status) {
          const obj = { status: [this.$route.query.status] }
          if (this.$route.query.source) {
            obj.source = [this.$route.query.source]
          }
          this.getDataFromApi({ pagination: this.pagination, filter: this.search, filterBy: obj }, true)
        } else if (this.$route.query.source) {
          const obj = { source: [this.$route.query.source] }
          this.getDataFromApi({ pagination: this.pagination, filter: this.search, filterBy: obj }, true)
        } else {
          this.getDataFromApi({ pagination: this.pagination, filter: this.search }, true)
        }
      })
    },
    triggerExternal () {
      this.getDataFromApi({ pagination: this.pagination, filter: this.search })
    },
    getDataFromApi (props, init = false) {
      this.loading = true
      // console.log(filterablesQ)
      const { page, rowsPerPage, sortBy, descending } = props.pagination
      // console.log(props.pagination)
      const search = props.filter ? props.filter : (this.search ? this.search : '')
      let query = ''
      if (this.route.indexOf('?') !== -1) {
        query += this.route + '&'
      } else {
        query += this.route + '?'
      }
      if (!init) {
        query += 'page=' + page +
                      '&rpp=' + rowsPerPage +
                      '&sortby=' + (sortBy || '') +
                      '&desc=' + descending +
                      '&search=' + encodeURIComponent(search) +
                      '&visible=' + encodeURIComponent(JSON.stringify(this.visibleColumns))
        if (props.filterBy) {
          query += '&filter=' + encodeURIComponent(JSON.stringify(props.filterBy))
        } else {
          query += '&filter=' + encodeURIComponent(JSON.stringify(this.filtered))
        }
        if (props.filter_deleted) {
          query += '&filter_deleted=1'
        }
        if (props.clear_search) {
          query += '&clear_search=1'
        }
      } else {
        if (props.filterBy) {
          query += '&filter=' + encodeURIComponent(JSON.stringify(props.filterBy))
        }
      }
      this.$axios.get(query).then((res) => {
        this.items = res.data.model.data
        this.pagination.rowsNumber = res.data.model.total
        this.pagination.page = res.data.model.current_page
        this.pagination.rowsPerPage = res.data.model.per_page
        this.pagination.sortBy = res.data.sortby
        this.pagination.descending = res.data.descending
        this.link_key = res.data.link_key
        this.visibleColumns = res.data.visible_columns
        this.filtered = res.data.filtered
        this.search = res.data.search ? decodeURIComponent(res.data.search) : ''
      }).finally(() => {
        this.loading = false
      })
    },
    applyFilter () {
      this.filtered = {}
      this.filterables.forEach((item, i) => {
        if (item.field_type === 'Decimal' || item.field_type === 'Integer') {
          if (item.value.max !== item.max || item.value.min !== item.min) {
            this.filtered[item.slug] = item.value
          }
        } else {
          if (item.value) {
            if (item.value.constructor.toString().indexOf('Array') !== -1) {
              // console.log(item.value.length)
              if (item.value.length > 0) { this.filtered[item.slug] = item.value } else {
                // remove filter if the array is empty
                this.$delete(this.filtered, item.slug)
              }
            } else {
              // console.log(item.value)
              this.filtered[item.slug] = item.value
            }
          } else {
            // remove the filter key if the input filed is empty by backspace
            this.$delete(this.filtered, item.slug)
          }
        }
      })
      this.getDataFromApi({ pagination: this.pagination, filter: this.search })
    },
    deleteFilter (key) {
      this.$delete(this.filtered, key)
      this.filterables.forEach((item, i) => {
        if (item.slug === key) {
          if (item.field_type === 'Decimal' || item.field_type === 'Integer') {
            this.filterables[i].value.max = parseInt(item.max)
            this.filterables[i].value.min = parseInt(item.min)
          } else {
            this.filterables[i].value = null
          }
        }
      })
      this.getDataFromApi({ pagination: this.pagination, filter: this.search, filter_deleted: 1 })
    },
    resetFilter () {
      this.filterables.forEach((item, i) => {
        if (item.field_type === 'Decimal' || item.field_type === 'Integer') {
          this.filterables[i].value.max = parseInt(item.max)
          this.filterables[i].value.min = parseInt(item.min)
        } else {
          this.filterables[i].value = null
        }
      })
      this.applyFilter()
    },
    filterFn ({ val, update, abort }, i) {
      update(() => {
        const needle = val.toLowerCase()
        if (needle && needle.length > 0) {
          this.$axios.get(this.filterables[i].search_link + '?search=' + encodeURIComponent(needle)).then((res) => {
          // this.$set(this.filterables[i].options, res.data)
            this.filterables[i].options = res.data
          // eslint-disable-next-line node/handle-callback-err
          }).catch((error) => {
          // const response = error.response
          // console.log(response)
          })
        }
      })
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return null
    },
    getStatusColor (status) {
      if (status === 'Draft' || status === 'Disabled' || status === 'Confirmed' || status === 'Draft - Reverted') {
        return 'grey-9'
      } else if (status === 'Pending Approval' || status === 'Rejected' || status === 'Pending Activation' || status === 'Pending Confirmation' || status === 'Cancelled' || status === 'pending') {
        return 'red-10'
      } else if (status === 'Processing' || status === 'Awaiting Shipment' || status === 'Packed' || status === 'Partially Converted' | status === 'processing') {
        return 'orange-9'
      } else if (status === 'Invoice Pending') {
        return 'blue-10'
      } else if (status === 'Admin Approved' || status === 'Approved') {
        return 'blue'
      } else if (status === 'Invoiced' || status === 'Generated') {
        return 'info'
      } else if (status === 'Active' || status === 'Complete' || status === 'Converted') {
        return 'green-10'
      }
    }
  }
}
</script>
