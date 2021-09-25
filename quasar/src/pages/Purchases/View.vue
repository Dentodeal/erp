<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"
      v-on:revert="revertFn"
      v-on:sendToAdmin="sendToAdmin"
      v-on:approve="approveFn"
      v-on:activate="activateFn"
      v-on:purchasereturn="purchaseReturnDialog = true"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <q-toolbar class="q-px-lg" v-if="return_count > 0">
      <q-space/>
      <q-btn depressed color="cyan-7" label="purchase returns" :to="'/purchases/returns/' + $route.params.id">
      <q-badge color="white" class="text-black q-ml-sm">{{return_count}}</q-badge>
      </q-btn>
    </q-toolbar>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <div class="row q-col-gutter-md q-pa-md ">
          <div class="col">
            <q-markup-table wrap-cells separator="cell">
              <tbody>
                <tr>
                  <td>Bill Number</td>
                  <td>{{bill_number}}</td>
                </tr>
                <tr>
                  <td>Supplier</td>
                  <td><router-link :to="'/suppliers/view/' + supplier.id">{{supplier.name}}</router-link></td>
                </tr>
                <tr>
                  <td>Warehouse</td>
                  <td>{{warehouse.name}}</td>
                </tr>
                <tr>
                  <td>Created By</td>
                  <td>{{created_by.name}}</td>
                </tr>
              </tbody>
            </q-markup-table>
          </div>
          <div class="col">
            <q-markup-table wrap-cells separator="cell" >
              <tbody>
                <tr>
                  <td>Status</td>
                  <td>{{status}}</td>
                </tr>
                <tr>
                  <td>Bill Date</td>
                  <td>{{bill_date}}</td>
                </tr>
                <tr>
                  <td>Delivery Date</td>
                  <td>{{delivery_date}}</td>
                </tr>
                <tr v-if="grn">
                  <td>GRN No</td>
                  <td><router-link :to="'/goods_receive_notes/view/' + grn.id">{{grn.serial_no}}</router-link></td>
                </tr>
              </tbody>
            </q-markup-table>
          </div>
        </div>
        <div class="row q-col-gutter-md">
          <div class="col">
            <q-markup-table wrap-cells separator="cell" >
              <tbody>
                <tr>
                  <td class="text-left">Row discount mode:</td><td class="text-left"> {{row_discount_mode}}</td>
                </tr>
              </tbody>
            </q-markup-table>
          </div>
          <div class="col">
            <q-markup-table wrap-cells separator="cell" >
              <tbody>
                <tr>
                  <td class="text-left">Freight Split Method:</td><td class="text-left"> {{freight_split_method}}</td>
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
              <template v-slot:bottom-row>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="9">
                  </q-td>
                  <q-td class="text-right">Total</q-td>
                  <q-td class="text-right">{{tax_row_total}}</q-td>
                  <q-td class="text-right">{{taxable_total}}</q-td>
                </q-tr>
                <q-tr>
                  <q-td colspan="100%"></q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Discount Taxable</q-td>
                  <q-td class="text-right"> - {{taxable_discount}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Items tax</q-td>
                  <q-td class="text-right">{{tax_total}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Bill Freight Tax</q-td>
                  <q-td class="text-right">{{bill_freight_gst}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-3">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Subtotal</q-td>
                  <q-td class="text-right">{{subtotal}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Discount Subtotal</q-td>
                  <q-td class="text-right"> - {{discount_subtotal}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Bill Freight</q-td>
                  <q-td class="text-right">{{bill_freight}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Round</q-td>
                  <q-td class="text-right">{{round}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-3">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Bill Total</q-td>
                  <q-td class="text-right">{{bill_total}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-1">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">External Freight</q-td>
                  <q-td class="text-right">{{external_freight}}</q-td>
                </q-tr>
                <q-tr class="bg-blue-grey-3">
                  <q-td colspan="10">
                  </q-td>
                  <q-td class="text-right">Grand Total</q-td>
                  <q-td class="text-right">{{grand_total}}</q-td>
                </q-tr>
              </template>
              <template v-slot:header="props">
                <q-tr :props="props">
                  <q-th auto-width />
                  <q-th
                    v-for="col in props.cols"
                    :key="col.name"
                    :props="props">
                    {{ col.label }}
                  </q-th>
                </q-tr>
              </template>
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td auto-width>
                    <q-btn size="md" color="grey-7" round dense flat @click="props.expand = !props.expand" :icon="props.expand ? 'arrow_drop_down' : 'arrow_right'" />
                  </q-td>
                  <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                  <q-td>
                    <router-link :to="'/products/view/' + props.row.product.id"> {{props.row.product.name}}</router-link>
                    <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.product.name)"></q-btn>
                  </q-td>
                  <q-td class="text-left">
                    {{props.row.local ? 'Yes':'No'}}
                  </q-td>
                  <q-td class="text-right">
                    <div v-if="props.row.gst">
                      {{props.row.gst}}%
                    </div>
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.qty}}
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.is_free ? 'Yes':'No'}}
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.rate}}
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.cost}}
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.discount}}<span v-if="row_discount_mode == 'percentage'">%</span>
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.tax_amount}}
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.taxable}}
                  </q-td>
                </q-tr>
                <q-tr v-show="props.expand" :props="props" class="bg-grey-4">
                  <q-td/>
                  <q-td/>
                  <q-td class="text-left">
                    Weight: {{props.row.weight}}
                  </q-td>
                  <q-td class="text-left">
                    MRP: {{props.row.mrp}}
                  </q-td>
                  <q-td class="text-left">
                    HSN: {{props.row.hsn}}
                  </q-td>
                  <q-td class="text-left"> Expiry: {{props.row.expiry_date}}</q-td>
                  <q-td colspan="7"/>
                </q-tr>
              </template>
              <template v-slot:top-right="props">
                <q-input borderless dense debounce="300" v-model="search" placeholder="Search">
                  <template v-slot:append>
                    <q-icon name="search" />
                  </template>
                </q-input>
                <q-btn
                  flat round dense
                  :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                  @click="props.toggleFullscreen"
                  class="q-ml-md"/>
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
      </q-card>
    </div>
    <page-toolbar :page-title="''" :buttons="toolbarButtons"
      v-on:revert="revertFn"
      v-on:sendToAdmin="sendToAdmin"
      v-on:approve="approveFn"
      v-on:activate="activateFn"
      v-on:purchasereturn="purchaseReturnDialog = true"
      class="q-mt-md"/>
    <q-dialog
      v-model="purchaseReturnDialog"
      persistent
      maximized
      transition-show="slide-up"
      transition-hide="slide-down">
      <return-dialog :items="items"/>
    </q-dialog>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
