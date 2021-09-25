<template>
  <q-page>
    <page-toolbar
      :buttons="toolbarButtons"
      v-on:registerpayment="settlementDialog = true"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <div class="row q-col-gutter-md q-pa-md ">
        <div class="col">
          <q-markup-table wrap-cells separator="cell">
            <tbody>
              <tr>
                <td>Serial No</td>
                <td><router-link :to="'/sale_orders/view/' + $route.params.id">{{model.serial_no}}</router-link></td>
              </tr>
              <tr>
                <td>Customer</td>
                <td><router-link :to="'/customers/view/'+model.customer.id">{{model.customer.name}}</router-link></td>
              </tr>
              <tr>
                <td>Warehouse</td>
                <td>{{model.warehouse.name}}</td>
              </tr>
              <tr>
                <td>Pricelist</td>
                <td>{{model.pricelist.name}}</td>
              </tr>
              <tr>
                <td>Source</td>
                <td>{{model.source}}</td>
              </tr>
              <tr v-if="model.shipment">
                <td>Shipment</td>
                <td> <router-link :to="'/shipments/view/'+model.shipment.id">{{model.shipment.serial_no}}</router-link></td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
        <div class="col">
          <q-markup-table wrap-cells separator="cell" >
            <tbody>
              <tr>
                <td>Status</td>
                <td>{{model.status}}</td>
              </tr>
              <tr>
                <td>Created By</td>
                <td>{{model.created_by.name}}</td>
              </tr>
              <tr>
                <td>Representative</td>
                <td>{{model.representative.name}}</td>
              </tr>
              <tr>
                <td>Created At</td>
                <td>{{getLocaleString(model.created_at)}}</td>
              </tr>
              <tr>
                <td>Invoiced At</td>
                <td>{{getLocaleString(model.invoiced_at)}}</td>
              </tr>
              <tr v-if="model.invoiced_by">
                <td>Invoiced By</td>
                <td>{{model.invoiced_by.name}}</td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
      <div class="row q-col-gutter-md q-pa-md ">
        <div class="col">
          <q-markup-table wrap-cells separator="cell" >
            <tbody>
              <tr>
                <td>Payment Status:
                  <q-chip :color="getPaymentStatusColor()" text-color="white">{{model.payment_status}}</q-chip></td>
                <td>Paid: {{model.paid_amount}}</td>
                <td>Balance: {{balance_amount}}</td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
      <div class="row q-col-gutter-md q-pa-md ">
        <div class="col">
          <q-markup-table wrap-cells separator="cell" >
            <tbody>
              <tr>
                <td>Rate Includes GST: {{model.rate_includes_gst ? 'Yes':'No'}}</td>
                <td>Include Flood Cess ?: {{model.flood_cess_included ? 'Yes':'No'}}</td>
                <td>OTC: {{model.otc ? 'Yes':'No'}}</td>
                <td>COD: {{model.otc ? 'Yes':'No'}}</td>
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
            :data="model.items"
            row-key="sl_no"
            wrap-cells
            :loading="table_loading"
            :rows-per-page-options="[0]"
            :filter="search"
            >
            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                <q-td>
                  {{props.row.product.name}}
                  <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.product.name)"></q-btn>
                </q-td>
                <q-td class="text-right">
                  <div v-if="props.row.gst">
                    {{props.row.gst}}%
                  </div>
                </q-td>
                <q-td class="text-right">
                  {{ parseFloat(props.row.qty).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
                <q-td class="text-right">
                  {{parseFloat(props.row.rate).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
                <q-td class="text-right">
                  {{props.row.expiry_date}}
                </q-td>
                <q-td class="text-right">
                  {{props.row.is_free ? 'Yes':'No'}}
                </q-td>
                <q-td class="text-right" v-if="!model.rate_includes_gst">
                  {{parseFloat(props.row.taxable).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
                <q-td class="text-right" v-if="!model.rate_includes_gst">
                  {{parseFloat(props.row.tax_amount).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
                <q-td class="text-right">
                  {{parseFloat(props.row.total).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
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
            <template v-slot:bottom-row>
              <q-tr class="bg-blue-grey-3">
                <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                <q-td class="text-right">Subtotal</q-td>
                <q-td class="text-right">{{parseFloat(model.subtotal).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                <q-td class="text-right">Discount</q-td>
                <q-td class="text-right">{{parseFloat(model.discount).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                <q-td class="text-right">Freight</q-td>
                <q-td class="text-right">{{parseFloat(model.freight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                <q-td class="text-right">Round</q-td>
                <q-td class="text-right">{{parseFloat(model.round).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-3">
                <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                <q-td class="text-right">Total</q-td>
                <q-td class="text-right">{{parseFloat(model.total).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
            </template>
          </q-table>
        </div>
      </div>
      <div class="row q-mt-sm">
        <div class="col q-px-md">
          <div class="text-h6"> Additional Information</div>
          <q-markup-table wrap-cells separator="cell">
            <tbody>
              <tr>
                <td>PO Number: {{model.po_number}}</td>
                <td>Order Date: {{model.order_date}}</td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
      <div class="row q-mt-sm">
        <div class="col q-px-md">
          <div class="text-h6">Remarks</div>
          <div v-html="model.remarks"></div>
        </div>
      </div>
    </div>
    <page-toolbar
      :buttons="toolbarButtons"
      v-on:registerpayment="settlementDialog = true"/>
    <q-dialog v-model="settlementDialog" persistent position="top">
      <settlement-dialog
        :status="model.status"
        :customer="model.customer"
        :total="model.total"
        :id="$route.params.id"
        route="register_payment"
        :serial_no="model.serial_no"
        @close="settlementDialog = false; getDataFromApi()"/>
    </q-dialog>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
import settlementDialog from './SettlementDialog'
export default {
  name: 'SaleOrdersRevisitsView',
  components: {
    PageToolbar,
    Breadcrumbs,
    settlementDialog
  },
  data () {
    return {
      model: {
        serial_no: '',
        customer: {
          name: null
        },
        representative: {
          name: null
        },
        created_by: {
          name: null
        },
        created_at: null,
        invoiced_at: null,
        status: null,
        remarks: '',
        warehouse: {
          name: null
        },
        pricelist: {
          name: null
        },
        source: null,
        rate_includes_gst: null,
        flood_cess_included: null,
        items: [],
        subtotal: null,
        discount: null,
        freight: null,
        round: null,
        total: null,
        cod: 0,
        otc: 0,
        payment_status: null,
        paid_amount: null,
        cod_charge: null,
        shipment: null,
        invoiced_by: {
          name: null
        }
      },
      search: null,
      table_loading: false,
      remarkDialog: false,
      approveRemark: '',
      settlementDialog: false,
      amount: 0,
      reference_id: '',
      payment_via: null,
      settlement_remarks: ''
    }
  },
  mounted () {
    this.getDataFromApi()
  },
  computed: {
    balance_amount () {
      return this.model.total - this.model.paid_amount
    },
    toolbarButtons () {
      const arr = []
      arr.push({
        label: 'Register Payment',
        id: 'registerpayment',
        type: 'button',
        color: 'teal-10',
        icon: 'add'
      })
      return arr
    },
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
          field: (row) => row.product.name,
          label: 'Product',
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
          name: 'rate',
          field: 'rate',
          label: 'Rate',
          sortable: true
        },
        {
          name: 'expiry_date',
          field: 'expiry_date',
          label: 'Exp Date',
          sortable: true
        },
        {
          name: 'is_free',
          field: 'is_free',
          label: 'Free ?',
          sortable: true
        }
      ]
      if (!this.model.rate_includes_gst) {
        arr.push({
          name: 'taxable',
          field: 'taxable',
          label: 'Taxable',
          sortable: true
        },
        {
          name: 'tax_amount',
          field: 'tax_amount',
          label: 'Tax Amount',
          sortable: true
        })
      }
      arr.push({
        name: 'total',
        field: 'total',
        label: 'Total',
        sortable: true
      })
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders Revists', link: '/sale_order_revisits' },
        { label: this.model.serial_no }
      ]
      return arr
    }
  },
  methods: {
    getDataFromApi () {
      this.$q.loading.show()
      this.$axios.get('sale_orders/' + this.$route.params.id).then((res) => {
        this.model = res.data
      }).finally(() => {
        this.$store.commit('setPageTitle', this.model.serial_no)
        this.$q.loading.hide()
      })
    },
    getPaymentStatusColor () {
      if (this.model.payment_status === 'Settled') {
        return 'green-10'
      }
      if (this.model.payment_status === 'Partially Settled') {
        return 'orange-10'
      }
      if (this.model.payment_status === 'Unsettled') {
        return 'red-10'
      }
    },
    submitPayment () {
      if (this.amount === null || this.amount === undefined || this.amount === '') {
        this.$q.notify({ message: 'Amount should not be empty', type: 'negative' })
      } else if (this.payment_via === null && parseFloat(this.amount) > 0) {
        this.$q.notify({ message: 'Payment Via is not specified', type: 'negative' })
      } else {
        this.$axios.post('sale_orders/register_payment/' + this.$route.params.id, {
          payment_via: this.payment_via,
          amount: this.amount,
          reference_id: this.reference_id,
          remarks: this.settlement_remarks
        }).then((res) => {
          if (res.data.message === 'success') {
            // this.getDataFromApi()
            this.$q.notify({
              type: 'positive',
              message: 'Payment Registered Successfully'
            })
            this.settlementDialog = false
            this.getDataFromApi()
          }
        })
      }
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
    }
  }
}
</script>
