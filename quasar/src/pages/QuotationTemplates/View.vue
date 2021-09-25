<template>
    <q-page>
        <page-toolbar
            :buttons="toolbarButtons"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <div class="row q-col-gutter-md q-pa-md ">
                    <div class="col">
                        <q-markup-table wrap-cells separator="cell">
                            <tbody>
                                <tr>
                                    <td>Name</td>
                                    <td>{{model.name}}</td>
                                </tr>
                                <tr>
                                    <td>Customer/Lead</td>
                                    <td v-if="model.customer"><router-link :to="getCustomerLink">{{model.customer.name}}</router-link>
                                      <template v-if="billingAddress">
                                      <br>{{billingAddress}}
                                      </template>
                                    </td>
                                    <td v-else></td>
                                </tr>
                                <tr>
                                    <td>Warehouse</td>
                                    <td>{{model.warehouse ? model.warehouse.name : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Pricelist</td>
                                    <td>{{model.pricelist ? model.pricelist.name : ''}}</td>
                                </tr>
                                <tr>
                                    <td>FOB Point</td>
                                    <td>{{model.fob_point}}</td>
                                </tr>
                                <tr>
                                    <td>PO Number</td>
                                    <td>{{model.po_number}}</td>
                                </tr>
                                <tr>
                                    <td>Contact Person</td>
                                    <td>{{model.contact_person || (model.representative ? model.representative.name : '')}}</td>
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
                                    <td>{{model.created_by ? model.created_by.name : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Representative</td>
                                    <td>{{model.representative ? model.representative.name : ''}}</td>
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
                            {{props.row.rate}}
                          </q-td>
                          <q-td class="text-right" v-if="!model.rate_includes_gst">
                            {{props.row.taxable}}
                          </q-td>
                          <q-td class="text-right" v-if="!model.rate_includes_gst">
                            {{props.row.tax_amount}}
                          </q-td>
                          <q-td class="text-right">
                            {{props.row.total}}
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
                          <td class="text-right">{{parseFloat(model.subtotal).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</td>
                        </q-tr>
                        <q-tr class="bg-blue-grey-1">
                          <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                          <q-td class="text-right">Discount</q-td>
                          <q-td class="text-right">-{{parseFloat(model.discount).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                        </q-tr>
                        <q-tr class="bg-blue-grey-1">
                          <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                          <q-td class="text-right">Freight</q-td>
                          <q-td class="text-right">{{parseFloat(model.freight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                        </q-tr>
                        <q-tr class="bg-blue-grey-1" v-if="model.type === 'Export'">
                          <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                          <q-td class="text-right">Bank Charges</q-td>
                          <q-td class="text-right">{{parseFloat(model.bank_charges).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                        </q-tr>
                        <q-tr class="bg-blue-grey-1" v-if="model.type === 'Export'">
                          <q-td :colspan="!model.rate_includes_gst ? 8 : 6"/>
                          <q-td class="text-right">Previous Balance</q-td>
                          <q-td class="text-right">{{parseFloat(model.prev_balance).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
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
            </q-card>
        </div>
        <page-toolbar :page-title="''" :buttons="toolbarButtons" class="q-mt-md"/>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'QuotationsView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      model: {
        bank: null,
        name: null,
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
        prev_balance: null
      },
      billingAddress: null,
      search: null,
      table_loading: false
    }
  },
  mounted () {
    this.$q.loading.show()
    this.$axios.get('quotation_templates/' + this.$route.params.id).then((res) => {
      this.model = res.data
    }).finally(() => {
      this.loadAddress()
      this.$q.loading.hide()
    })
  },
  methods: {
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
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
    totalWeight () {
      return this.$_.sumBy(this.model.items, (v) => parseFloat(v.product ? v.product.weight * v.qty : 0))
    },
    totalQty () {
      return this.$_.sumBy(this.model.items, (v) => parseFloat(v.qty))
    },
    toolbarButtons () {
      const arr = [{
        label: 'Edit',
        id: 'edit',
        type: 'a',
        link: '/quotation_templates/edit/' + this.$route.params.id,
        color: 'teal',
        icon: 'edit'
      }]
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
        { label: 'Quotation Templates', link: '/quotation_templates' },
        { label: this.model.name }
      ]
      return arr
    },
    getCustomerLink () {
      if (this.model.customer) {
        if (!this.model.customer.is_lead) {
          return '/customers/view/' + this.model.customer.id
        } else {
          return '/leads/view/' + this.model.customer.id
        }
      }
      return ''
    }
  }
}
</script>