import ReturnDialog from './ReturnDialog'
export default {
  name: 'PurchasesView',
  components: {
    PageToolbar,
    Breadcrumbs,
    ReturnDialog
  },
  data () {
    return {
      bill_number: null,
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
      expirableItems: [],
      subtotal: 0,
      bill_total: 0,
      delivery_date: null,
      round: 0,
      discount_subtotal: 0,
      discount: 0,
      bill_freight: 0,
      bill_freight_includes_gst: false,
      bill_freight_gst: null,
      external_freight: 0,
      status: 'draft',
      bill_date: null,
      row_discount_mode: 'percentage',
      grand_total: 0,
      table_loading: false,
      search: null,
      expirable: null,
      remarks: '',
      taxable_total: null,
      tax_row_total: null,
      tax_total: null,
      taxable_discount: null,
      freight_split_method: null,
      discount_split_method: '',
      grn: null,
      purchaseReturnDialog: false,
      return_count: 0
    }
  },
  computed: {
    columns () {
      const arr = [{
        name: 'sl_no',
        field: 'sl_no',
        label: '#',
        sortable: true
      },
      {
        name: 'name',
        field: (row) => row.product.name,
        label: 'Product',
        sortable: true,
        align: 'left'
      },
      {
        name: 'local',
        field: 'local',
        label: 'Avoid Costing?',
        sortable: true,
        align: 'left'
      },
      {
        name: 'gst',
        field: 'gst',
        label: 'GST',
        sortable: true
      },
      {
        name: 'qty',
        field: 'qty',
        label: 'Qty',
        sortable: true
      },
      {
        name: 'is_free',
        field: 'is_free',
        label: 'Free ?',
        sortable: true
      },
      {
        name: 'rate',
        field: 'rate',
        label: 'Rate',
        sortable: true
      }]
      if (this.$store.getters.hasPermissionTo('view_product_pricing')) {
        arr.push(
          {
            name: 'cost',
            field: 'cost',
            label: 'Cost',
            sortable: true
          })
      }
      arr.push(
        {
          name: 'discount',
          field: 'discount',
          label: 'Discount',
          sortable: true
        },
        {
          name: 'tax_amount',
          field: 'tax_amount',
          label: 'Tax Amount',
          sortable: true
        },
        {
          name: 'taxable',
          field: 'taxable',
          label: 'Taxable',
          sortable: true
        }
      )
      return arr
    },
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('revert_purchase') && this.status === 'Pending Approval') {
        arr.push({
          label: 'Revert',
          id: 'revert',
          type: 'button',
          color: 'grey-7',
          icon: 'undo'
        })
      }
      if (this.$store.getters.hasPermissionTo('activate_purchase') && this.status === 'Admin Approved') {
        arr.push({
          label: 'Activate',
          id: 'activate',
          type: 'button',
          color: 'green-10',
          icon: 'check'
        })
      }
      if (this.$store.getters.hasPermissionTo('create_purchase_return') && (this.status === 'Active')) {
        arr.push({
          label: 'Create Purchase Return',
          id: 'purchasereturn',
          type: 'button',
          color: 'orange-7',
          icon: 'undo'
        })
      }
      if (this.$store.getters.hasPermissionTo('approve_purchase') && this.status === 'Pending Approval') {
        arr.push({
          label: 'Approve',
          id: 'approve',
          type: 'button',
          color: 'green-10',
          icon: 'check'
        })
      }
      if (this.$store.getters.hasPermissionTo('update_purchase') && this.status === 'Draft') {
        arr.push({
          label: 'Send To Admin',
          id: 'sendToAdmin',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
        arr.push({
          label: 'Edit',
          id: 'edit',
          type: 'a',
          link: '/purchases/edit/' + this.$route.params.id,
          color: 'teal',
          icon: 'edit'
        })
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Purchases', link: '/purchases' },
        { label: this.bill_number }
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
  watch: {
    freight_split_method (method) {
      if (method !== null) this.calculateCosting()
    }
  },
  methods: {
    calculateCosting () {
      this.$q.loading.show()
      let percentageOfSubtotalDiscount = 0
      let percentageOfTaxableDiscount = 0
      if (parseFloat(this.discount_subtotal) > 0) {
        percentageOfSubtotalDiscount = (parseFloat(this.discount_subtotal) / parseFloat(this.subtotal))
      }
      if (parseFloat(this.taxable_discount) > 0) {
        percentageOfTaxableDiscount = (parseFloat(this.taxable_discount) / parseFloat(this.taxable_total))
      }
      const groupedItems = this.$_.groupBy(this.items, 'product_id')
      let freight = 0
      if (parseFloat(this.bill_freight) > 0 || parseFloat(this.external_freight) > 0) {
        const billFreight = !isNaN(this.bill_freight) ? parseFloat(this.bill_freight) : 0
        const externalFreight = !isNaN(this.external_freight) ? parseFloat(this.external_freight) : 0
        freight = billFreight + externalFreight
      }
      this.items.forEach((item, i) => {
        if (!item.is_free && !item.local) {
          let landing = item.rate
          if (groupedItems[item.product_id].length > 1) {
            landing = this.$_.sumBy(groupedItems[item.product_id], (obj) => parseFloat(obj.taxable)) / this.$_.sumBy(groupedItems[item.product_id], 'qty')
          }
          if (item.discount && parseFloat(item.discount) > 0) {
            if (this.row_discount_mode === 'percentage') {
              landing = (landing * (1 - (parseFloat(item.discount) / 100)))
            } else {
              landing = (landing - parseFloat(item.discount))
            }
          }
          if (percentageOfSubtotalDiscount) {
            // updating landing if there is any discount on subtotal. Reminder: subtotal always includes GST
            landing = landing * (1 - percentageOfSubtotalDiscount)
          }
          if (percentageOfTaxableDiscount) {
            // updating landing if there is any discount on subtotal. Reminder: subtotal always includes GST
            landing = landing * (1 - percentageOfTaxableDiscount)
          }
          if (parseFloat(this.bill_freight) > 0 || parseFloat(this.external_freight) > 0) {
            let costing = 0
            if (this.freight_split_method === 'weight') {
              const totalWeight = this.$_.sumBy(this.items, (v) => parseFloat(v.weight) * parseInt(v.qty))
              const freightOnSingleGram = freight / totalWeight
              costing = parseFloat(landing) + (parseFloat(freightOnSingleGram) * parseFloat(item.weight))
            }
            /*
            if (this.freight_split_method === 'quantity') {
              let itemQty = 0
              if (groupedItems[item.product_id].length > 1) {
                itemQty = this.$_.sumBy(groupedItems[item.product_id], (obj) => parseFloat(obj.qty))
              } else itemQty = parseInt(item.qty)
              const totalQty = this.$_.sumBy(this.items, (v) => parseInt(v.qty))
              const freightOnsingleQty = freight / totalQty
              costing = parseFloat(landing) + (parseFloat(freightOnsingleQty) * itemQty)
            }
            */
            if (this.freight_split_method === 'equal') {
              const freightOnsingleLine = freight / parseFloat(this.taxable_total) // percentage of freight on each line
              costing = parseFloat(landing) * (1 + freightOnsingleLine)
            }
            this.items[i].cost = parseFloat(costing).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
          } else {
            this.items[i].cost = parseFloat(landing).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
          }
        }
      })
      this.$q.loading.hide()
    },
    getDataFromApi () {
      this.$q.loading.show()
      this.$axios.get('purchases/' + this.$route.params.id).then((res) => {
        this.bill_number = res.data.bill_number
        this.status = res.data.status
        this.supplier = res.data.supplier
        this.bill_date = res.data.bill_date
        this.delivery_date = res.data.delivery_date
        this.created_by = res.data.created_by
        this.warehouse = res.data.warehouse
        this.row_discount_mode = res.data.row_discount_mode
        this.bill_freight_includes_gst = res.data.bill_freight_includes_gst
        this.bill_freight_gst = res.data.bill_freight_gst
        this.subtotal = res.data.subtotal
        this.discount_subtotal = res.data.discount_subtotal
        this.bill_freight = res.data.bill_freight
        this.round = res.data.round
        this.bill_total = res.data.bill_total
        this.external_freight = res.data.external_freight
        this.grand_total = res.data.grand_total
        this.remarks = res.data.remarks
        this.items = res.data.items
        this.taxable_total = res.data.taxable_total
        this.tax_row_total = res.data.tax_row_total
        this.tax_total = res.data.tax_total
        this.tax_total = res.data.tax_total
        this.taxable_discount = res.data.taxable_discount
        this.freight_split_method = res.data.freight_split_method
        this.grn = res.data.grn
        this.return_count = res.data.return_count
      }).then(() => {
        if (!this.freight_split_method) this.freight_split_method = 'weight'
        this.$store.commit('setPageTitle', 'Purchase: ' + this.bill_number)
        this.$q.loading.hide()
      })
    },
    revertFn () {
      this.$q.dialog({
        message: 'Do you want to continue', cancel: true
      }).onOk(() => {
        this.$axios.get('purchases/revert/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              message: 'Purchase Reverted Successfully'
            })
            this.$router.back()
          }
        })
      })
    },
    sendToAdmin () {
      this.$q.dialog({
        message: 'Do you want to continue', cancel: true
      }).onOk(() => {
        this.$q.loading.show()
        this.$axios.get('purchases/send_admin/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              color: 'blue-10',
              message: 'Purchase Sent for Approval'
            })
            this.$router.back()
          }
        }).catch((error) => {
          if (error.response.status === 422) {
            this.$q.notify({
              type: 'negative',
              message: error.response.data.message
            })
            let str = ''
            Object.keys(error.response.data.errors).forEach((key) => {
              str += '<p>' + error.response.data.errors[key][0] + '</p>'
            })
            this.$q.dialog({
              message: str,
              html: true
            })
          }
        }).finally(() => { this.$q.loading.hide() })
      })
    },
    approveFn () {
      this.$q.dialog({
        message: 'Do you want to continue', cancel: true
      }).onOk(() => {
        this.$axios.post('purchases/approve/' + this.$route.params.id, { freight_split_method: this.freight_split_method }).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              type: 'positive',
              message: 'Purchase Approved Successfully'
            })
            this.$router.back()
          }
        }).catch((error) => {
          this.$q.notify({
            type: 'negative',
            message: error.response.data.message
          })
          let str = ''
          Object.keys(error.response.data.errors).forEach((key) => {
            if (key.indexOf('items') !== -1) {
              str += '<p>' + 'Line ' + (parseInt(key.substr(key.indexOf('.') + 1)) + 1) + ':' + error.response.data.errors[key][0] + '</p><br>'
            }
          })
          this.$q.dialog({
            title: 'Error in data submitted',
            message: str,
            html: true
          })
        })
      })
    },
    activateFn () {
      this.$q.dialog({
        message: 'Do you want to continue', cancel: true
      }).onOk(() => {
        this.$axios.get('purchases/activate/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              type: 'positive',
              message: 'Data Updated Successfully'
            })
            this.$router.back()
          }
        })
      })
    }
  }
}
</script>
