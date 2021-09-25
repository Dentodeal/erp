<template>
    <q-page>
        <page-toolbar
            :buttons="toolbarButtons"
            v-on:activate="activate()"
            />
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <div class="row q-col-gutter-md q-pa-md ">
                    <div class="col-12 col-md-6">
                        <q-markup-table wrap-cells separator="cell">
                            <tbody>
                                <tr>
                                    <td>Serial No</td>
                                    <td>{{serial_no}}</td>
                                </tr>
                                <tr v-if="sale_order.customer">
                                    <td>Customer</td>
                                    <td><router-link :to="'/customers/view/'+sale_order.customer.id">{{sale_order.customer.name}}</router-link></td>
                                </tr>
                                <tr v-if="sale_order.warehouse">
                                    <td>Warehouse</td>
                                    <td>{{sale_order.warehouse.name}}</td>
                                </tr>
                                <tr>
                                    <td>Sale Order</td>
                                    <td><router-link :to="'/sale_orders/view/'+sale_order.id">{{sale_order.serial_no}}</router-link></td>
                                </tr>
                            </tbody>
                        </q-markup-table>
                    </div>
                    <div class="col-12 col-md-6">
                        <q-markup-table wrap-cells separator="cell" >
                            <tbody>
                                <tr>
                                    <td>Status</td>
                                    <td>{{status}}</td>
                                </tr>
                                <tr>
                                    <td>Created By</td>
                                    <td>{{created_by.name}}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{getLocaleString(created_at)}}</td>
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
                                        <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.name)"></q-btn>
                                    </q-td>
                                    <q-td class="text-right">
                                        <div v-if="props.row.gst">
                                            {{props.row.gst}}%
                                        </div>
                                    </q-td>
                                    <q-td class="text-right">
                                        {{props.row.qty.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                                        <q-popup-edit v-model="props.row.qty"
                                            buttons
                                            label-set="Save"
                                            label-cancel="Close"
                                            :validate="() => $refs.qty_edit.validate() ? true : false"
                                            @hide="updateRow(props.rowIndex);props.row.qty = parseInt(props.row.qty);"
                                            >
                                            <q-input
                                                v-model="props.row.qty"
                                                dense
                                                autofocus
                                                @focus="$refs.qty_edit.select()"
                                                class="input-right"
                                                ref="qty_edit"
                                                :rules="[v => !isNaN(v) || 'Invalid']"
                                            />
                                        </q-popup-edit>
                                    </q-td>
                                    <q-td class="text-right">
                                            {{props.row.is_free ? 'Yes':'No'}}
                                    </q-td>
                                    <q-td class="text-right">
                                        {{props.row.rate.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                                    </q-td>
                                    <q-td class="text-right" v-if="sale_order && !sale_order.rate_includes_gst">
                                        {{props.row.taxable.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                                    </q-td>
                                    <q-td class="text-right" v-if="sale_order && !sale_order.rate_includes_gst">
                                        {{props.row.tax_amount.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                                    </q-td>
                                    <q-td class="text-right">
                                        {{props.row.total}}
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
                                <q-td :colspan="sale_order && !sale_order.rate_includes_gst ? 7 : 5"/>
                                <q-td class="text-right">Subtotal</q-td>
                                <q-td class="text-right">{{subtotal.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                              </q-tr>
                              <q-tr class="bg-blue-grey-1">
                                <q-td :colspan="sale_order && !sale_order.rate_includes_gst ? 7 : 5"/>
                                <q-td class="text-right">Freight</q-td>
                                <q-td class="text-right">
                                  {{freight ? parseFloat(freight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): '0.00'}}
                                  <q-popup-edit v-model="freight"
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    :validate="() => $refs.freight_edit.validate() ? true : false"
                                    @hide="freight = parseFloat(freight).toFixed(2);updateTotal();"
                                    >
                                    <q-input
                                        v-model="freight"
                                        dense
                                        autofocus
                                        @focus="$refs.freight_edit.select()"
                                        class="input-right"
                                        ref="freight_edit"
                                        :rules="[v => !isNaN(v) || 'Invalid']"
                                    />
                                  </q-popup-edit>
                                </q-td>
                              </q-tr>
                              <q-tr class="bg-blue-grey-3">
                                <q-td :colspan="sale_order && !sale_order.rate_includes_gst ? 7 : 5"/>
                                <q-td class="text-right">Total</q-td>
                                <q-td class="text-right">{{total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
                              </q-tr>
                            </template>
                        </q-table>
                    </div>
                </div>
                <div class="row q-mt-sm">
                    <div class="col q-px-md">
                        <div class="text-h6">Remarks</div>
                        <q-editor v-model="remarks" rows="3"/>
                    </div>
                </div>
            </q-card>
        </div>
        <page-toolbar :page-title="''" :buttons="toolbarButtons"
            v-on:activate="activate()" class="q-mt-md"/>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SaleOrdersView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      serial_no: '',
      sale_order: {
        customer: null,
        warehouse: null
      },
      created_by: {
        name: null
      },
      created_at: null,
      status: null,
      remarks: '',
      items: [],
      subtotal: null,
      freight: null,
      total: null,
      search: null,
      table_loading: false,
      errorQty: false,
      errorMessageQty: ''
    }
  },
  mounted () {
    this.getDataFromApi()
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('invoice_sale_order') && this.status !== 'Active') {
        arr.push({
          label: 'Activate',
          id: 'activate',
          type: 'button',
          color: 'green-7',
          icon: 'check'
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
          name: 'name',
          field: 'name',
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
        }
      ]
      if (this.sale_order && !this.sale_order.rate_includes_gst) {
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
        { label: 'Sale Returns', link: '/sale_returns' },
        { label: this.serial_no }
      ]
      return arr
    }
  },
  watch: {

  },
  methods: {
    update () {
      this.$q.loading.show()
      this.$axios.post('sale_returns/' + this.$route.params.id, {
        _method: 'PUT',
        items: this.items,
        subtotal: this.subtotal,
        total: this.total,
        freight: this.freight
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({ type: 'positive', message: 'Updated Successfully' })
        }
      }).finally(() => {
        this.$q.loading.hide()
      })
    },
    getDataFromApi () {
      this.$q.loading.show()
      this.$axios.get('sale_returns/' + this.$route.params.id).then((res) => {
        this.serial_no = res.data.serial_no
        this.sale_order = res.data.sale_order
        this.created_by = res.data.created_by
        this.created_at = res.data.created_at
        this.status = res.data.status
        this.remarks = res.data.remarks
        this.items = res.data.items
        this.subtotal = res.data.subtotal
        this.freight = res.data.freight
        this.total = res.data.total
      }).finally(() => {
        this.$store.commit('setPageTitle', this.serial_no)
        this.$q.loading.hide()
      })
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
    },
    qtyEditValidation (val) {
      if (val && Number.isInteger(Number(val))) {
        this.errorQty = false
        this.errorMessageQty = ''
        return true
      } else {
        this.errorQty = true
        this.errorMessageQty = 'Invalid Entry'
        return false
      }
    },
    freightEditValidation (val) {
      if (val && Number.isInteger(Number(val))) {
        this.errorQty = false
        this.errorMessageQty = ''
        return true
      } else {
        this.errorQty = true
        this.errorMessageQty = 'Invalid Entry'
        return false
      }
    },
    updateRow (index) {
      const oldTotal = parseFloat(this.items[index].total)
      const qty = parseInt(this.items[index].qty)
      const rate = parseFloat(this.items[index].rate)
      const gst = parseFloat(this.items[index].gst)
      let total = 0
      if (!this.sale_order.rate_includes_gst) {
        let taxable = 0
        taxable = (rate * qty)
        this.items[index].taxable = taxable.toFixed(2)
        const taxAmount = taxable * (gst / 100)
        this.items[index].tax_amount = taxAmount.toFixed(2)
        total = taxable + taxAmount
        this.items[index].total = total.toFixed(2)
      } else {
        total = (rate * qty)
        this.items[index].total = total.toFixed(2)
      }
      const diff = total - oldTotal
      this.addtoSubtotal(diff)
      this.updateTotal()
    },
    addtoSubtotal (val) {
      this.subtotal = (parseFloat(this.subtotal) + parseFloat(val)).toFixed(2)
    },
    updateTotal () {
      const subtotal = parseFloat(this.subtotal)
      const freight = parseFloat(this.freight)
      let total = 0
      total = (subtotal + freight)
      this.total = Math.round(total).toFixed(2)
      this.update()
    },
    activate () {
      this.$axios.post('sale_returns/activate/' + this.$route.params.id, {
        remarks: this.remarks
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$router.push('/sale_returns')
          this.$q.notify({ type: 'positive', message: 'Sale Return Activated Successfully' })
        }
      })
    }
  }
}
</script>
