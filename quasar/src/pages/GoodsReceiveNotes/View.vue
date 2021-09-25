<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"
      @createpurchase="createPurchase"
      v-on:activate="activateFn"
      v-on:revert="revert"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <div class="row q-col-gutter-md q-pa-md ">
          <div class="col">
              <q-markup-table wrap-cells separator="cell">
                  <tbody>
                      <tr>
                          <td>Serial No</td>
                          <td>{{serial_no}}</td>
                      </tr>
                      <tr>
                          <td>Supplier</td>
                          <td><router-link :to="'/suppliers/view/' + supplier.id">{{supplier.name}}</router-link></td>
                      </tr>
                      <tr>
                          <td>Warehouse</td>
                          <td>{{warehouse.name}}</td>
                      </tr>
                      <tr v-if="purchase">
                          <td>Purchase Entry</td>
                          <td><router-link :to="'/purchases/view/' + purchase.id">{{purchase.bill_number}}</router-link></td>
                      </tr>
                  </tbody>
              </q-markup-table>
          </div>
          <div class="col">
              <q-markup-table wrap-cells separator="cell" >
                  <tbody>
                      <tr>
                          <td>Created By</td>
                          <td>{{created_by.name}}</td>
                      </tr>
                      <tr>
                          <td>Status</td>
                          <td>{{status}}</td>
                      </tr>
                      <tr>
                          <td>Delivery Date</td>
                          <td>{{delivery_date}}</td>
                      </tr>
                  </tbody>
              </q-markup-table>
          </div>
      </div>
      <div class="row q-col-gutter-md q-pa-md ">
          <div class="col">
              <q-table
                  class="my-sticky-header-table"
                  :columns="columns"
                  title="Items"
                  :data="items"
                  row-key="index"
                  wrap-cells
                  :loading="table_loading"
                  :rows-per-page-options="[0]"
                  :filter="search"
                  >
                  <template v-slot:body="props">
                      <q-tr :props="props">
                          <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                          <q-td>
                              <router-link :to="'/products/view/' + props.row.product.id"> {{props.row.product.name}}</router-link>
                              <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.product.name)"></q-btn>
                          </q-td>
                          <q-td class="text-right">
                            {{props.row.expiry_date}}
                          </q-td>
                          <q-td class="text-right">
                              {{props.row.qty}}
                          </q-td>
                      </q-tr>
                  </template>
                  <template v-slot:top-right>
                      <q-input borderless dense debounce="300" v-model="search" placeholder="Search">
                      <template v-slot:append>
                          <q-icon name="search" />
                      </template>
                      </q-input>
                  </template>
              </q-table>
          </div>
      </div>
      <div class="row q-mt-xs">
        <div class="col-12" >
          <div class="text-h6">Remarks</div>
          <div v-html="remarks"></div>
        </div>
      </div>
    </div>
    <page-toolbar :page-title="''" :buttons="toolbarButtons"
        v-on:activate="activateFn"
        @createpurchase="createPurchase"
        v-on:revert="revert"
        class="q-mt-md"/>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'PurchasesView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      serial_no: null,
      supplier: {
        name: null
      },
      created_by: {
        name: null
      },
      warehouse: {
        name: null
      },
      items: [],
      delivery_date: null,
      status: null,
      table_loading: false,
      search: null,
      remarks: '',
      purchase: null
    }
  },
  computed: {
    columns () {
      const arr = [
        {
          name: 'sl_no',
          field: 'sl_no',
          label: '#',
          sortable: true
        },
        {
          name: 'name',
          field: 'name',
          label: 'Product',
          sortable: true,
          align: 'left'
        },
        {
          name: 'expiry_date',
          field: 'expiry_date',
          label: 'Expiry Date',
          sortable: true,
          align: 'right'
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        }
      ]
      return arr
    },
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_purchase')) {
        if (this.status === 'Active' && !(this.purchase && this.purchase.status !== 'Draft')) {
          arr.push({
            label: 'Revert',
            id: 'revert',
            type: 'button',
            color: 'black',
            icon: 'undo'
          })
        }
        if (this.status === 'Draft' || this.status === 'Draft - Reverted') {
          arr.push({
            label: 'Activate',
            id: 'activate',
            type: 'button',
            color: 'green-10',
            icon: 'check'
          })
          arr.push({
            label: 'Edit',
            id: 'edit',
            type: 'a',
            link: '/goods_receive_notes/edit/' + this.$route.params.id,
            color: 'teal',
            icon: 'edit'
          })
        } else {
          if (!this.purchase) {
            arr.push({
              label: 'Enter Purchase',
              id: 'createpurchase',
              type: 'button',
              color: 'blue-10',
              icon: 'forward'
            })
          }
        }
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Good Receive Notes', link: '/goods_receive_notes' },
        { label: this.serial_no }
      ]
      return arr
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('view_purchase')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      Promise.all([
        this.getDataFromApi()
      ]).finally(() => {
        this.$q.loading.hide()
      })
    }
  },
  methods: {
    getDataFromApi () {
      this.$q.loading.show()
      this.$axios.get('goods_receive_notes/' + this.$route.params.id).then((res) => {
        this.serial_no = res.data.serial_no
        this.status = res.data.status
        this.supplier = res.data.supplier
        this.delivery_date = res.data.delivery_date
        this.created_by = res.data.created_by
        this.warehouse = res.data.warehouse
        this.remarks = res.data.remarks
        this.items = res.data.items
        this.purchase = res.data.purchase
      }).then(() => {
        this.$store.commit('setPageTitle', 'Goods Receive Note: ' + this.serial_no)
        this.$q.loading.hide()
      })
    },
    generatePurchase () {
      this.$q.dialog({
        message: 'Do you want to continue', cancel: true
      }).onOk(() => {
        this.$router.push('/purchases/create?grn_id=' + this.$route.params.id)
        this.$q.notify({
          color: 'blue-10',
          message: 'Note: This purchase order is not saved yet.'
        })
      })
    },
    createPurchase () {
      this.$router.push('/purchases/create?grn_id=' + this.$route.params.id)
      this.$q.notify({
        message: 'Note!. This purchase entry is not saved yet.',
        color: 'blue-10'
      })
    },
    activateFn () {
      this.$q.dialog({
        message: 'Do you want to continue', cancel: true
      }).onOk(() => {
        this.$axios.get('goods_receive_notes/activate/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              type: 'positive',
              message: 'Data Updated Successfully'
            })
            this.$router.push('/goods_receive_notes')
          }
        })
      })
    },
    revert () {
      this.$q.dialog({
        message: 'Do you want to continue', cancel: true
      }).onOk(() => {
        this.$axios.get('goods_receive_notes/revert/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              type: 'positive',
              message: 'Data Updated Successfully'
            })
            this.$router.push('/goods_receive_notes')
          }
        })
      })
    }
  }
}
</script>
