<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" v-on:save="saveFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <q-card-section>
          <div class="row">
            <div class="col">
              <q-input v-model="model.name" label="Name" outlined dense square ref="name" :rules="[v => !!v || 'Required']"/>
            </div>
          </div>
          <div class="row q-mt-xs">
            <div class="col">
              <q-table
                :columns="columns"
                title="Items"
                :data="model.items"
                row-key="sl_no"
                >
                <template v-slot:body-cell-action="props">
                  <q-td class="text-right">
                    <q-btn color="grey-9" round flat icon="delete" @click="deleteItem(props.rowIndex)"/>
                  </q-td>
                </template>
                <template v-slot:body-cell-qty="props">
                  <q-td class="text-right">
                    {{props.value}}
                    <q-popup-edit v-model="props.row.qty" buttons>
                      <q-input v-model.number="props.row.qty" dense autofocus type="number"/>
                    </q-popup-edit>
                  </q-td>
                </template>
              </q-table>
            </div>
          </div>
          <div class="row q-col-gutter-xs q-mt-xs">
            <div class="">
              <q-btn icon="list" label="Select Items" flat color="teal" @click="itemsDialog = true" tabindex="11"/>
            </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
    <q-dialog maximized persistent transition-show="slide-up" transition-hide="slide-down" v-model="itemsDialog">
      <q-card class="bg-primary text-white">
        <q-bar>
          <q-space />
          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip content-class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>

        <q-card-section>
          <q-table
          title="Items"
          :data="sale_order.items"
          :columns="[{ name:'name', required: true, label: 'Item', align: 'left', field: row => row.product.name, sortable: true }]"
          selection="multiple"
          :selected.sync="model.items"
          :filter="search"
          >
            <template v-slot:top-right>
              <q-input borderless dense debounce="300" v-model="search" placeholder="Search" clearable>
                <template v-slot:append>
                  <q-icon name="search" />
                </template>
              </q-input>
            </template>
          </q-table>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'

export default {
  name: 'BoxCreate',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      columns: [
        {
          name: 'product',
          field: row => row.product.name,
          label: 'Item',
          align: 'left'
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
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
      sale_order: {
        serial_no: null
      },
      model: {
        name: null,
        items: []
      },
      itemsDialog: false,
      search: null
    }
  },
  mounted () {
    this.$store.commit('setPageTitle', 'Packaging')
    this.$axios.get('sale_orders/' + this.$route.params.so_id).then((res) => {
      this.sale_order = res.data
    })
    if (this.$route.params.id) {
      this.$axios.get('packaging/' + this.$route.params.so_id + '/' + this.$route.params.id).then((res) => {
        this.model = res.data
      })
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      arr.push({
        label: 'Save',
        id: 'save',
        type: 'button',
        color: 'teal',
        icon: 'save'
      })
      return arr
    },
    breadcrumbs () {
      return [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders', link: '/sale_orders' },
        { label: this.sale_order.serial_no, link: '/sale_orders/view/' + this.$route.params.so_id },
        { label: 'Packaging', link: '/packaging/' + this.$route.params.so_id },
        { label: 'Create' }
      ]
    }
  },
  methods: {
    saveFn () {
      if (this.$refs.name.validate() & this.validateCount()) {
        let route = 'packaging/' + this.$route.params.so_id
        if (this.$route.params.id) {
          this.model._method = 'PUT'
          route += '/' + this.$route.params.id
        }
        this.$axios.post(route, this.model).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              message: 'Saved Successfully',
              type: 'positive'
            })
            this.$router.back()
          }
        }).catch((err) => {
          if (err.response.status === 422) {
            let str = ''
            Object.keys(err.response.data.errors).forEach((key) => {
              str += err.response.data.errors[key][0] + '</br>'
            })
            this.$q.dialog({
              html: true,
              message: str
            })
          }
        })
      }
    },
    validateCount () {
      if (this.model.items.length > 0) {
        return true
      } else {
        this.$q.notify({
          message: 'There Should be atleast one item!!',
          type: 'negative'
        })
        return false
      }
    },
    deleteItem (index) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Do you want to delete this item?',
        cancel: true
      }).onOk(() => {
        this.model.items.splice(index, 1)
      })
    }
  }
}
</script>
