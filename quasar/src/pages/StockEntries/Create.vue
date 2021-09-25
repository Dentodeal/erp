<template>
  <q-page>
    <page-toolbar
    :buttons="toolbarButtons"
    v-on:save="saveFn()"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <q-card-section>
          <q-form ref="form">
            <div class="row q-col-gutter-md q-mb-md">
              <div class="col-6">
                <q-input v-model="model.name" outlined dense square
                :rules="[v => !!v || 'Required']" ref="name" label="Name"
                :error-message="errors.name"
                :error="errors.name && errors.name.length > 0"/>
              </div>
              <div class="col-6">
                <q-input v-model="model.created_by.name" outlined dense square label="Created By" readonly/>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <q-table
                :columns="columns"
                title="Items"
                :data="model.items"
                row-key="sl_no"
                wrap-cells
                :rows-per-page-options="[0]"
                :filter="search"
                >
                  <template v-slot:top-right>
                    <q-input borderless dense debounce="300" v-model="search" placeholder="Search">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                    </q-input>
                  </template>
                  <template v-slot:body="props">
                    <q-tr :props="props">
                      <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                      <q-td>
                        {{props.row.product.name}}
                        <q-popup-edit v-model="props.row.product.name"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          @hide="()=>{ product=null }"
                          @show="()=>{ product=props.row.product.name }"
                          >
                          <q-select
                            outlined square dense
                            label="Product"
                            :rules="[v=>!!v||'Required']"
                            lazy-rules="ondemand"
                            :options="productOptions"
                            @filter="productFilter"
                            use-input
                            fill-input
                            hide-selected
                            v-model="product"
                            :loading="prodLoading"
                            option-value="id"
                            option-label="name"
                            @input="props.row.product = product;"
                            >
                            <template v-slot:no-option>
                              <q-item>
                                <q-item-section class="text-grey">
                                  No results
                                </q-item-section>
                              </q-item>
                            </template>
                          </q-select>
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.weight}}
                        <q-popup-edit v-model="props.row.weight"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          :validate="v => !isNaN(v)"
                          >
                          <q-input
                            @input="$refs.weight_edit.resetValidation()"
                            ref="weight_edit"
                            :rules="[(v) => !isNaN(v) || 'Invalid']"
                            v-model="props.row.weight"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.length}}
                        <q-popup-edit v-model="props.row.length"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          :validate="v => !isNaN(v)"
                          >
                          <q-input
                            @input="$refs.length_edit.resetValidation()"
                            ref="length_edit"
                            :rules="[(v) => !isNaN(v) || 'Invalid']"
                            v-model="props.row.length"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.breadth}}
                        <q-popup-edit v-model="props.row.breadth"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          :validate="v => !isNaN(v)"
                          >
                          <q-input
                            @input="$refs.breadth_edit.resetValidation()"
                            ref="breadth_edit"
                            :rules="[(v) => !isNaN(v) || 'Invalid']"
                            v-model="props.row.breadth"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.height}}
                        <q-popup-edit v-model="props.row.height"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          :validate="v => !isNaN(v)"
                          >
                          <q-input
                            @input="$refs.height_edit.resetValidation()"
                            ref="height_edit"
                            :rules="[(v) => !isNaN(v) || 'Invalid']"
                            v-model="props.row.height"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.gtin}}
                        <q-popup-edit v-model="props.row.gtin"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          >
                          <q-input
                            v-model="props.row.gtin"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.reorder_code}}
                        <q-popup-edit v-model="props.row.reorder_code"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          >
                          <q-input
                            v-model="props.row.reorder_code"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.origin_country}}
                        <q-popup-edit v-model="props.row.origin_country"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          >
                          <q-select
                            :options="countries"
                            v-model="props.row.origin_country"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.qty}}
                        <q-popup-edit v-model="props.row.qty"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          :validate="v => qtyValidation(v) === true ? true : false"
                          @hide="props.row.qty = parseInt(props.row.qty)"
                          >
                          <q-input
                            @input="$refs.qty_edit.resetValidation()"
                            ref="qty_edit"
                            :rules="[qtyEditValidation]"
                            v-model="props.row.qty"
                            dense
                            autofocus
                            />
                        </q-popup-edit>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.expirable ? 'Yes' : 'No'}}
                      </q-td>
                      <q-td class="text-right">
                        <q-btn v-if="props.row.expirable" flat color="primary" label="Expiry Data" @click="loadExpiryData(props.row)"/>
                      </q-td>
                      <q-td class="text-right">
                        <q-btn size="sm" flat color="grey-9" round icon="delete" @click="deleteFn(props.row)"/>
                      </q-td>
                    </q-tr>
                  </template>
                </q-table>
              </div>
            </div>
            <div class="row q-col-gutter-xs q-mt-xs">
              <div class="col-4">
                <q-select
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
                  @input-value="$refs.product.resetValidation()"
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
                <q-input
                  outlined square dense
                  v-model="weight" label="W"
                  class="input-right" @focus="$event.target.select()"
                  ref="weight"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  lazy-rules="ondemand"
                  @input="$refs.weight.resetValidation()"/>
              </div>
              <div class="col">
                <q-input
                  outlined square dense
                  v-model="length" label="L"
                  class="input-right" @focus="$event.target.select()"
                  ref="length"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  lazy-rules="ondemand"
                  @input="$refs.length.resetValidation()"/>
              </div>
              <div class="col">
                <q-input
                  outlined square dense
                  v-model="breadth" label="B"
                  class="input-right" @focus="$event.target.select()"
                  ref="breadth"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  lazy-rules="ondemand"
                  @input="$refs.breadth.resetValidation()"/>
              </div>
              <div class="col">
                <q-input
                  outlined square dense
                  v-model="height" label="H"
                  class="input-right" @focus="$event.target.select()"
                  ref="height"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  lazy-rules="ondemand"
                  @input="$refs.height.resetValidation()"/>
              </div>
              <div class="col">
                <q-input
                  outlined square dense
                  v-model="gtin" label="GTIN"
                  class="input-right" @focus="gtin && $event.target.select()"
                  ref="gtin"/>
              </div>
              <div class="col">
                <q-input
                  outlined square dense
                  v-model="reorder_code" label="MPN"
                  class="input-right" @focus="reorder_code && $event.target.select()"
                  ref="reorder_code"/>
              </div>
              <div class="">
                <q-select
                  :options="countries"
                  outlined square dense
                  v-model="origin_country" label="Country"
                  @focus="origin_country && $event.target.select()"
                  />
              </div>
              <div class="col">
                <q-input
                  outlined square dense
                  v-model="qty" label="Qty"
                  class="input-right" @focus="$event.target.select()"
                  ref="qty"
                  :rules="[qtyValidation]"
                  lazy-rules="ondemand"
                  @input="$refs.qty.resetValidation()"/>
              </div>
              <div class="">
                <q-checkbox v-model="expirable" label="Exp?"/>
              </div>
              <div class="" v-if="expirable">
                <q-btn flat color="grey-8" class="q-mt-xs" @click="expiryDialog = true" label="Exp Details" :disable="qty == 0"/>
              </div>
              <div class="">
                <q-btn icon="add" round flat color="teal" @click="addRow()"/>
              </div>
            </div>
            <div class="row q-col-gutter-xs q-mt-md">
              <div class="col">
                <div class="text-subtitle2">Remarks</div>
                <q-editor v-model="model.remarks"/>
              </div>
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </div>
    <q-dialog v-model="expiryDialog" persistent>
      <q-card style="width: 500px">
        <q-card-section>
          <div class="text-subtitle1">Expiry Details</div>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col text-right">Pending</div>
            <div class="col">{{availableQty}}</div>
          </div>
          <div class="row" v-for="(item, i) in expiry_data" :key="i">
            <div class="col">
              <q-input filled dense v-model="expiry_data[i].date"
              ref="date"
              :rules="[v=>!!v||'Required', dateValidation]"
              hint="Format yyyy-mm-dd"
              label="Expiry Date"
              mask="####-##-##">
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                      <q-date v-model="expiry_data[i].date">
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
              <q-input outlined square dense v-model="expiry_data[i].qty" label="Qty" ref="expiry_qty" :rules="[qtyValidation]" @focus="expiry_data[i].qty && $event.target.select()"/>
            </div>
            <div class="" v-if="expiry_data.length > 1">
              <q-btn size="sm" round icon="delete" @click="expiry_data.splice(i, 1)" flat/>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <q-btn flat color="grey-9" label="Add" @click="expiry_data.push({ date: null, qty: null }); $nextTick(() => $refs.date[expiry_data.length -1].focus())"/>
            </div>
          </div>
        </q-card-section>
        <q-card-actions>
          <q-btn label="submit" :disable="availableQty !== 0" @click="closeExpiryDialog" flat color="primary"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'StockEntriesCreate',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  mounted () {
    this.$axios.get('countries').then((res) => {
      this.countries = res.data
    })
    if (this.$route.params.id) {
      this.$axios.get('stock_entries/' + this.$route.params.id).then((res) => {
        this.model = res.data
        if (res.data.remarks == null) { this.model.remarks = '' }
      })
    } else {
      this.model.created_by.name = this.$store.getters.user.name
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      arr.push({
        label: 'Save as Draft',
        id: 'save',
        type: 'button',
        color: 'teal',
        icon: 'save'
      })
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Stock Entries', link: '/stock_entries' },
        { label: this.$route.params.id ? this.model.name : 'Create', link: '/stock_entries/view/' + this.$route.params.id }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
      }
      return arr
    },
    availableQty () {
      if (this.expiryEditIndex !== null) {
        return parseInt(this.model.items[this.expiryEditIndex].qty) - this.$_.sumBy(this.expiry_data, (v) => v.qty ? parseInt(v.qty) : 0)
      }
      return parseInt(this.qty) - this.$_.sumBy(this.expiry_data, (v) => v.qty ? parseInt(v.qty) : 0)
    }
  },
  data () {
    return {
      model: {
        name: null,
        created_by: {
          name: null,
          id: null
        },
        items: [],
        remarks: ''
      },
      countries: [],
      origin_country: null,
      search: null,
      errors: {
        name: null
      },
      product: null,
      qty: 0,
      expirable: false,
      expiry_data: [
        { date: null, qty: null }
      ],
      weight: 0,
      length: 0,
      breadth: 0,
      height: 0,
      gtin: null,
      reorder_code: null,
      productOptions: [],
      prodLoading: false,
      expiryDialog: false,
      expiryEditIndex: null,
      columns: [
        {
          name: 'sl_no',
          field: 'sl_no',
          label: '#',
          sortable: false
        },
        {
          name: 'product',
          field: (row) => row.product.name,
          label: 'Product',
          sortable: false,
          align: 'left'
        },
        {
          name: 'weight',
          field: 'weight',
          label: 'W',
          sortable: false
        },
        {
          name: 'length',
          field: 'length',
          label: 'L',
          sortable: false
        },
        {
          name: 'breadth',
          field: 'breadth',
          label: 'B',
          sortable: false
        },
        {
          name: 'height',
          field: 'height',
          label: 'H',
          sortable: false
        },
        {
          name: 'gtin',
          field: 'gtin',
          label: 'GTIN',
          sortable: false
        },
        {
          name: 'mpn',
          field: 'mpn',
          label: 'MPN',
          sortable: false
        },
        {
          name: 'origin_country',
          field: 'origin_country',
          label: 'Country',
          sortable: false
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: false
        },
        {
          name: 'expirable',
          field: 'expirable',
          label: 'Exp?',
          sortable: false
        },
        {
          name: 'expiry_data',
          field: 'expiry_data',
          label: 'Exp Data',
          sortable: false
        },
        {
          name: 'actions',
          field: 'actions',
          label: '',
          sortable: false
        }
      ]
    }
  },
  watch: {
    product (newV, oldV) {
      if (newV && typeof (newV) === 'object') {
        this.$axios.get('products/basic/' + newV.id + '/' + 1).then((res) => {
          this.weight = res.data.weight
          this.reorder_code = res.data.reorder_code
        })
      }
    }
  },
  methods: {
    qtyValidation (val) {
      val = parseInt(val)
      if (!val) return 'Required'
      else if (isNaN(val)) return 'Invalid'
      else if (val < 0) return 'Invalid'
      else if (this.expirable && parseInt(this.availableQty) > 0) return 'Expiry Data Missing / Incomplete'
      else return true
    },
    qtyEditValidation (val) {
      val = parseInt(val)
      if (!val) return 'Required'
      else if (isNaN(val)) return 'invalid'
      else if (!Number.isInteger(Number(val))) return 'Must be Integer'
      return true
    },
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
      update(() => {
        if (val) {
          this.prodLoading = true
          this.$axios.get('products/search?inclall=1&keyword=' + encodeURIComponent(val)).then((res) => {
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
      this.$refs.weight.validate() &
      this.$refs.length.validate() &
      this.$refs.breadth.validate() &
      this.$refs.height.validate() &
      this.$refs.qty.validate()) {
        if (typeof (this.product) === 'object' && this.$_.findIndex(this.model.items, (v) => v.product.id === this.product.id) !== -1) {
          this.$q.dialog({
            title: 'Duplicate Entry !!',
            message: 'This product is already entered',
            persistent: true
          })
        } else {
          this.addRow2()
        }
      }
    },
    addRow2 () {
      this.model.items.push({
        product: this.product,
        qty: this.qty,
        weight: this.weight,
        length: this.length,
        breadth: this.breadth,
        height: this.height,
        gtin: this.gtin,
        reorder_code: this.reorder_code,
        origin_country: this.origin_country,
        expirable: this.expirable,
        expiry_data: this.expiry_data
      })
      this.product = null
      this.qty = 0
      this.weight = 0
      this.length = 0
      this.breadth = 0
      this.height = 0
      this.gtin = null
      this.reorder_code = null
      this.origin_country = null
      this.expirable = false
      this.expiry_data = [
        { date: null, qty: null }
      ]
      this.$nextTick(() => {
        this.$refs.product.focus()
      })
    },
    deleteFn (data) {
      const ind = this.$_.findIndex(this.model.items, (item) => item.product.id === data.product.id)
      if (this.model.items[ind].id) {
        this.$q.loading.show()
        this.$axios.get('stock_entries/delete_item/' + this.model.items[ind].id).then((res) => {
          if (res.data.message === 'success') {
            this.model.items.splice(ind, 1)
          }
        }).finally(() => {
          this.$q.loading.hide()
        })
      } else {
        this.model.items.splice(ind, 1)
      }
    },
    loadExpiryData (data) {
      const ind = this.$_.findIndex(this.model.items, (item) => item.product.id === data.product.id)
      // console.log('index: ' + ind)
      // console.log('product: ' + this.model.items[ind].product.name)
      // console.log(this.model.items[ind])
      this.expiryEditIndex = ind
      this.expiry_data = this.model.items[ind].expiry_data
      this.expiryDialog = true
    },
    closeExpiryDialog () {
      let flag = true
      this.$refs.date.forEach((v, i) => {
        if (!this.$refs.date[i].validate()) flag = false
      })
      this.$refs.expiry_qty.forEach((v, i) => {
        if (!this.$refs.expiry_qty[i].validate()) flag = false
      })
      if (flag) {
        this.expiryDialog = false
        if (this.expiryEditIndex !== null) {
          this.model.items[this.expiryEditIndex].expiry_data = this.expiry_data
          this.expiry_data = [
            { date: null, qty: null }
          ]
          this.expiryEditIndex = null
        }
      }
    },
    saveFn () {
      if (this.$refs.name.validate()) {
        if (this.model.items.length === 0) {
          this.$q.notify({
            message: 'There should be atleast one item',
            type: 'negative'
          })
        } else {
          let errorStr = ''
          this.model.items.forEach((item, i) => {
            if (item.expirable) {
              const totalExpirableQty = this.$_.sumBy(item.expiry_data, v => parseInt(v.qty))
              if (totalExpirableQty !== parseInt(item.qty)) {
                errorStr += '<p> Line ' + (i + 1) + ': Expiry Data missing / incomplete </p>'
              }
            }
          })
          if (errorStr) {
            this.$q.dialog({
              message: errorStr,
              html: true,
              title: 'Errors in Form'
            })
          } else {
            this.$q.loading.show()
            const obj = this.model
            obj._method = 'POST'
            let route = 'stock_entries'
            if (this.$route.params.id) {
              obj._method = 'PUT'
              route = 'stock_entries/' + this.$route.params.id
            }
            this.$axios.post(route, obj).then((res) => {
              // console.log(res)
              if (res.data.message === 'success') {
                this.$router.push({ path: '/stock_entries' })
              }
            }).catch((err) => {
              // console.log(err)
              let errorStr = ''
              Object.keys(err.response.data.errors).forEach((key) => {
                if (key === 'name') {
                  this.errors.name = err.response.data.errors.name[0]
                } else if (key.indexOf('items') !== -1) {
                  errorStr += '<p>Line ' + key.substr(6, 1) + ': Already Taken </p>'
                }
              })
              if (errorStr) {
                this.$q.dialog({
                  message: errorStr,
                  html: true,
                  title: 'Errors in Form'
                })
              }
            }).finally(() => {
              this.$q.loading.hide()
            })
          }
        }
      }
    }
  }
}
</script>
