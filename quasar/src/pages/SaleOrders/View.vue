<template>
  <q-page>
    <page-toolbar
      :buttons="toolbarButtons"
      v-on:sendtoaccounts="sendtoaccounts()"
      v-on:invoice="settlementDialog = true"
      v-on:requestapproval="requestapproval()"
      v-on:approve="approve()"
      v-on:reject="reject()"
      v-on:cancel="cancel()"
      v-on:complete="complete()"
      v-on:revert="revert()"
      v-on:download="download()"
      v-on:sendforconfirmation="sendforconfirmation()"
      v-on:confirm="confirm()"
      v-on:salereturn="saleReturnDialog = true"
      v-on:export="exportFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <q-toolbar class="q-px-lg" v-if="return_count > 0">
      <q-space/>
      <q-btn depressed color="cyan-7" label="sale returns" :to="'/sale_orders/returns/' + $route.params.id">
      <q-badge color="white" class="text-black q-ml-sm">{{return_count}}</q-badge>
      </q-btn>
    </q-toolbar>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <div class="row q-col-gutter-md q-pa-md ">
        <div class="col-12 col-md-6">
          <q-markup-table wrap-cells separator="cell">
            <tbody>
              <tr>
                <td>Serial No</td>
                <td>{{model.serial_no}}</td>
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
              <tr v-if="model.cod">
                <td colspan="2"><div class="text-h5 text-center">COD</div></td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
        <div class="col-12 col-md-6">
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
              <tr v-if="model.order_no">
                <td>Order No</td>
                <td>{{model.order_no}}</td>
              </tr>
              <tr v-if="model.quotation">
                <td>Quotation</td>
                <td><router-link :to="'/quotations/view/' + model.quotation.id">{{model.quotation.serial_no}}</router-link></td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
      <div class="row" v-if="model.billing_address && model.customer.name!= 'OTC'">
        <div class="col-12 col-md-6 q-mt-md q-pa-sm">
          <q-card>
            <q-card-section class="bg-blue-10 text-white">
                <div class="text-subtitle2 ">Billing Address</div>
            </q-card-section>
            <q-card-section>
                <div class="text-subtitle1">{{model.billing_address}}</div>
            </q-card-section>
          </q-card>
        </div>
        <div class="col-12 col-md-6 q-mt-md q-pa-sm">
          <q-card>
            <q-card-section class="bg-orange-10 text-white">
              <div class="text-subtitle2 ">Shipping Address</div>
            </q-card-section>
            <q-card-section>
              <div class="text-subtitle1" >{{model.shipping_address.content}}</div>
            </q-card-section>
          </q-card>
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
                <td>Balance: {{model.balance_amount}}</td>
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
                <td>COD: {{model.cod ? 'Yes':'No'}}</td>
                <td>Type: {{model.type}}</td>
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
            <template v-slot:header="props">
              <q-tr :props="props">
                <q-th auto-width />
                <q-th
                  v-for="col in props.cols"
                  :key="col.name"
                  :props="props"
                >
                  {{ col.label }}
                </q-th>
              </q-tr>
            </template>
            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td auto-width>
                  <q-btn round dense flat @click="props.expand = !props.expand" :icon="props.expand ? 'arrow_drop_down' : 'arrow_right'" />
                </q-td>
                <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                <q-td>
                  <router-link :to="'/products/view/' + props.row.product.id">{{props.row.product.name}}</router-link>
                  <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.product.name)"></q-btn>
                </q-td>
                <q-td class="text-right">
                  {{ parseFloat(props.row.product.weight * props.row.qty).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
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
                  {{currencySymbol}}{{parseFloat(props.row.rate).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
                <q-td class="text-right">
                  {{props.row.expiry_date}}
                </q-td>
                <q-td class="text-right" v-if="!model.rate_includes_gst">
                  {{currencySymbol}}{{parseFloat(props.row.taxable).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
                <q-td class="text-right" v-if="!model.rate_includes_gst">
                  {{currencySymbol}}{{parseFloat(props.row.tax_amount).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
                <q-td class="text-right">
                  {{currencySymbol}}{{parseFloat(props.row.total).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                </q-td>
              </q-tr>
              <q-tr v-show="props.expand" :props="props">
                <q-td colspan="100%">
                  <div class="text-left">Use Mask: {{props.row.use_mask ? 'Yes' : 'No'}}, Mask Name: {{ props.row.mask_name || 'NIL' }}</div>
                </q-td>
              </q-tr>
            </template>
            <template v-slot:top-right="props">
              <q-input borderless dense debounce="300" v-model="search" placeholder="Search" clearable>
              <template v-slot:append>
                <q-icon name="search" />
              </template>
              </q-input>
              <q-btn
                flat round dense
                :icon="props.inFullscreen ? 'fullscreen_exit' : 'fullscreen'"
                @click="props.toggleFullscreen"
                class="q-ml-md"
              />
            </template>
            <template v-slot:bottom-row>
              <q-tr class="bg-blue-grey-3 text-bold">
                <q-td colspan="2"></q-td>
                <td class="text-right">Total Weight:</td>
                <td class="text-right">{{parseFloat(totalWeight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}gm</td>
                <q-td :colspan="!model.rate_includes_gst ? 5 : 3"/>
                <q-td class="text-right">Subtotal</q-td>
                <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.subtotal).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td :colspan="!model.rate_includes_gst ? 9 : 7"/>
                <q-td class="text-right">Discount</q-td>
                <q-td class="text-right">-{{currencySymbol}}{{parseFloat(model.discount).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1" v-if="model.cod">
                <q-td :colspan="!model.rate_includes_gst ? 9 : 7"/>
                <q-td class="text-right">COD Charge</q-td>
                <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.cod_charge).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td :colspan="!model.rate_includes_gst ? 9 : 7"/>
                <q-td class="text-right">Freight</q-td>
                <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.freight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td :colspan="!model.rate_includes_gst ? 9 : 7"/>
                <q-td class="text-right">Bank Charges</q-td>
                <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.bank_charges).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td :colspan="!model.rate_includes_gst ? 9 : 7"/>
                <q-td class="text-right">Round</q-td>
                <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.round).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-3 text-bold">
                <q-td :colspan="!model.rate_includes_gst ? 9 : 7"/>
                <q-td class="text-right">Total</q-td>
                <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.total).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
            </template>
          </q-table>
        </div>
      </div>
      <div class="row q-mt-sm">
        <div class="col q-px-md">
          <div class="text-h6"> Additional Information</div>
          <q-markup-table wrap-cells separator="cell" flat>
            <tbody>
              <tr>
                <td>PO Number: {{model.po_number}}</td>
                <td>Order Date: {{model.order_date}}</td>
              </tr>
            </tbody>
          </q-markup-table>
        </div>
      </div>
      <div class="row q-mt-sm q-col-gutter-md">
        <div class="col-12 col-md-6 q-px-md">
          <div class="text-h6">Remarks</div>
          <div v-html="model.remarks"></div>
        </div>
        <div class="col-12 col-md-6 q-px-md">
          <div class="text-h6">Dentodeal Data</div>
          <div v-html="model.dentodeal_data"></div>
        </div>
      </div>
        </div>
        <q-dialog v-model="settlementDialog" persistent position="top">
          <settlement-dialog
            :status="model.status"
            :customer="model.customer"
            :total="model.total"
            :id="$route.params.id"
            route="invoice"
            :serial_no="model.serial_no"
            @close="settlementDialog = false; getDataFromApi()"/>
        </q-dialog>
        <page-toolbar :page-title="''" :buttons="toolbarButtons"
            v-on:sendtoaccounts="sendtoaccounts()"
            v-on:invoice="settlementDialog = true"
            v-on:requestapproval="requestapproval()"
            v-on:approve="approve()"
            v-on:reject="reject()"
            v-on:cancel="cancel()"
            v-on:complete="complete()"
            v-on:revert="revert()"
            v-on:download="download()"
            v-on:sendforconfirmation="sendforconfirmation()"
            v-on:confirm="confirm()" class="q-mt-md"
            v-on:salereturn="saleReturnDialog = true"
            v-on:export="exportFn"/>
        <q-dialog v-model="remarkDialog" position="top">
            <q-card style="width:500px">
                <q-card-section>
                    <div class="text-subtitle2">Remarks</div>
                    <q-editor rows="3" v-model="approveRemark"/>
                </q-card-section>
                <q-card-actions>
                    <q-btn color="green-10" label="Submit" @click="submitApproval" :loading="approveLoading"/>
                    <q-btn color="grey-7" label="Cancel" flat @click="remarkDialog = false"/>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog persistent v-model="exportDialog" position="top">
          <q-card style="width:350px">
            <q-card-section>
              <div class="text-subtitle1">Select Export Mode</div>
              <div class="row">
                <div class="col">
                  <q-radio v-model="export_mode" label="pdf" val="pdf"/>
                  <q-radio v-model="export_mode" label="excel(.xlsx)" val="excel"/>
                </div>
              </div>
              <div class="row">
                <div class="col" v-if="model.type == 'Standard'">
                  <q-checkbox v-model="export_include_gst_column" label="Include GST Column ?"/>
                </div>
                <div class="col" v-if="model.type == 'Export'">
                  <q-checkbox v-model="export_use_mask_name" label="Use Mask Name ?"/>
                  <q-checkbox v-model="export_include_hsn" label="Include HSN Column ?"/>
                </div>
              </div>
            </q-card-section>
            <q-card-actions>
              <q-btn label="export" color="primary" @click="performExport" :disable="!export_mode"/>
              <q-btn label="cancel" flat color="secondary" @click="exportDialog=false"/>
            </q-card-actions>
          </q-card>
        </q-dialog>
        <q-dialog
          v-model="saleReturnDialog"
          persistent
          maximized
          transition-show="slide-up"
          transition-hide="slide-down"
        >
          <return-dialog :items="model.items" :rateIncludesGst="model.rate_includes_gst" />
        </q-dialog>
        <q-dialog
          v-model="shipmentSelectionDialog"
          persistent
          maximized
          transition-show="slide-up"
          transition-hide="slide-down"
        >
          <shipment-dialog :items="model.items"/>
        </q-dialog>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
import ReturnDialog from './ReturnDialog'
import ShipmentDialog from './ShipmentDialog'
import SettlementDialog from './SettlementDialog.vue'
export default {
  name: 'SaleOrdersView',
  components: {
    PageToolbar,
    Breadcrumbs,
    ReturnDialog,
    ShipmentDialog,
    SettlementDialog
  },
  data () {
    return {
      exportDialog: false,
      export_mode: null,
      export_include_gst_column: true,
      export_use_mask_name: false,
      export_include_hsn: false,
      model: {
        serial_no: '',
        customer: {
          name: null,
          non_billable_account: false
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
        payment_status: null,
        paid_amount: null,
        cod: 0,
        otc: 0,
        billing_address: null,
        shipping_address: null,
        cod_charge: null,
        shipment: null,
        invoiced_by: {
          name: null
        },
        order_no: null,
        po_number: null,
        order_date: null,
        quotation: null,
        dentodeal_data: '',
        currency: 'INR',
        bank_charges: 0
      },
      search: null,
      table_loading: false,
      remarkDialog: false,
      approveRemark: '',
      settlementDialog: false,
      amount: 0,
      reference_id: '',
      payment_via: null,
      settlement_remarks: '',
      saleReturnDialog: false,
      shipmentSelectionDialog: false,
      selectedForShipment: [],
      return_count: null,
      approveLoading: false
    }
  },
  mounted () {
    this.getDataFromApi()
  },
  computed: {
    currencySymbol () {
      if (this.model.currency === 'INR') {
        return '₹'
      }
      return '$'
    },
    totalWeight () {
      return this.$_.sumBy(this.model.items, (v) => parseFloat(v.product.weight * v.qty))
    },
    balance_amount () {
      return this.model.otal - this.model.paid_amount
    },
    toolbarButtons () {
      const arr = []
      arr.push({
        label: 'Packaging',
        id: 'packaging',
        type: 'a',
        link: '/packaging/' + this.$route.params.id,
        flat: true,
        color: 'grey-9',
        icon: 'archive'
      })
      arr.push({
        label: 'Download PDF',
        id: 'download',
        type: 'button',
        flat: true,
        color: 'grey-7',
        icon: 'get_app'
      })
      arr.push({
        label: 'Download Invoice',
        id: 'export',
        type: 'button',
        flat: true,
        color: 'grey-8',
        icon: 'get_app'
      })
      if (this.model.status !== 'Cancelled') {
        if (this.$store.getters.hasPermissionTo('revert_sale_order') && (this.model.status !== 'Draft')) {
          arr.push({
            label: 'Revert',
            id: 'revert',
            type: 'button',
            color: 'grey-7',
            icon: 'undo'
          })
        }
        if (this.$store.getters.hasPermissionTo('create_sale_return') && (this.model.status === 'Complete')) {
          arr.push({
            label: 'Create Sale Return',
            id: 'salereturn',
            type: 'button',
            color: 'orange-7',
            icon: 'undo'
          })
        }
        if (this.$store.getters.hasPermissionTo('ship_sale_order') && this.model.status === 'Invoiced' && this.model.otc) {
          arr.push({
            label: 'Complete',
            id: 'complete',
            type: 'button',
            color: 'green-7',
            icon: 'check'
          })
        }
        if (this.$store.getters.hasPermissionTo('reject_sale_order') && this.model.status === 'Pending Approval') {
          arr.push({
            label: 'Reject',
            id: 'reject',
            type: 'button',
            color: 'red-8',
            icon: 'close'
          })
        }
        if (this.$store.getters.hasPermissionTo('approve_sale_order') && this.model.status === 'Pending Approval') {
          arr.push({
            label: 'Approve',
            id: 'approve',
            type: 'button',
            color: 'green-8',
            icon: 'check'
          })
        }
        if (this.$store.getters.hasPermissionTo('request_approval_sale_order') && (this.model.status === 'Invoice Pending' || this.model.status === 'Rejected')) {
          arr.push({
            label: 'Request for Approval',
            id: 'requestapproval',
            type: 'button',
            color: 'orange-8',
            icon: 'pan_tool'
          })
        }
        if (this.$store.getters.hasPermissionTo('invoice_sale_order') && (this.model.status === 'Invoice Pending' || this.model.status === 'Approved')) {
          arr.push({
            label: 'Invoice',
            id: 'invoice',
            type: 'button',
            color: 'blue-7',
            icon: 'receipt'
          })
        }
        if (this.$store.getters.hasPermissionTo('send_to_accounts_sale_order') && (this.model.status === 'Draft' || this.model.status === 'Confirmed') && !this.model.customer.non_billable_account) {
          arr.push({
            label: 'Send to accounts',
            id: 'sendtoaccounts',
            type: 'button',
            color: 'blue-10',
            icon: 'forward'
          })
        }

        if (this.model.status === 'Draft' && this.model.source === 'Dentodeal' && !this.model.customer.non_billable_account) {
          arr.push({
            label: 'Send for confirmation',
            id: 'sendforconfirmation',
            type: 'button',
            color: 'blue-10',
            icon: 'forward'
          })
        }

        if (this.$store.getters.hasPermissionTo('confirm_sale_order') && this.model.status === 'Pending Confirmation') {
          arr.push({
            label: 'Confirm',
            id: 'confirm',
            type: 'button',
            color: 'green-10',
            icon: 'check'
          })
        }

        if (this.model.status === 'Draft') {
          arr.push({
            label: 'Edit',
            id: 'edit',
            type: 'a',
            link: '/sale_orders/edit/' + this.$route.params.id,
            color: 'teal',
            icon: 'edit'
          })
        }
        if (!(this.model.shipment && this.model.shipment.status === 'Complete')) {
          arr.push({
            label: 'Cancel',
            id: 'cancel',
            type: 'button',
            color: 'red',
            icon: 'close'
          })
        }
      }
      arr.push({
        label: 'Duplicate',
        id: 'duplicate',
        type: 'a',
        link: '/sale_orders/create?duplicate=' + this.$route.params.id,
        color: 'grey',
        icon: 'file_copy'
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
          name: 'weight',
          field: 'weight',
          label: 'Weight',
          sortable: true
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
          label: 'Tax Amt',
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
        { label: 'Sale Orders', link: '/sale_orders' },
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
        this.$store.commit('setPageTitle', this.serial_no)
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
    sendtoaccounts () {
      this.$q.dialog({
        cancel: true,
        title: 'Caution',
        message: 'Do you want to continue ?'
      }).onOk(() => {
        this.$axios.get('sale_orders/send_to_accounts/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            // this.getDataFromApi()
            this.$q.notify({
              type: 'positive',
              message: 'Sale Order is sent for invoice generation'
            })
            this.$router.push('/sale_orders')
          }
        })
      })
    },
    invoice () {
      if (this.amount === null || this.amount === undefined || this.amount === '') {
        this.$q.notify({ message: 'Amount should not be empty', type: 'negative' })
      } else if (this.payment_via === null && parseFloat(this.amount) > 0) {
        this.$q.notify({ message: 'Payment Via is not specified', type: 'negative' })
      } else {
        this.$axios.post('sale_orders/invoice/' + this.$route.params.id, {
          payment_via: this.payment_via,
          amount: this.amount,
          reference_id: this.reference_id,
          remarks: this.settlement_remarks
        }).then((res) => {
          if (res.data.message === 'success') {
            // this.getDataFromApi()
            this.$q.notify({
              type: 'positive',
              message: 'Sale Order is invoiced succesfully'
            })
            this.$router.push('/sale_orders')
          }
        })
      }
    },
    requestapproval () {
      this.$axios.get('sale_orders/request_approval/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          // this.getDataFromApi()
          this.$q.notify({
            type: 'positive',
            message: 'Sale Order is sent for approval'
          })
          this.$router.push('/sale_orders')
        }
      })
    },
    approve () {
      this.approveRemark = ''
      this.remarkDialog = true
    },
    submitApproval () {
      this.approveLoading = true
      this.$axios.post('sale_orders/approve/' + this.$route.params.id, { remarks: this.approveRemark }).then((res) => {
        if (res.data.message === 'success') {
          this.remarkDialog = false
          // this.getDataFromApi()
          this.$q.notify({
            type: 'positive',
            message: 'Sale Order is approved succesfully'
          })
          this.$router.push('/sale_orders')
        }
      })
    },
    reject () {
      this.$axios.get('sale_orders/reject/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          // this.getDataFromApi()
          this.$q.notify({
            type: 'positive',
            message: 'Sale Order is rejected succesfully'
          })
          this.$router.push('/sale_orders')
        }
      })
    },
    complete () {
      this.$q.dialog({
        cancel: true,
        title: 'Caution',
        message: 'Do you want to continue ?'
      }).onOk(() => {
        this.$axios.get('sale_orders/complete/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            // this.getDataFromApi()
            this.$q.notify({
              type: 'positive',
              message: 'Sale Order is set as complete'
            })
            this.$router.push('/sale_orders')
          }
        })
      })
    },
    revert () {
      this.$q.dialog({
        cancel: true,
        title: 'Caution',
        message: 'All transactions (settlements and refunds) & sale returns will be deleted !!!. Proceed ?'
      }).onOk(() => {
        this.$axios.get('sale_orders/revert/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            // this.getDataFromApi()
            this.$q.notify({
              type: 'positive',
              message: 'Sale Order is reverted succesfully'
            })
            this.$router.push('/sale_orders')
          }
        })
      })
    },
    cancel () {
      this.$q.dialog({
        cancel: true,
        title: 'Caution',
        message: 'Do you want to continue ?'
      }).onOk(() => {
        this.$axios.get('sale_orders/cancel/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            // this.getDataFromApi()
            this.$q.notify({
              type: 'negative',
              message: 'Sale Order is cancelled'
            })
            this.$router.push('/sale_orders')
          }
        })
      })
    },
    sendforconfirmation () {
      this.$axios.get('sale_orders/request_confirmation/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          // this.getDataFromApi()
          this.$q.notify({
            type: 'positive',
            message: 'Sale Order is sent for confirmation'
          })
          this.$router.push('/sale_orders')
        }
      })
    },
    confirm () {
      this.$axios.get('sale_orders/confirm/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          // this.getDataFromApi()
          this.$q.notify({
            type: 'positive',
            message: 'Sale Order is confirmed'
          })
          this.$router.push('/sale_orders')
        }
      })
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
    },
    download () {
      this.$q.loading.show()
      this.$axios({
        url: 'sale_orders/download/' + this.$route.params.id,
        method: 'GET',
        responseType: 'blob' // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', this.model.serial_no.replace(/\//g, '') + '.pdf')
        document.body.appendChild(link)
        link.click()
      }).finally(() => {
        this.$q.loading.hide()
      })
    },
    exportFn () {
      this.exportDialog = true
    },
    performExport () {
      this.$q.loading.show()
      let filename = 'Invoice_' + this.model.customer.name.replace(/[^a-zA-Z0-9]/g, '_') + '_' + this.model.serial_no.replace(/\//g, '')
      if (this.export_mode === 'pdf') {
        filename += '.pdf'
      }
      if (this.export_mode === 'excel') {
        filename += '.xlsx'
      }
      this.$axios({
        url: 'sale_orders/export/' + this.$route.params.id,
        method: 'POST',
        responseType: 'blob', // important
        data: {
          include_gst_column: this.export_include_gst_column,
          use_mask_name: this.export_use_mask_name,
          include_hsn_column: this.export_include_hsn,
          mode: this.export_mode
        }
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', filename)
        document.body.appendChild(link)
        link.click()
      }).finally(() => {
        this.$q.loading.hide()
        this.exportDialog = false
      })
    }
  }
}
</script>
