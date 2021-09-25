<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"
        v-on:save="saveFn()"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <q-card-section>
          <div :class="$q.screen.gt.sm ? 'row q-col-gutter-md' : 'row q-col-gutter-xs'">
            <div :class="$q.screen.gt.sm ? 'col-md-6 col-12 q-mt-md' : 'col-md-6 col-12 q-mt-xs'">
              <q-input
                :error-message="errors.name"
                :error="errors.name && errors.name.length > 0"
                @input="errors.name = null"
                dense outlined square label="Name" v-model="model.name" ref="name" :rules="[v=> !!v || 'Required']"/>
            </div>
             <div :class="$q.screen.gt.sm ? 'col-md-6 col-12 q-mt-md' : 'col-md-6 col-12 q-mt-xs'">
              <q-input
                :error-message="errors.sku"
                :error="errors.sku && errors.name.length > 0"
                @input="errors.sku = null"
                dense outlined square label="SKU" v-model="model.sku" ref="sku" :rules="[v=> !!v || 'Required']"/>
            </div>
          </div>
          <div class="row q-mt-md">
            <div class="col">
              <q-table
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
                        <q-popup-edit v-model="props.row.product.name"
                            buttons
                            label-set="Save"
                            label-cancel="Close"
                            @show="()=>{product=props.row.product}"
                            @hide="()=>{product= null}"
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
                                @input="props.row.product.name = product.name; props.row.product_id = product.id;"
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
                        {{props.row.qty}}
                        <q-popup-edit v-model="props.row.qty"
                            buttons
                            label-set="Save"
                            label-cancel="Close"
                            :validate="v => qtyEditValidation(v) === true ? true : false"
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
          <div class="row q-col-gutter-xs q-mt-xs">
              <div class="col-9">
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
              <div class="col-2">
                  <q-input
                      outlined square dense
                      v-model="qty" label="Qty"
                      class="input-right" @focus="qty && $event.target.select()"
                      ref="qty"
                      :rules="[qtyValidation]" @blur="()=>{qty = qty ? parseInt(qty).toString():null}"
                      lazy-rules="ondemand"
                      @input="$refs.qty.resetValidation()"/>
              </div>
              <div class="">
                  <q-btn icon="add" round flat color="teal" @click="addRow()"/>
              </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
    <page-toolbar :page-title="''"
        :buttons="toolbarButtons"
        v-on:save="saveFn()"
        class="q-mt-md"/>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ProductBundlesCreate',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      model: {
        name: null,
        sku: null,
        items: []
      },
      search: null,
      table_loading: false,
      prodLoading: false,
      product: null,
      productOptions: [],
      qty: null,
      errors: {
        name: null,
        sku: null
      },
      toolbarButtons: [
        {
          label: 'Save',
          id: 'save',
          type: 'button',
          color: 'teal',
          icon: 'save'
        }
      ]
    }
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Product Bundles', link: '/product_bundles' },
        { label: this.$route.params.id ? this.model.name : 'Create', link: '/product_bundles/view/' + this.$route.params.id }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
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
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        },
        {
          name: 'actions',
          field: 'actions',
          label: '',
          sortable: false
        }
      ]
      return arr
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('create_product')) {
      this.$router.push({ name: 'Error403' })
    } else {
      if (this.$route.params.id) {
        this.$q.loading.show()
        this.$axios.get('product_bundles/' + this.$route.params.id).then((res) => {
          this.model = res.data
        }).finally(() => {
          this.$q.loading.hide()
        })
      }
    }
  },
  methods: {
    addRow () {
      if (this.$refs.product.validate() & this.$refs.qty.validate()) {
        this.addItem(this.qty)
      }
      this.product = null
      this.qty = null
      this.$nextTick(() => {
        this.$refs.product.focus()
      })
    },
    addItem (qty) {
      qty = parseInt(qty)
      this.model.items.push({
        product: {
          name: this.product.name
        },
        product_id: this.product.id,
        qty: qty
      })
    },
    deleteFn (index) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        if (this.model.items[index].id) {
          this.$axios.get('product_bundles/delete_item/' + this.model.items[index].id).then((res) => {
            if (res.data.message === 'success') {
              this.model.items.splice(index, 1)
            }
          })
        } else {
          this.model.items.splice(index, 1)
        }
      }).onCancel(() => {

      })
    },
    qtyValidation () {
      if (this.qty === null || this.qty === '') {
        return 'Required'
      } else if (isNaN(this.qty)) {
        return 'Invalid'
      } else if (parseInt(this.qty) <= 0) {
        return 'Qty must be greater than 0'
      } else {
        return true
      }
    },
    qtyEditValidation (val) {
      if (!val) return 'Required'
      else if (isNaN(val)) return 'invalid'
      else if (!Number.isInteger(Number(val))) return 'Must be Integer'
      return true
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
    saveFn () {
      if (this.$refs.name.validate() & this.validateItemsCount()) {
        const obj = this.model
        obj._method = this.$route.params.id ? 'PUT' : 'POST'
        let route = 'product_bundles'
        if (this.$route.params.id) {
          route = 'product_bundles/' + this.$route.params.id
        }
        this.$q.loading.show()
        this.$axios.post(route, obj).then((res) => {
          if (res.data.message === 'success') {
            this.$q.loading.hide()
            this.$q.notify({
              type: 'positive',
              message: 'Saved Succesfully.'
            })
            this.$router.replace('/product_bundles/view/' + res.data.id)
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
              if (key.indexOf('items') !== -1) {
                str += '<p>' + 'Line ' + (parseInt(key.substr(key.indexOf('.') + 1)) + 1) + ':' + error.response.data.errors[key][0] + '</p><br>'
              }
              if (key.indexOf('name') !== -1) {
                this.errors.name = error.response.data.errors[key][0]
              }
              if (key.indexOf('sku') !== -1) {
                this.errors.sku = error.response.data.errors[key][0]
              }
            })
            if (str) {
              this.$q.dialog({
                style: { width: '50%', maxWidth: '100%' },
                title: 'Cannot process this Sale Order',
                message: str,
                html: true
              })
            }
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
      if (this.model.items.length > 0) {
        return true
      }
      this.$q.notify({
        type: 'negative',
        message: 'There should be atleast one item'
      })
      return false
    }
  }
}
</script>
