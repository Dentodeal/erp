<template>
  <q-page>
    <page-toolbar
      :buttons="toolbarButtons"
      v-on:createso="createSaleOrder()"
      v-on:export="exportFn"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
        >
          <q-tab name="details" label="Details" />
          <q-tab name="payments" label="Payments" />
          <q-tab name="documents" label="Documents" />
        </q-tabs>
        <q-separator />
        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="details">
            <div class="row q-col-gutter-md q-pa-md ">
              <div class="col">
                <q-markup-table wrap-cells separator="cell">
                  <tbody>
                    <tr>
                      <td>Serial No</td>
                      <td>{{model.serial_no}}</td>
                    </tr>
                    <tr>
                      <td>Customer/Lead</td>
                      <td><router-link :to="getCustomerLink">{{this.model.customer.name}}</router-link>
                        <template v-if="billingAddress">
                        <br>{{billingAddress}}
                        </template>
                      </td>
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
                      <td>Inco Term</td>
                      <td>{{model.inco_term}}</td>
                    </tr>
                    <tr>
                      <td>Shipping Point</td>
                      <td>{{model.fob_point}}</td>
                    </tr>
                    <tr>
                      <td>PO Number</td>
                      <td>{{model.po_number}}</td>
                    </tr>
                    <tr>
                      <td>Contact Person</td>
                      <td>{{model.contact_person || model.representative.name}}</td>
                    </tr>
                    <tr>
                      <td>Contact Phone</td>
                      <td>{{model.contact_phone}}</td>
                    </tr>
                    <tr>
                      <td>Bank</td>
                      <td>
                        <q-item v-if="model.bank">
                          <q-item-section>
                            <q-item-label>{{model.bank.name}}</q-item-label>
                            <q-item-label caption>Account Name: {{ model.bank.acc_name }}</q-item-label>
                            <q-item-label caption>Bank: {{ model.bank.bank_name }}</q-item-label>
                            <q-item-label caption>Acc No. {{ model.bank.acc_no }}</q-item-label>
                            <q-item-label caption>IFSC: {{ model.bank.ifsc }}</q-item-label>
                            <q-item-label caption>UPI: {{ model.bank.upi }}</q-item-label>
                          </q-item-section>
                        </q-item>
                      </td>
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
                        <td>Valid Until</td>
                        <td>{{getLocaleString(model.valid_until)}}</td>
                      </tr>
                      <tr>
                        <td>Ship Date</td>
                        <td>{{getLocaleString(model.ship_date)}}</td>
                      </tr>
                      <tr>
                        <td>Ship Via</td>
                        <td>{{model.ship_via}}</td>
                      </tr>
                      <tr>
                        <td>Contact Email</td>
                        <td>{{model.contact_email}}</td>
                      </tr>
                      <template v-if="model.sale_orders">
                        <tr v-for="(sale_order,i) in model.sale_orders" :key="i">
                          <td v-if="i === 0" :rowspan="model.sale_orders.length">Sale Orders</td>
                          <td :style="i !== 0 ? { borderLeft: '1px solid rgba(0, 0, 0, 0.12)'} : ''">
                            <router-link :to="'/sale_orders/view/' + sale_order.id">{{sale_order.serial_no}}
                              <q-badge align="top" outline color="primary" class="q-ml-md" :label="sale_order.status"/>
                            </router-link>
                          </td>
                        </tr>
                      </template>
                      <tr>
                        <td>Type</td>
                        <td>{{model.type}}</td>
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
                      <td>Include Flood Cess ?: {{model.flood_cess_included == 'Yes' ? 'Yes':'No'}}</td>
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
                        {{props.row.product_id ? props.row.product.name : props.row.product_name}}
                        <q-badge v-if="!props.row.product_id" align="top" outline color="primary" label="New"/>
                        <q-badge v-if="props.row.converted_qty == props.row.qty" align="top" outline color="green-7" label="Converted"/>
                        <q-badge v-if="props.row.converted_qty > 0 && props.row.converted_qty != props.row.qty" align="top" outline color="orange-7" label="Partially Converted"/>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.product_id ? (props.row.qty * props.row.product.weight) : 0}}
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
                        {{currencySymbol}}{{props.row.rate}}
                      </q-td>
                      <q-td class="text-right" v-if="!model.rate_includes_gst">
                        {{currencySymbol}}{{props.row.taxable}}
                      </q-td>
                      <q-td class="text-right" v-if="!model.rate_includes_gst">
                        {{currencySymbol}}{{props.row.tax_amount}}
                      </q-td>
                      <q-td class="text-right">
                        {{currencySymbol}}{{props.row.total}}
                      </q-td>
                    </q-tr>
                    <q-tr v-show="props.expand" :props="props">
                      <q-td colspan="100%">
                        <div class="text-left">Use Mask: {{props.row.use_mask ? 'Yes' : 'No'}}, Mask Name: {{ props.row.mask_name }}</div>
                      </q-td>
                    </q-tr>
                  </template>
                  <template v-slot:top-right>
                    <q-input borderless dense debounce="300" v-model="search" placeholder="Search" clearable>
                      <template v-slot:append>
                        <q-icon name="search" />
                      </template>
                    </q-input>
                  </template>
                  <template v-slot:bottom-row>
                    <q-tr class="bg-blue-grey-3">
                      <td colspan="2"></td>
                      <td class="text-right">Total Weight:</td>
                      <td class="text-right">{{parseFloat(totalWeight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}gm</td>
                      <td class="text-right">Total Qty:</td>
                      <td class="text-right">{{parseFloat(totalQty)}}</td>
                      <td colspan="2" v-if="!model.rate_includes_gst"/>
                      <td class="text-right">Subtotal</td>
                      <td class="text-right">{{currencySymbol}}{{parseFloat(model.subtotal).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</td>
                    </q-tr>
                    <q-tr class="bg-blue-grey-1">
                      <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                      <q-td class="text-right">Discount</q-td>
                      <q-td class="text-right">-{{currencySymbol}}{{parseFloat(model.discount).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                    </q-tr>
                    <q-tr class="bg-blue-grey-1">
                      <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                      <q-td class="text-right">Freight</q-td>
                      <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.freight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                    </q-tr>
                    <q-tr class="bg-blue-grey-1" v-if="model.type === 'Export'">
                      <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                      <q-td class="text-right">Bank Charges</q-td>
                      <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.bank_charges).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                    </q-tr>
                    <q-tr class="bg-blue-grey-1" v-if="model.type === 'Export'">
                      <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                      <q-td class="text-right">Previous Balance</q-td>
                      <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.prev_balance).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                    </q-tr>
                    <q-tr class="bg-blue-grey-1">
                      <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                      <q-td class="text-right">Round</q-td>
                      <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.round).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                    </q-tr>
                    <q-tr class="bg-blue-grey-3">
                      <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                      <q-td class="text-right">Total</q-td>
                      <q-td class="text-right">{{currencySymbol}}{{parseFloat(model.total).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                    </q-tr>
                  </template>
                </q-table>
              </div>
            </div>
            <div class="row" v-if="model.instructions">
              <div class="col q-px-md">
                <div class="text-h6">Instructions</div>
                <div v-html="model.instructions"></div>
              </div>
            </div>
            <div class="row" v-if="model.terms">
              <div class="col q-px-md">
                <div class="text-h6">Terms</div>
                <div v-html="model.terms"></div>
              </div>
            </div>
            <div class="row" v-if="model.remarks">
              <div class="col q-px-md">
                <div class="text-h6">Remarks</div>
                <div v-html="model.remarks"></div>
              </div>
            </div>
          </q-tab-panel>
          <q-tab-panel name="payments">
            <q-card>
              <q-markup-table>
                <thead>
                  <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Payment Via</th>
                    <th class="text-left">Transaction ID</th>
                    <th class="text-right">Amount</th>
                    <th class="text-right">Created At</th>
                    <th class="text-right"></th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="model.payments && model.payments.length > 0">
                    <tr v-for="(item,ind) in model.payments" :key="ind">
                      <td class="text-left">{{ind + 1}}</td>
                      <td class="text-left">{{item.payment_via}}</td>
                      <td class="text-left">{{item.transaction_id}}</td>
                      <td class="text-right">{{item.amount}}</td>
                      <td class="text-right">{{getLocaleString(new Date(item.id * 1000))}}</td>
                      <td class="text-right"><q-btn flat round icon="delete" @click="deletePayment(ind)"/></td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="100%" class="text-center text-caption">No Data Available</td>
                  </tr>
                </tbody>
              </q-markup-table>
              <q-separator/>
              <q-card-section>
                <q-btn color="primary" label="Add Payment" @click="openPaymentDialog"/>
              </q-card-section>
            </q-card>
          </q-tab-panel>

          <q-tab-panel name="documents">
            <q-card>
              <q-markup-table>
                <thead>
                  <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Document</th>
                    <th class="text-right"></th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="model.documents && model.documents.length > 0">
                    <tr v-for="(item,ind) in model.documents" :key="ind">
                      <td class="text-left">{{ind + 1}}</td>
                      <td class="text-left">
                        <a :href="$store.getters.baseURL + '/storage/' + item.link" target="_blank">{{item.name}}</a>
                      </td>
                      <td class="text-right"><q-btn flat round icon="delete" @click="deleteDocument(ind)"/></td>
                    </tr>
                  </template>
                  <tr v-else>
                    <td colspan="100%" class="text-center text-caption">No Data Available</td>
                  </tr>
                </tbody>
              </q-markup-table>
              <q-card-section>
                <q-form ref="docForm">
                  <div class="row q-col-gutter-md">
                    <div class="col-6">
                      <q-input filled dense :rules="[v => !!v || 'Required']" label="Document Name" v-model="doc_name"/>
                    </div>
                    <div class="col-4">
                      <q-file outlined v-model="doc_file" filled dense ref="docfile" :rules="[v => !!v || 'Required']">
                        <template v-slot:prepend>
                          <q-icon name="attach_file" />
                        </template>
                      </q-file>
                    </div>
                    <div class="col-2">
                      <q-btn color="primary" label="Upload" @click="uploadDoc"/>
                    </div>
                  </div>
                </q-form>
              </q-card-section>
            </q-card>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>
    <page-toolbar :page-title="''" :buttons="toolbarButtons" v-on:createso="createSaleOrder()" v-on:export="exportFn" class="q-mt-md"/>
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
    v-model="convertDialog"
    persistent
    maximized
    transition-show="slide-up"
    transition-hide="slide-down"
    >
      <convert-dialog :items="model.items" />
    </q-dialog>
    <q-dialog v-model="paymentDialog" persistent>
      <q-card style="width:500px;">
        <q-card-section>
          <div class="text-h6">Add Payment Details</div>
        </q-card-section>
        <q-separator/>
        <q-card-section>
          <q-form ref="paymentForm">
            <div class="row q-col-gutter-md">
              <div class="col-6 text-right q-mt-sm">Payment Via</div>
              <div class="col-6">
                <q-select :options="['Cash','Bank','Cheque','UPI','Card']" dense filled
                :rules="[v => !!v || 'Required']"
                v-model="paymentModel.payment_via"
                ref="payment_via"/>
              </div>
              <div class="col-6 text-right q-mt-sm">Currency</div>
              <div class="col-6">
                <q-select :options="currencyOptions"
                ref="currency"
                v-model="paymentModel.currency"
                filled dense
                emit-value
                map-options
                :rules="[(v) => !!v || 'Required']"/>
              </div>
              <div class="col-6 text-right q-mt-sm">Transaction ID / Ref ID</div>
              <div class="col-6">
                <q-input filled dense v-model="paymentModel.transaction_id"/>
              </div>
              <div class="col-6 text-right q-mt-sm">Amount</div>
              <div class="col-6">
                <q-input filled dense ref="amount" class="input-right" v-model.number="paymentModel.amount"
                :rules="[(v) => !!v || 'Required']"/>
              </div>
            </div>
          </q-form>
        </q-card-section>
        <q-card-actions>
          <q-btn label="Submit" color="primary" @click="addPayment"/>
          <q-btn flat label="Cancel" color="secondary" @click="closePaymentDialog"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
import ConvertDialog from './ConvertDialog.vue'
export default {
  name: 'QuotationsView',
  components: {
    PageToolbar,
    Breadcrumbs,
    ConvertDialog
  },
  data () {
    return {
      doc_name: null,
      doc_file: null,
      paymentDialog: false,
      currencyOptions: [
        {
          label: 'US Dollar',
          symbol: '$',
          value: 'USD'
        },
        {
          label: 'Indian Rupee',
          symbol: '₹',
          value: 'INR'
        }
      ],
      paymentModel: {
        payment_via: null,
        currency: 'INR',
        transaction_id: null,
        amount: 0.00
      },
      tab: 'details',
      model: {
        inco_term: null,
        bank: null,
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
        status: null,
        remarks: '',
        instructions: '',
        terms: '',
        valid_until: null,
        fob_point: null,
        po_number: null,
        warehouse: {
          name: null
        },
        pricelist: {
          name: null
        },
        rate_includes_gst: null,
        flood_cess_included: null,
        items: [],
        subtotal: null,
        discount: null,
        freight: null,
        round: null,
        total: null,
        search: null,
        table_loading: false,
        ship_date: null,
        ship_via: null,
        contact_person: null,
        contact_phone: null,
        contact_email: null,
        sale_orders: [],
        bank_charges: null,
        prev_balance: null,
        currency: 'INR',
        payments: [],
        documents: []
      },
      exportDialog: false,
      export_mode: null,
      export_include_gst_column: true,
      export_use_mask_name: false,
      export_include_hsn: false,
      convertDialog: false,
      billingAddress: null,
      search: null,
      table_loading: false
    }
  },
  mounted () {
    this.$q.loading.show()
    this.$axios.get('quotations/' + this.$route.params.id).then((res) => {
      this.model = res.data
    }).finally(() => {
      this.loadAddress()
      this.$q.loading.hide()
    })
  },
  methods: {
    uploadDoc () {
      this.$refs.docForm.validate().then((success) => {
        if (success) {
          this.$q.loading.show()
          const fD = new FormData()
          fD.append('file', this.doc_file)
          fD.append('name', this.doc_name)
          this.$axios.post('quotations/' + this.$route.params.id + '/documents', fD, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }).then((res) => {
            if (res.data.message === 'success') {
              this.model.documents = res.data.documents
              this.doc_name = null
              this.doc_file = null
            }
          }).finally(() => this.$q.loading.hide())
        } else {
          this.$q.notify({
            message: 'Error in data submitted. Please check',
            type: 'negative'
          })
        }
      })
    },
    deleteDocument (ind) {
      this.$q.dialog({
        title: 'Confirmation',
        message: 'Do you want to continue?',
        persistent: true,
        cancel: true
      }).onOk(() => {
        this.$q.loading.show()
        this.$axios.get('quotations/' + this.$route.params.id + '/delete_document/' + this.model.documents[ind].id).then((res) => {
          if (res.data.message === 'success') {
            this.model.documents = res.data.documents
          }
        }).finally(() => {
          this.$q.loading.hide()
        })
      })
    },
    deletePayment (ind) {
      this.$q.dialog({
        title: 'Confirmation',
        message: 'Do you want to continue?',
        persistent: true,
        cancel: true
      }).onOk(() => {
        this.$q.loading.show()
        this.$axios.get('quotations/' + this.$route.params.id + '/delete_payment/' + this.model.payments[ind].id).then((res) => {
          if (res.data.message === 'success') {
            this.model.payments = res.data.payments
          }
        }).finally(() => {
          this.$q.loading.hide()
        })
      })
    },
    addPayment () {
      this.$refs.paymentForm.validate().then((success) => {
        if (success) {
          this.$q.loading.show()
          this.$axios.post('quotations/' + this.$route.params.id + '/add_payment', this.paymentModel).then((res) => {
            if (res.data.message === 'success') {
              this.model.payments = res.data.payments
              this.closePaymentDialog()
            }
          }).finally(() => this.$q.loading.hide())
        } else {
          this.$q.notify({
            message: 'Error in data submitted. Please check',
            type: 'negative'
          })
        }
      })
    },
    openPaymentDialog () {
      this.paymentDialog = true
    },
    closePaymentDialog () {
      this.paymentModel = {
        payment_via: null,
        currency: 'INR',
        transaction_id: 'null',
        amount: 0.00
      }
      this.paymentDialog = false
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
    },
    exportFn () {
      this.exportDialog = true
    },
    performExport () {
      this.$q.loading.show()
      let filename = 'Quotation_' + this.model.customer.name.replace(/[^a-zA-Z0-9]/g, '_') + '_' + this.model.serial_no.replace(/\//g, '')
      if (this.export_mode === 'pdf') {
        filename += '.pdf'
      }
      if (this.export_mode === 'excel') {
        filename += '.xlsx'
      }
      this.$axios({
        url: 'quotations/export/' + this.$route.params.id,
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
    },
    createSaleOrder () {
      if (this.model.customer.is_lead) {
        this.$q.dialog({ title: 'Alert', message: 'You cannot create sale order for a lead. Please convert it to customer!!', dark: true })
      } else if (this.model.customer.status !== 'Active' && this.model.customer.status !== 'Approved') {
        this.$q.dialog({
          title: 'Attention',
          message: 'The customer selected is not active or approved by CRO. Please contact CRO.'
        })
      } else {
        let flag = false
        this.model.items.forEach((item) => {
          if (item.converted_qty > 0 || !item.product_id) {
            flag = true
          }
        })
        if (!flag) {
          this.$q.dialog({
            title: 'Select conversion mode',
            options: {
              type: 'radio',
              model: 'conversion_mode',
              items: [
                { label: 'Partial Conversion', value: 'partial' },
                { label: 'Full Conversion', value: 'full' }
              ]
            },
            cancel: true,
            persistent: true
          }).onOk((data) => {
            if (data === 'partial') {
              this.convertDialog = true
            } else if (data === 'full') {
              this.convert()
            }
          })
        } else {
          this.convertDialog = true
        }
      }
    },
    convert () {
      const newProducts = []
      this.model.items.forEach((item, i) => {
        if (item.product_id === 0) {
          newProducts.push(item)
        }
      })
      if (newProducts.length > 0) {
        this.$q.dialog({ title: 'Alert', message: 'There are new products in the items list. Please replace them with products in store!!', dark: true })
      } else {
        this.$q.loading.show()
        this.$axios.get('quotations/convert/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            this.$router.push({ path: '/sale_orders/view/' + res.data.id })
          }
        }).catch((error) => {
          if (error.response.status === 422) {
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
              style: { width: '50%', maxWidth: '100%' },
              title: 'Cannot convert this Quotation',
              message: str,
              html: true
            })
          }
        }).finally(() => {
          this.$q.loading.hide()
        })
      }
    },
    loadAddress () {
      if (this.model.customer) {
        if (this.model.customer.name !== 'OTC') {
          this.$q.loading.show()
          this.billingAddress = null
          this.$axios.get('customer_addresses/' + this.model.customer.id).then((res) => {
            if (res.data.representatives.length === 1) {
              this.model.representative_id = res.data.representatives[0].id
            }
            res.data.addresses.forEach((addr) => {
              if (addr.tag_name === 'billing') {
                this.billingAddress = this.addressFormat(addr)
              }
            })
          }).finally(() => {
            this.$q.loading.hide()
          })
        }
      }
    },
    addressFormat (addr) {
      let str = ''
      str += addr.line_1 + ', '
      if (addr.line_2) { str += addr.line_2 + ', ' }

      if (addr.district) {
        str += addr.district + ', '
      }
      str += addr.state + ', '
      if (addr.pin) { str += 'PIN: ' + addr.pin + ', ' }
      str += addr.country + ', '
      str += 'Ph: '
      addr.phones.forEach((item) => {
        str += '(' + item.country_code + ')' + item.content + ', '
      })
      str = str.substr(0, str.length - 2)
      return str
    }
  },
  computed: {
    currencySymbol () {
      if (this.model.currency === 'INR') {
        return '₹'
      }
      return '$'
    },
    totalWeight () {
      return this.$_.sumBy(this.model.items, (v) => parseFloat(v.product ? v.product.weight * v.qty : 0))
    },
    totalQty () {
      return this.$_.sumBy(this.model.items, (v) => parseFloat(v.qty))
    },
    toolbarButtons () {
      const arr = []
      arr.push({
        label: 'Download',
        id: 'export',
        type: 'button',
        color: 'teal',
        icon: 'get_app',
        flat: true
      })
      if (this.model.status === 'Draft' || this.model.status === 'Partially Converted') {
        arr.push({
          label: 'Convert to Sale Order',
          id: 'createso',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
        arr.push({
          label: 'Edit',
          id: 'edit',
          type: 'a',
          link: '/quotations/edit/' + this.$route.params.id,
          color: 'teal',
          icon: 'edit'
        })
      }
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
          name: 'product_name',
          field: 'product_name',
          label: 'Product',
          sortable: true,
          align: 'left'
        },
        {
          name: 'weight',
          field: 'weight',
          label: 'Weight',
          sortable: true,
          align: 'right'
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
        { label: 'Quotations', link: '/quotations' },
        { label: this.model.serial_no }
      ]
      return arr
    },
    getCustomerLink () {
      if (!this.model.customer.is_lead) {
        return '/customers/view/' + this.model.customer.id
      } else {
        return '/leads/view/' + this.model.customer.id
      }
    }
  }
}
</script>
