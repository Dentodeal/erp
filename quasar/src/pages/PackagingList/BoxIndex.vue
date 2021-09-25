<template>
    <q-page>
      <page-toolbar :buttons="toolbarButtons"/>
      <breadcrumbs :breadcrumbs="breadcrumbs"/>
      <q-card>
        <q-card-section>
          <q-table
            title="Items"
            :data="items"
            :columns="columns"
            :dense="$q.screen.lt.md"
            :filter="search"
            :loading="loading"
            :rows-per-page-options="[25,50,100]"
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
                <q-btn
                flat round dense
                :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                @click="props.toggleFullscreen"
                class="q-ml-md"
                />
            </template>
            <template v-slot:body-cell-box="props">
              <q-td>
                {{$_.map(props.row.product.boxes, 'name').join()}}
              </q-td>
            </template>
            <template v-slot:body-cell-created_at="props">
              <q-td class="text-right">
                {{getLocaleString(props.row.created_at)}}
              </q-td>
            </template>
            <template v-slot:body-cell-action="props">
              <q-td class="text-right">
                <q-btn round flat icon="download" @click="download(props.rowIndex, props.row.id)" color="grey-9"/>
                <q-btn round flat icon="edit" :to="'/packaging/' + $route.params.so_id + '/edit/' + props.row.id"/>
                <q-btn round flat icon="delete" @click="deleteFn(props.row.id)" color="grey-9"/>
              </q-td>
            </template>
          </q-table>
        </q-card-section>
      </q-card>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'BoxIndex',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      sale_order: {
        serial_no: null
      },
      items: [],
      columns: [
        {
          name: 'name',
          field: 'name',
          label: 'Box Name',
          sortable: true,
          align: 'left'
        },
        {
          name: 'created_at',
          field: 'created_at',
          label: 'Created At',
          sortable: false,
          align: 'right'
        },
        {
          name: 'action',
          field: 'action',
          label: '',
          sortable: false,
          align: 'right'
        }
      ],
      search: null,
      loading: false
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Packaging')
    this.$axios.get('sale_orders/' + this.$route.params.so_id).then((res) => {
      this.sale_order = res.data
    })
    this.getDataFromApi()
  },
  computed: {
    toolbarButtons () {
      const arr = []
      arr.push({
        label: 'Items List',
        id: 'create',
        type: 'a',
        link: '/packaging/' + this.$route.params.so_id + '/items',
        color: 'teal',
        icon: 'list'
      })
      arr.push({
        label: 'Create',
        id: 'create',
        type: 'a',
        link: '/packaging/' + this.$route.params.so_id + '/create',
        color: 'teal',
        icon: 'add'
      })
      return arr
    },
    breadcrumbs () {
      return [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders', link: '/sale_orders' },
        { label: this.sale_order.serial_no, link: '/sale_orders/view/' + this.$route.params.so_id },
        { label: 'Packaging', link: '' }
      ]
    }
  },
  methods: {
    deleteFn (id) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$q.loading.show()
        const route = 'packaging/' + this.$route.params.so_id + '/' + id
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
      })
    },
    getDataFromApi () {
      this.loading = true
      this.$axios.get('packaging/' + this.$route.params.so_id).then((res) => {
        this.items = res.data
      }).finally(() => {
        this.loading = false
      })
    },
    download (index, id) {
      this.$q.loading.show()
      this.$axios({
        url: 'packaging/' + this.$route.params.so_id + '/download/' + id,
        method: 'GET',
        responseType: 'blob'
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', this.items[index].name.replace(/\//g, '') + '.pdf')
        document.body.appendChild(link)
        link.click()
      }).finally(() => {
        this.$q.loading.hide()
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
