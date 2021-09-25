<template>
    <q-page>
        <page-toolbar
            :buttons="toolbarButtons"
            v-on:save="saveFn()"
            v-on:activate="activateFn()"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-md-6 col-12 q-mt-md">
                            <q-select
                                outlined square dense
                                label="Supplier"
                                v-model="supplier"
                                use-input
                                fill-input
                                ref="supplier"
                                :rules="[v=>!!v||'Required']"
                                hide-selected
                                :options="supplierOptions"
                                @filter="supplierFilterFn"
                                option-value="id"
                                option-label="name">
                                <template v-slot:no-option>
                                    <q-item>
                                        <q-item-section class="text-grey">
                                        No results
                                        </q-item-section>
                                    </q-item>
                                </template>
                            </q-select>
                        </div>
                        <div class="col-md-6 col-12 q-mt-md">
                            <div class="row q-col-gutter-md">
                                <div class="col">
                                    <q-input dense filled v-model="delivery_date" readonly label="Delivery date" hint="Click on calender icon to enter date" ref="delivery_date"
                                        :rules="[v=>!!v||'Required']">
                                        <template v-slot:append>
                                            <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                                                <q-date v-model="delivery_date" >
                                                <div class="row items-center justify-end">
                                                    <q-btn v-close-popup label="Close" color="primary" flat />
                                                </div>
                                                </q-date>
                                            </q-popup-proxy>
                                            </q-icon>
                                        </template>
                                    </q-input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md">
                        <div class="col-md-6 col-12 q-mt-md">
                            <q-select
                                v-model="warehouse_id"
                                dense
                                label="Warehouse"
                                emit-value
                                map-options
                                option-value="id"
                                option-label="name"
                                outlined square
                                ref="warehouse"
                                :rules="[v=>!!v||'Required']"
                                :options="warehouses"/>
                        </div>
                        <div class="col-md-6 col-12 q-mt-md">
                            <q-input readonly outlined square dense label="Created By" :value="$store.getters.user.name"/>
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
                                <template v-slot:body="props">
                                    <q-tr :props="props">
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
                                            {{props.row.expiry_date}}
                                            <q-popup-edit v-model="props.row.expiry_date" v-if="props.row.expirable"
                                                buttons
                                                label-set="Save"
                                                label-cancel="Close"
                                                :validate="v => expiryEditValidation(v,props.row.expirable) === true ? true: false"
                                                >
                                                <q-input label="Expiry Date" tabindex="5"
                                                  v-model="props.row.expiry_date"
                                                  :rules="[v => expiryEditValidation(v,props.row.expirable)]"
                                                  ref="expiry_date_edit"
                                                  mask="####-##-##"
                                                  hint="Format: yyyy/mm/dd"
                                                  @input="$refs.expiry_date_edit.resetValidation()">
                                                  <template v-slot:append>
                                                    <q-icon name="event" class="cursor-pointer" tabindex="5">
                                                      <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                                                        <q-date v-model="props.row.expiry_date" mask="YYYY-MM-DD">
                                                          <div class="row items-center justify-end">
                                                            <q-btn v-close-popup label="Close" color="primary" flat />
                                                          </div>
                                                        </q-date>
                                                      </q-popup-proxy>
                                                    </q-icon>
                                                  </template>
                                                </q-input>
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
                                            <q-btn round icon="delete" flat color="black" @click="deleteFn(props.rowIndex)"/>
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
                                @filter="productFilter"
                                option-value="id"
                                option-label="name"
                                use-input
                                fill-input
                                hide-selected
                                v-model="product"
                                :loading="prodLoading"
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
                          <q-input label="Expiry Date" tabindex="2"
                            v-model="expiry_date" square outlined dense
                            :disable="!this.expirable"
                            :rules="[expiryValidation]"
                            ref="expiry_date"
                            mask="####-##-##" lazy-rules="ondemand"
                            hint="Format: yyyy-mm-dd"
                            @input="$refs.expiry_date.resetValidation()">
                            <template v-slot:append>
                              <q-icon name="event" class="cursor-pointer" tabindex="5">
                                <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                                  <q-date v-model="expiry_date" mask="YYYY-MM-DD">
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
                            <q-input tabindex="3" outlined square dense v-model="qty" label="Qty" class="input-right"
                              @focus="$event.target.select()"
                              ref="qty"
                              :rules="[qtyValidation]" lazy-rules="ondemand"
                              @blur="()=>{qty = qty ? parseInt(qty).toString():null}"/>
                        </div>
                        <div class="">
                            <q-btn icon="add" tabindex="4" round flat color="teal" @click="addRow()"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="text-subtitle1 q-mt-md">Remarks</div>
                            <q-editor v-model="remarks" min-height="5rem"/>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </div>
        <page-toolbar :page-title="''" :buttons="toolbarButtons"
            v-on:save="saveFn()"
            v-on:activate="activateFn()" class="q-mt-md"/>
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
      supplier: null,
      supplierOptions: [],
      warehouses: [],
      warehouse_id: null,
      items: [],
      status: 'Draft',
      delivery_date: null,
      productOptions: [],
      gstOptions: [],
      product: null,
      qty: null,
      expiry_date: null,
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
          name: 'expiry_date',
          field: 'expiry_date',
          label: 'Exp.Date',
          sortable: false
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
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
      expirable: null,
      prodLoading: false,
      errorQty: false,
      errorMessageQty: '',
      errorRate: false,
      errorMessageRate: '',
      remarks: ''
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_purchase') || this.$store.getters.hasPermissionTo('update_purchase')) {
        arr.push({
          label: 'Activate',
          id: 'activate',
          type: 'button',
          color: 'green',
          icon: 'check'
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
        { label: 'Goods Receive Notes', link: '/purchases' },
        { label: this.$route.params.id ? this.bill_number : 'Create', link: '/goods_receive_notes/view/' + this.$route.params.id }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
      }
      return arr
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('create_purchase')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      Promise.all([
        this.$axios.get('warehouses').then((res) => {
          this.warehouses = res.data.model
          if (this.warehouses.length === 1) {
            this.warehouse_id = this.warehouses[0].id
          }
        })
      ]).finally(() => {
        this.$q.loading.hide()
      })
      if (this.$route.params.id) {
        this.$q.loading.show()
        this.$axios.get('goods_receive_notes/' + this.$route.params.id).then((res) => {
          this.supplier = res.data.supplier
          this.delivery_date = res.data.delivery_date
          this.warehouse_id = res.data.warehouse_id
          this.remarks = res.data.remarks ? res.data.remarks : ''
          this.items = res.data.items
        }).finally(() => {
          this.$store.commit('setPageTitle', 'Edit Goods Receivable Note: ' + this.bill_number)
          this.$q.loading.hide()
        })
      } else {
        this.$store.commit('setPageTitle', 'Create Goods Receivable Note')
      }
    }
  },
  methods: {
    supplierFilterFn (val, update, abort) {
      update(() => {
        if (val) {
          this.$axios.get('suppliers_search?&search=' + encodeURIComponent(val)).then((res) => {
            this.supplierOptions = res.data
          })
        }
      })
    },
    productFilter (val, update, abort) {
      update(() => {
        if (val) {
          this.prodLoading = true
          this.$axios.get('products/search?keyword=' + encodeURIComponent(val)).then((res) => {
            this.productOptions = res.data
          }).finally(() => {
            this.prodLoading = false
          })
        }
      })
    },
    addRow () {
      if (
        this.$refs.product.validate() &
                this.$refs.qty.validate() &
                this.$refs.expiry_date.validate()
      ) {
        this.items.push({
          sl_no: this.items.length + 1,
          name: this.product.name,
          product_id: this.product.id,
          product: this.product,
          expiry_date: this.expiry_date,
          expirable: this.expirable,
          qty: this.qty
        })
        this.product = null
        this.qty = null
        this.expiry_date = null
        this.expirable = null
        this.$nextTick(() => {
          this.$refs.product.focus()
        })
      }
    },
    deleteFn (index) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
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
    activateFn () {
      this.saveFn('Active')
    },
    saveFn (status = 'Draft') {
      if (
        this.$refs.supplier.validate() &
        this.$refs.delivery_date.validate() &
        this.$refs.warehouse.validate() &
                this.validateItemsCount()) {
        const obj = {
          _method: this.$route.params.id ? 'PUT' : 'POST',
          status: status,
          supplier_id: this.supplier.id,
          delivery_date: this.delivery_date,
          warehouse_id: this.warehouse_id,
          items: this.$_.map(this.items, this.$_.partialRight(this.$_.pick, ['product_id', 'qty', 'expirable', 'expiry_date'])),
          remarks: this.remarks
        }
        let route = 'goods_receive_notes'
        if (this.$route.params.id) {
          route = 'goods_receive_notes/' + this.$route.params.id
        }
        this.$q.loading.show()
        this.$axios.post(route, obj).then((res) => {
          if (res.data.message === 'success') {
            this.$q.loading.hide()
            this.$q.notify({
              type: 'positive',
              message: 'Saved Succesfully.'
            })
            this.$router.back()
          }
        }).catch((error) => {
          this.$q.loading.hide()
          if (error.response.status === 422) {
            this.$q.notify({
              type: 'negative',
              message: error.response.data.message
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
    expiryValidation (v) {
      if (this.expirable) {
        if (!v) {
          return 'Required'
        } else {
          if (!/^-?[\d]+-[0-1]\d-[0-3]\d$/.test(v)) {
            return 'Invalid Date'
          }
        }
      }
      return true
    },
    expiryEditValidation (v, expirable) {
      if (expirable && v) {
        if (!/^-?[\d]+-[0-1]\d-[0-3]\d$/.test(v)) {
          return 'Invalid Date'
        }
      }
      return true
    },
    duplicateRow (obj) {
      this.items.push(this.$_.cloneDeep(obj))
    }
  },
  watch: {
    product (newV, oldV) {
      if (newV) {
        this.$axios.get('products/' + newV.id).then((res) => {
          this.gst = res.data.gst
          this.expirable = res.data.expirable
          this.expiry_date = null
        })
      }
    }
  }
}
</script>
