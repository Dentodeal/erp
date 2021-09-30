<template>
    <q-page>
        <page-toolbar
            :buttons="toolbarButtons"
            v-on:save="saveFn()"
            v-on:sendToAdmin="sendToAdmin()"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
        <div class="row q-col-gutter-md">
            <div class="col-md-6 col-12 q-mt-md">
                <q-input outlined dense square label="Bill No." v-model="bill_number" ref="bill_number" :rules="[v=>!!v||'Required']"/>
            </div>
            <div class="col-md-3 col-12 q-mt-md">
                <q-input readonly dense outlined square label="Status" v-model="status"/>
            </div>
            <div class="col-md-3 col-12 q-mt-md">
                <q-input readonly dense outlined square label="GRN No" v-model="grn_serial_no"/>
            </div>
        </div>
        <div class="row q-col-gutter-md">
            <div class="col-md-6 col-12 q-mt-md">
                <q-input readonly :value="supplier.name" outlined square dense label="Supplier"/>
            </div>
            <div class="col-md-6 col-12 q-mt-md">
                <div class="row q-col-gutter-md">
                    <div class="col">
                        <q-input
                          filled dense
                          v-model="bill_date"
                          label="Bill date"
                          hint="Click on calender icon to enter date" ref="bill_date"
                          :rules="[v=>!!v||'Required', dateValidation]"
                          lazy-rules="ondemand"
                          @input="$refs.bill_date.resetValidation()"
                          mask="####-##-##">
                            <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                                    <q-date v-model="bill_date" >
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
                                    </div>
                                    </q-date>
                                </q-popup-proxy>
                                </q-icon>
                            </template>
                        </q-input>
                    </div>
                    <div class="col">
                        <q-input filled readonly dense v-model="delivery_date" label="Delivery date">
                        </q-input>
                    </div>
                </div>
            </div>
        </div>
        <div class="row q-col-gutter-md">
            <div class="col-md-6 col-12 q-mt-md">
                <q-input readonly dense outlined square :value="warehouse.name" label="Warehouse"/>
            </div>
            <div class="col-md-6 col-12 q-mt-md">
                <q-select
                    v-model="created_by_id"
                    label="Created By"
                    emit-value
                    map-options
                    option-value="id"
                    option-label="name"
                    outlined square dense
                    ref="created_by_id"
                    :rules="[v=>!!v||'Required']"
                    :options="createdByOptions"/>
            </div>
        </div>
        <div class="row q-col-gutter-md q-mb-md">
          <div class="col-3">
            <q-toggle
              :label="`Row discount mode: ${row_discount_mode}`"
              color="green"
              false-value="fixed"
              true-value="percentage"
              v-model="row_discount_mode"
            />
          </div>
          <div class="col-3">
            <q-select
              outlined dense square
              :options="[
              {label:'Equal', value: 'equal', description: 'Freight will be equally divided by moving the line'},
              {label:'Weight', value: 'weight', description: 'Freight will be divided based on weight'}
              ]"
              v-model="freight_split_method"
              label="Freight Split Method"
              emit-value>
              <template v-slot:option="scope">
                <q-item
                  v-bind="scope.itemProps"
                  v-on="scope.itemEvents"
                >
                  <q-item-section>
                    <q-item-label v-html="scope.opt.label" />
                    <q-item-label caption>{{ scope.opt.description }}</q-item-label>
                  </q-item-section>
                </q-item>
              </template>
            </q-select>
          </div>
        </div>
        <div class="row">
            <div class="col">
                <q-table
                    :columns="columns"
                    title="Items"
                    :data="items"
                    row-key="product_id"
                    wrap-cells
                    :loading="table_loading"
                    :rows-per-page-options="[0]"
                    :filter="search"
                    >
                    <template v-slot:bottom-row>
                      <q-tr class="bg-blue-grey-1">
                        <q-td colspan="6">
                        </q-td>
                        <q-td class="text-right">Total</q-td>
                        <q-td class="text-right">{{tax_row_total.toFixed(2)}}</q-td>
                        <q-td class="text-right">{{taxable_total.toFixed(2)}}</q-td>
                        <q-td></q-td>
                      </q-tr>

                    </template>
                    <template v-slot:header="props">
                        <q-tr :props="props">
                            <q-th/>
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
                                <q-btn size="md" color="grey-7" round dense flat @click="props.expand = !props.expand" :icon="props.expand ? 'arrow_drop_down' : 'arrow_right'" />
                            </q-td>
                            <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                            <q-td>
                                {{props.row.product.name}}
                                <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.product.name)">
                                  <q-tooltip>
                                    Copy Name
                                  </q-tooltip>
                                </q-btn>
                                <q-btn icon="file_copy" flat round @click="duplicateRow(props.row)">
                                  <q-tooltip>
                                    Duplicate Row
                                  </q-tooltip>
                                </q-btn>
                            </q-td>
                            <q-td class="text-right">
                                {{props.row.gst}}%
                                <q-popup-edit v-model="props.row.gst"
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    @hide="updateRow(props.rowIndex)"
                                    >
                                    <q-select
                                        v-model="props.row.gst"
                                        :options="gstOptions"
                                        map-options
                                        emit-value
                                        dense autofocus/>
                                </q-popup-edit>
                            </q-td>
                            <q-td class="text-right">
                                {{props.row.qty}}
                                <q-popup-edit v-model="props.row.qty"
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    :validate="v => qtyValidation(v) === true ? true:false"
                                    @hide="updateRow(props.rowIndex);props.row.qty = parseInt(props.row.qty)"
                                    @show="$refs.qty_edit.select()"
                                    >
                                    <q-input
                                        @input="$refs.qty_edit.resetValidation()"
                                        ref="qty_edit"
                                        v-model="props.row.qty"
                                        :rules="[qtyValidation]"
                                        dense
                                        autofocus
                                    />
                                </q-popup-edit>
                            </q-td>
                            <q-td class="text-right">
                                {{props.row.rate}}
                                <q-popup-edit  v-if="!props.row.is_free" v-model="props.row.rate"
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    :validate="v => rateEditValidation(v) === true ? true: false"
                                    @hide="updateRow(props.rowIndex);props.row.rate = parseFloat(props.row.rate).toFixed(2)"
                                    @show="$refs.rate_edit.select()"
                                    >
                                    <q-input
                                        @input="$refs.rate_edit.resetValidation()"
                                        v-model="props.row.rate"
                                        :rules="[rateEditValidation]"
                                        ref="rate_edit"
                                        dense
                                        autofocus
                                    />
                                </q-popup-edit>
                            </q-td>
                            <q-td class="text-right">
                                {{props.row.discount}}<span v-if="row_discount_mode == 'percentage'">%</span>
                                <q-popup-edit  v-if="!props.row.is_free" v-model="props.row.discount"
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    :validate="discountEditValidation"
                                    @hide="updateRow(props.rowIndex)"
                                    >
                                    <q-input
                                        @input="discountEditValidation"
                                        v-model="props.row.discount"
                                        :error="errorDiscount"
                                        :error-message="errorMessageDiscount"
                                        dense
                                        autofocus
                                    />
                                </q-popup-edit>
                            </q-td>
                            <q-td class="text-right">
                                {{props.row.tax_amount}}
                            </q-td>
                            <q-td class="text-right">
                                {{props.row.taxable}}
                            </q-td>

                            <q-td class="text-right">
                                <q-btn round icon="delete" flat color="black" @click="deleteFn(props.rowIndex)"/>
                            </q-td>
                        </q-tr>
                        <q-tr v-show="props.expand" :props="props"  class="bg-grey-4">
                            <q-td colspan="2"></q-td>
                            <q-td class="" colspan="2">
                                Avoid Costing: {{props.row.local ? 'Yes':'No'}}
                                <q-popup-edit
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    v-model="props.row.local"
                                    :validate="weightEditValidation">
                                    <q-select v-model="props.row.local"
                                        :options="[{ label:'Yes',value:1}, { label:'No',value:0}]"
                                        emit-value
                                        map-options
                                        dense
                                        autofocus/>
                                </q-popup-edit>
                            </q-td>
                            <q-td class="" colspan="2">
                                Is free: {{props.row.is_free ? 'Yes':'No'}}
                            </q-td>
                            <q-td class="text-left" colspan="2">
                                Weight: {{props.row.weight}}
                                <q-popup-edit
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    v-model="props.row.weight"
                                    :validate="weightEditValidation">
                                    <q-input v-model="props.row.weight"
                                        :error="errorWeight"
                                        :error-message="errorMessageWeight"
                                        dense
                                        autofocus/>
                                </q-popup-edit>
                            </q-td>
                            <q-td class="text-left" colspan="2">
                                MRP: {{props.row.mrp}}
                                <q-popup-edit
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close"
                                    v-model="props.row.mrp"
                                    :validate="weightEditValidation">
                                    <q-input v-model="props.row.mrp"
                                        :error="errorWeight"
                                        :error-message="errorMessageWeight"
                                        dense
                                        autofocus/>
                                </q-popup-edit>
                            </q-td>
                            <q-td class="text-left" colspan="2">
                                HSN: {{props.row.hsn}}
                                <q-popup-edit
                                    v-model="props.row.hsn"
                                    buttons
                                    label-set="Save"
                                    label-cancel="Close">
                                    <q-input v-model="props.row.hsn" dense autofocus />
                                </q-popup-edit>
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
                    <template v-slot:body-cell-actions="props">
                        <q-td :props="props">

                        </q-td>
                    </template>
                </q-table>
            </div>
        </div>
        <div class="row q-col-gutter-xs q-mt-md">
            <div class="col-4">
                <q-select
                    clearable
                    tabindex="1"
                    outlined square dense
                    label="Product"
                    ref="product"
                    :rules="[v=>!!v||'Required']"
                    lazy-rules="ondemand"
                    :options="productOptions"
                    option-value="id"
                    option-label="name"
                    @filter="productFilter"
                    use-input
                    fill-input
                    hide-selected
                    v-model="product"
                    >
                    <template v-slot:no-option>
                        <q-item>
                            <q-item-section class="text-grey">
                            No results
                            </q-item-section>
                        </q-item>
                    </template>
                </q-select>
            </div>
            <div class="col">
                <q-checkbox v-model="local" class="q-mt-xs" size="xs" label="Avoid Costing?" tabindex="-1"/>
            </div>
            <div class="col">
                <q-input tabindex="2" label="Weight per item" v-model="row_weight" square outlined dense hint="in grams"  @focus="$event.target.select()" ref="row_weight"  :rules="[v=>!!v||'Required',v=> Number(v) > 0||'Invalid']" @blur="()=>{row_weight = row_weight ? parseFloat(row_weight).toFixed(2):null}" lazy-rules="ondemand"/>
            </div>
            <div class="col">
                <q-input label="MRP" tabindex="3" v-model="mrp" square outlined dense @focus="$event.target.select()" ref="mrp"  :rules="[v=> !isNaN(v)||'Invalid']" @blur="()=>{mrp = mrp ? parseFloat(mrp).toFixed(2):null}" lazy-rules="ondemand"/>
            </div>
            <div class="col">
                <q-input label="HSN" tabindex="4" v-model="hsn" square outlined dense/>
            </div>
        </div>
        <div class="row q-col-gutter-md q-mt-sm">
            <div class="col">
                <q-select
                    tabindex="5"
                    outlined square dense
                    v-model="gst"
                    ref="gst"
                    :rules="[v=>!!v||'Required']"
                    lazy-rules="ondemand"
                    :options="gstOptions"
                    label="GST"
                    map-options
                    emit-value/>
            </div>
            <div class="col">
                <q-input tabindex="6" outlined square dense v-model="qty" label="Qty" class="input-right"
                  @focus="$event.target.select()"
                  ref="qty"
                  :rules="[qtyValidation]" lazy-rules="ondemand"
                  @blur="()=>{qty = qty ? parseInt(qty).toString():null}"/>
            </div>
            <div class="col">
                <q-input tabindex="7" outlined :readonly="is_free" square dense v-model="rate" label="Rate" @focus="$event.target.select()" @blur="()=>{rate = rate ? parseFloat(rate).toFixed(2):null}"  ref="rate"  :rules="[v=>!!v||'Required',v=>!isNaN(v)||'Invalid',rateValidation]" lazy-rules="ondemand"/>
            </div>
            <div class="">
                <q-checkbox v-model="is_free" class="q-mt-xs" label="Free?" size="xs"/>
            </div>
            <div class="col">
                <q-input outlined square dense
                    tabindex="8"
                    v-model="row_discount"
                    label="Discount"
                    @focus="$event.target.select()"
                    @blur="()=>{row_discount = row_discount ? parseFloat(row_discount).toFixed(2):null}"
                    ref="row_discount" />
            </div>
            <div class="col">
                <q-input outlined square dense v-model="row_taxable" readonly label="Taxable"/>
            </div>
            <div class="col">
                <q-input outlined square dense v-model="row_tax_amount" readonly label="Tax Amount"/>
            </div>
            <div class="">
                <q-btn icon="add" tabindex="9" round flat color="teal" @click="addRow()"/>
            </div>
        </div>
        <div class="row q-mt-xs">
            <div class="col-12 col-md-10 q-px-md">
            </div>
            <div class="col-12 col-md-2">
                <div class="column">
                    <div class="col">
                      <q-input tabindex="11" label="Discount on Taxable" class="input-right" dense outlined square v-model="taxable_discount" ref="taxable_discount" :rules="[v=>!isNaN(v)||'Invalid']" @focus="$refs.taxable_discount.select()">
                        <template v-slot:before>
                            <q-icon name="remove" />
                          </template>
                      </q-input>
                    </div>
                    <div class="col">
                      <q-input label="Items Tax" class="input-right q-mb-md" dense outlined square readonly v-model="taxTotal"/>
                    </div>
                    <div class="col">
                      <q-input tabindex="12" label="Bill Freight Tax" class="input-right" dense outlined square v-model="bill_freight_gst" ref="bill_freight_gst" :rules="[v=>!isNaN(v)||'Invalid']" @focus="$refs.bill_freight_gst.select()"/>
                    </div>
                    <div class="col">
                      <q-input  label="Subtotal (incl. GST)" class="input-right q-mb-md" dense outlined square readonly v-model="subtotal"/>
                    </div>
                    <div class="col">
                      <q-input tabindex="13" label="Discount on Subtotal" class="input-right" dense outlined square v-model="discount_subtotal" ref="discount_subtotal" :rules="[v=>!isNaN(v)||'Invalid']"  @focus="$refs.discount_subtotal.select()">
                          <template v-slot:before>
                            <q-icon name="remove" />
                          </template>
                      </q-input>
                    </div>
                    <div class="col">
                      <q-input tabindex="13" label="Bill Freight" class="input-right" dense outlined square v-model="bill_freight" ref="bill_freight" :rules="[v=>!isNaN(v)||'Invalid']"  @focus="$refs.bill_freight.select()"/>
                    </div>
                    <div class="col">
                        <q-input label="Round" tabindex="14" class="input-right" dense outlined square v-model="round" ref="round" :rules="[v=>!isNaN(v)||'Invalid']"  @focus="$refs.round.select()"/>
                    </div>
                    <div class="col">
                        <q-input label="Bill Total" class="input-right q-mb-md" dense outlined readonly square v-model="bill_total"/>
                    </div>
                    <div class="col">
                        <q-input label="External Freight" tabindex="14" class="input-right" dense outlined square v-model="external_freight" ref="external_freight" :rules="[v=>!isNaN(v)||'Invalid']"  @focus="$refs.external_freight.select()"/>
                    </div>
                    <div class="col">
                        <q-input label="Grand Total" class="input-right" dense outlined square readonly v-model="grand_total"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="text-subtitle1 q-mt-md">Remarks</div>
                <q-editor v-model="remarks" min-height="5rem"/>
            </div>
        </div>
        </div>
        <page-toolbar :page-title="''" :buttons="toolbarButtons"
            v-on:save="saveFn()"
            v-on:sendToAdmin="sendToAdmin()" class="q-mt-md"/>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'PurchasesCreate',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      grn_id: null,
      grn_serial_no: null,
      bill_number: null,
      supplier: {
        name: null
      },
      warehouse: {
        name: null
      },
      items: [],
      round: 0,
      discount_subtotal: 0,
      taxable_discount: 0,
      discount: 0,
      bill_freight: 0,
      bill_freight_includes_gst: 'No',
      bill_freight_gst: 0,
      external_freight: 0,
      status: 'Draft',
      bill_date: null,
      delivery_date: null,
      products: [],
      productOptions: [],
      gstOptions: [],
      product: null,
      gst: null,
      qty: null,
      rate: null,
      mrp: null,
      row_discount: null,
      row_discount_mode: 'percentage',
      row_taxable: null,
      row_tax_amount: null,
      row_weight: null,
      is_free: false,
      local: false,
      hsn: null,
      created_by_id: null,
      createdByOptions: [],
      taxable_total: 0,
      tax_row_total: 0,
      tax_total: 0,
      columns: [
        {
          name: 'sl_no',
          field: 'sl_no',
          label: '#',
          sortable: false
        },
        {
          name: 'name',
          field: 'name',
          label: 'Product',
          sortable: false,
          align: 'left'
        },
        {
          name: 'gst',
          field: 'gst',
          label: 'GST',
          sortable: false
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: false
        },
        {
          name: 'rate',
          field: 'rate',
          label: 'Rate',
          sortable: false
        },
        {
          name: 'discount',
          field: 'discount',
          label: 'Disc.',
          sortable: false
        },
        {
          name: 'tax_amount',
          field: 'tax_amount',
          label: 'Tax Amt',
          sortable: false
        },
        {
          name: 'taxable',
          field: 'taxable',
          label: 'Taxable',
          sortable: false
        },

        {
          name: 'actions',
          field: 'actions',
          label: '',
          sortable: false
        }
      ],
      table_loading: false,
      search: null,
      prodLoading: false,
      errorQty: false,
      errorMessageQty: '',
      errorRate: false,
      errorMessageRate: '',
      errorDiscount: false,
      errorMessageDiscount: '',
      errorWeight: false,
      errorMessageWeight: '',
      remarks: '',
      freight_split_method: 'weight'
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_purchase') || this.$store.getters.hasPermissionTo('update_purchase')) {
        arr.push({
          label: 'Send to Admin',
          id: 'sendToAdmin',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
        arr.push({
          label: 'Save as Draft',
          id: 'save',
          type: 'button',
          color: 'teal',
          icon: 'save'
        })
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Purchases', link: '/purchases' },
        { label: this.$route.params.id ? this.bill_number : 'Create', link: '/purchases/view/' + this.$route.params.id }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
      }
      return arr
    },
    taxTotal () {
      if (parseFloat(this.taxable_discount) > 0) {
        const disc = parseFloat(this.taxable_discount) / parseFloat(this.taxable_total)
        return this.$_.sumBy(this.items, (v) => (parseFloat(v.taxable) * (1 - disc)) * parseFloat(v.gst) / 100).toFixed(2)
      }
      return this.tax_row_total
    },
    bill_total () {
      return (parseFloat(this.subtotal) - parseFloat(this.discount_subtotal) + parseFloat(this.bill_freight) + parseFloat(this.round)).toFixed(2)
    },
    grand_total () {
      return (parseFloat(this.bill_total) + parseFloat(this.external_freight)).toFixed(2)
    },
    subtotal () {
      return (parseFloat(this.taxable_total) - parseFloat(this.taxable_discount) + parseFloat(this.taxTotal) + parseFloat(this.bill_freight_gst)).toFixed(2)
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('create_purchase')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      const userPermission = ['create_purchase', 'update_purchase']
      Promise.all([
        this.$axios.get('gst_options').then((res) => {
          this.gstOptions = res.data
        }),
        this.$axios.get('users/has_permission?permissions=' + encodeURIComponent(JSON.stringify(userPermission))).then((res) => {
          this.createdByOptions = res.data
        })
      ]).finally(() => {
        this.$q.loading.hide()
      })
      if (this.$route.params.id) {
        this.$q.loading.show()
        this.$axios.get('purchases/' + this.$route.params.id).then((res) => {
          this.bill_number = res.data.bill_number
          this.status = res.data.status
          this.supplier = res.data.supplier
          this.created_by_id = res.data.created_by_id
          this.bill_date = res.data.bill_date
          this.delivery_date = res.data.delivery_date
          this.warehouse = res.data.warehouse
          this.row_discount_mode = res.data.row_discount_mode
          this.discount_subtotal = res.data.discount_subtotal
          this.taxable_discount = res.data.taxable_discount
          this.bill_freight = res.data.bill_freight
          this.bill_freight_gst = res.data.bill_freight_gst
          this.round = res.data.round
          this.external_freight = res.data.external_freight
          this.remarks = res.data.remarks ? res.data.remarks : ''
          this.items = res.data.items
          this.taxable_total = parseFloat(res.data.taxable_total)
          this.tax_row_total = parseFloat(res.data.tax_row_total)
          this.products = this.$_.map(res.data.grn.items, (v) => { return { name: v.product.name, id: v.product.id } })
        }).finally(() => {
          this.$store.commit('setPageTitle', 'Edit Purchase: ' + this.bill_number)
          this.$q.loading.hide()
        })
      } else {
        if (!this.$route.query.grn_id) {
          this.$store.commit('setPageTitle', 'Create Purchase')
          this.bill_number = Date.now().toString()
          this.supplier = {
            id: 181,
            name: 'Apexion Dental Products & Services'
          }
          const today = new Date()
          const year = today.getFullYear().toString()
          let month = (today.getMonth() + 1).toString()
          let day = today.getDate().toString()
          if (month.length < 2) { month = '0' + month }
          if (day.length < 2) { day = '0' + day }
          const todayFormatted = year + '-' + month + '-' + day
          this.bill_date = todayFormatted
          this.delivery_date = todayFormatted
          this.warehouse = {
            id: 1,
            name: 'Default'
          }
        }
      }
      if (this.$route.query.grn_id) {
        this.$q.loading.show()
        this.grn_id = this.$route.query.grn_id
        this.$axios.get('goods_receive_notes/' + this.$route.query.grn_id).then((res) => {
          this.supplier = { id: res.data.supplier.id, name: res.data.supplier.name }
          this.grn_serial_no = res.data.serial_no
          this.delivery_date = res.data.delivery_date
          this.warehouse = res.data.warehouse
          this.remarks = res.data.remarks ? res.data.remarks : ''
          this.products = this.$_.map(res.data.items, (v) => { return { name: v.product.name, id: v.product.id } })
        })
      }
    }
  },
  methods: {
    dateValidation (v) {
      if (!v) {
        return 'Required'
      } else {
        if (!/^-?[\d]+-[0-1]\d-[0-3]\d$/.test(v)) {
          return 'Invalid Date'
        }
      }
      return true
    },
    productFilter (val, update, abort) {
      if (val === '') {
        update(() => {
          this.productOptions = this.products
        })
        return
      }
      const needle = val.toLowerCase()
      if (this.$route.query.grn_id || this.$route.params.id) {
        update(() => {
          this.productOptions = this.products.filter((v) => v.name.toLowerCase().indexOf(needle) > -1)
        })
      } else {
        this.$axios.get('products/search?keyword=' + encodeURIComponent(val)).then((res) => {
          update(() => {
            this.productOptions = res.data
          })
        })
      }
    },
    addRow () {
      if (
        this.$refs.product.validate() &
                this.$refs.gst.validate() &
                this.$refs.qty.validate() &
                this.$refs.rate.validate() &
                this.$refs.row_weight.validate() &
                this.$refs.mrp.validate()
      ) {
        this.items.push({
          sl_no: this.items.length + 1,
          name: this.product.name,
          mrp: this.mrp,
          product_id: this.product.id,
          product: this.product,
          hsn: this.hsn,
          gst: this.gst,
          qty: this.qty,
          is_free: this.is_free,
          local: this.local,
          rate: this.rate,
          discount: this.row_discount || '0.00',
          taxable: this.row_taxable,
          tax_amount: this.row_tax_amount,
          weight: this.row_weight
        })
        this.pushTotal(this.row_tax_amount, this.row_taxable)
        this.product = null
        this.hsn = null
        this.gst = null
        this.qty = null
        this.is_free = false
        this.mrp = null
        this.rate = null
        this.row_discount = null
        this.row_taxable = null
        this.row_tax_amount = null
        this.$nextTick(() => {
          this.$refs.product.focus()
        })
      }
    },
    pushTotal (tax, taxable) {
      this.taxable_total += parseFloat(taxable)
      this.tax_row_total += parseFloat(tax)
    },
    deleteFn (index) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        const oldTaxable = parseFloat(this.items[index].taxable)
        const oldTaxAmount = parseFloat(this.items[index].tax_amount)
        this.pushTotal(-1 * oldTaxAmount, -1 * oldTaxable)
        this.items.splice(index, 1)
      }).onCancel(() => {

      })
    },
    qtyValidation (v) {
      if (!v) return 'Required'
      else if (!Number.isInteger(Number(v))) return 'Must be an Integer'
      else if (Number(v) < 1) return 'Invalid'
      else return true
    },
    rateEditValidation (val) {
      if (val && !isNaN(val) && parseFloat(val) > 0) {
        return true
      } else {
        return 'Invalid Entry'
      }
    },
    weightEditValidation (val) {
      if (val && !isNaN(val) && parseFloat(val) > 0) {
        this.errorWeight = false
        this.errorMessageWeight = ''
        return true
      } else {
        this.errorWeight = true
        this.errorMessageWeight = 'Invalid Entry'
        return false
      }
    },
    discountEditValidation (val) {
      if (val == null || !isNaN(val)) {
        this.errorDiscount = false
        this.errorMessageDiscount = ''
        return true
      } else {
        this.errorDiscount = true
        this.errorMessageDiscount = 'Invalid Entry'
        return false
      }
    },
    rateValidation (v) {
      if (this.is_free) {
        return true
      } else {
        if (parseFloat(this.rate) > 0) {
          return true
        } else {
          return 'Invalid'
        }
      }
    },
    updateRow (index) {
      const oldTaxable = parseFloat(this.items[index].taxable)
      const oldTaxAmount = parseFloat(this.items[index].tax_amount)
      const qty = parseInt(this.items[index].qty)
      const rate = parseFloat(this.items[index].rate)
      const discount = this.items[index].discount ? parseFloat(this.items[index].discount) : 0
      const gst = parseFloat(this.items[index].gst)
      let taxable = 0
      if (this.row_discount_mode === 'percentage') {
        taxable = rate * qty * (1 - (discount / 100))
      } else {
        taxable = (rate * qty) - discount
      }
      this.items[index].taxable = taxable.toFixed(2)
      const taxAmount = taxable * (gst / 100)
      this.items[index].tax_amount = taxAmount.toFixed(2)
      const taxableDiff = taxable - oldTaxable
      const taxAmountDiff = taxAmount - oldTaxAmount
      this.pushTotal(taxAmountDiff, taxableDiff)
    },
    sendToAdmin () {
      this.saveFn('Pending Approval')
    },
    saveFn (status = 'Draft') {
      if (
        this.$refs.bill_number.validate() &
        this.$refs.bill_date.validate() &
        this.$refs.created_by_id.validate() &
        this.$refs.taxable_discount.validate() &
        this.$refs.bill_freight_gst.validate() &
        this.$refs.discount_subtotal.validate() &
        this.$refs.bill_freight.validate() &
        this.$refs.round.validate() &
        this.$refs.external_freight.validate() &
                this.validateItemsCount()) {
        const obj = {
          _method: this.$route.params.id ? 'PUT' : 'POST',
          bill_number: this.bill_number,
          grn_id: this.grn_id,
          status: status,
          supplier_id: this.supplier.id,
          created_by_id: this.created_by_id,
          bill_date: this.bill_date,
          delivery_date: this.delivery_date,
          warehouse_id: this.warehouse.id,
          items: this.$_.map(this.items, this.$_.partialRight(this.$_.pick, ['product_id', 'qty', 'rate', 'is_free', 'local', 'gst', 'discount', 'weight', 'mrp', 'hsn'])),
          remarks: this.remarks,
          discount_subtotal: this.discount_subtotal,
          taxable_discount: this.taxable_discount,
          bill_freight: this.bill_freight,
          external_freight: this.external_freight,
          bill_freight_gst: this.bill_freight_gst,
          round: this.round,
          row_discount_mode: this.row_discount_mode,
          freight_split_method: this.freight_split_method
        }
        let route = 'purchases'
        if (this.$route.params.id) {
          route = 'purchases/' + this.$route.params.id
        }
        this.$q.loading.show()
        this.$axios.post(route, obj).then((res) => {
          if (res.data.message === 'success') {
            this.$q.loading.hide()
            this.$q.notify({
              type: 'positive',
              message: 'Saved Succesfully.'
            })
            this.$router.push('/purchases')
          }
        }).catch((error) => {
          this.$q.loading.hide()
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
        }).finally(() => {
          this.$q.loading.hide()
        })
      } else {
        this.$q.notify({
          type: 'negative',
          message: 'Invalid data given !!!'
        })
      }
    },
    validateItemsCount () {
      if (this.items.length > 0) {
        return true
      }
      this.$q.notify({
        type: 'negative',
        message: 'There should be atleast one item'
      })
      return false
    },
    updateFields () {
      // console.log('called')
      const qty = this.qty ? parseInt(this.qty) : 0
      const rate = this.rate ? parseFloat(this.rate) : 0
      const discount = this.row_discount ? parseFloat(this.row_discount) : 0
      const gst = this.gst ? parseFloat(this.gst) : 0
      let taxable = 0
      if (this.row_discount_mode === 'percentage') {
        taxable = rate * qty * (1 - (discount / 100))
      } else {
        taxable = (rate * qty) - discount
      }
      this.row_taxable = taxable.toFixed(2)
      const taxAmount = taxable * (gst / 100)
      this.row_tax_amount = taxAmount.toFixed(2)
    },
    duplicateRow (obj) {
      this.items.push(this.$_.cloneDeep(obj))
    }
  },
  watch: {
    is_free (newV, oldV) {
      if (newV) {
        this.rate = '0.00'
        this.$nextTick(() => {
          this.$refs.rate.validate()
        })
      }
    },
    product (newV, oldV) {
      if (newV) {
        this.$axios.get('products/' + newV.id).then((res) => {
          this.gst = res.data.gst
          this.row_weight = res.data.weight
          this.hsn = res.data.hsn
          this.mrp = res.data.mrp
        })
      }
    },
    qty (newV, oldV) {
      this.updateFields()
    },
    rate (newV, oldV) {
      this.updateFields()
    },
    row_discount (newV, oldV) {
      this.updateFields()
    },
    gst (newV, oldV) {
      this.updateFields()
    }
  }
}
</script>
