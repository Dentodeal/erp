<template>
    <div>
        <div :class="$q.screen.gt.sm ? 'q-px-lg': 'q-px-xs'">
            <q-card>
                <q-card-section>
                    <q-table
                    title="Users"
                    :data="items"
                    :columns="columns"
                    :row-key="rowKey"
                    :dense="$q.screen.lt.md"
                    :visible-columns="visibleColumns"
                    :filter="search"
                    :loading="loading"
                    :rows-per-page-options="infiniteRows ? [0] : [25,50,100]"
                    :pagination.sync="pagination"
                    >
                    <template v-slot:loading>
                        <q-inner-loading showing>
                            <q-spinner-gears size="50px" color="primary" />
                        </q-inner-loading>
                    </template>
                    <template v-slot:top="props">
                        <div class="col">
                            <q-input dense debounce="300" placeholder="Search here.." square v-model="search" clearable>
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                            </q-input>
                        </div>
                        <q-space/>
                        <q-btn flat round dense icon="visibility" @click="filterDrawer = !filterDrawer"/>
                        <q-btn
                        flat round dense
                        :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                        @click="props.toggleFullscreen"
                        class="q-ml-md"
                        />
                    </template>
                    <template v-slot:body-cell-actions="props">
                        <q-td :props="props">
                            <q-btn v-if="canEdit && (props.row.hasOwnProperty('status') ? props.row.status == 'draft':true)"
                                round icon="edit" flat color="black"
                                :to="!pageEdit ? editRoute ? editRoute+'/edit/'+props.row.id : route+'/edit/'+props.row.id:undefined"
                                @click="$emit('edit-fn',props.row)"/>
                            <q-btn v-if="canDelete" round icon="delete" flat color="black" @click="deleteFn(props.row.id)"/>
                        </q-td>
                    </template>
                    <template v-slot:body-cell="props">
                      <q-td :props="props" v-if="props.col.name == link_key" :class="props.col.align == 'right'?'text-right':'text-left'">
                        <router-link :to="route+'/view/'+props.row.id" class="text-ble-9u" style="text-decoration:none; font-weight:700;">
                          {{props.value}}
                        </router-link>
                        <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.name)"></q-btn>
                      </q-td>
                      <q-td v-else :class="props.col.align == 'right'?'text-right':'text-left'">
                        <template v-if="props.col.field_type == 'datetime'">
                            {{getLocaleString(props.value)}}
                        </template>
                        <template v-else>
                            {{props.value}}
                        </template>
                      </q-td>
                    </template>
                </q-table>
            </q-card-section>
        </q-card>
    </div>
    <q-drawer v-model="filterDrawer" side="right" :breakpoint="700"
    bordered overlay behavior="mobile">
    <q-list>
        <q-item-label header>Visible Columns</q-item-label>
        <template v-for="(item,i) in columns">
            <q-item v-if="item.required != true" :key="i">
                <q-item-section><q-toggle v-model="visibleColumns" :val="item.field" :label="item.label" @input="changeVisibleColumns"/></q-item-section>
            </q-item>
        </template>
    </q-list>
</q-drawer>
</div>
</template>

<script>
export default {
  // name: 'ComponentName',
  props: {
    infiniteRows: {
      type: Boolean,
      default: false
    },
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
    pageEdit: {
      type: Boolean,
      default: false
    },
    editRoute: {
      type: String,
      default: null
    },
    deleteRoute: {
      type: String,
      default: null
    }
  },
  data () {
    return {
      link_key: null,
      search: null,
      filterDrawer: false,
      items: [],
      visibleColumns: [],
      columns: [],
      loading: false,
      canEdit: false,
      canDelete: false,
      pagination: null
    }
  },
  watch: {
    pagination: {
      handler (newV, oldV) {
        if (oldV != null) {
          // const { page, rowsPerPage, sortBy, descending } = this.pagination
          // if(page==1 && )
          // console.log(this.pagination)
          /*
          const key = this.pagePreferences.page_index_pagination
          const obj = {}
          obj[key] = JSON.stringify(this.pagination)
          this.$axios.post('preferences', obj)
          */
        }
      },
      deep: true
    }
  },
  mounted () {
    this.getDataFromApi()
  },
  methods: {
    changeVisibleColumns () {
      /*
      // console.log(this.pagePreferences.page_index_visible_columns)
      if (this.pagePreferences.page_index_visible_columns) {
        const key = this.pagePreferences.page_index_visible_columns
        const obj = {}
        obj[key] = JSON.stringify(this.visibleColumns)
        this.$axios.post('preferences', obj)
      }
      */
    },
    deleteFn (id) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$q.loading.show()
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
          this.$q.loading.hide()
          this.getDataFromApi()
        })
      }).onCancel(() => {

      })
    },
    getDataFromApi () {
      this.loading = true
      this.$axios.get(this.route).then((res) => {
        this.items = res.data.model
        this.visibleColumns = res.data.visible_columns
        if (res.data.pagination) {
          this.pagination = res.data.pagination
        }
        this.columns = res.data.columns
        this.canEdit = this.$store.getters.hasPermissionTo(this.editPermission)
        this.canDelete = this.$store.getters.hasPermissionTo(this.deletePermission)
        if (this.canEdit || this.canDelete) {
          this.columns.push({
            name: 'actions',
            label: 'Actions',
            field: 'actions',
            required: true,
            sortable: false,
            align: 'right'
          })
        }
        this.link_key = res.data.link_key
      }).finally(() => {
        this.loading = false
      })
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return null
    }
  }
}
</script>
