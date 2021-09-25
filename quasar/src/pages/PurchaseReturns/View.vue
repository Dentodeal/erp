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
                                <tr v-if="purchase.supplier">
                                    <td>Supplier</td>
                                    <td><router-link :to="'/suppliers/view/'+purchase.supplier.id">{{purchase.supplier.name}}</router-link></td>
                                </tr>
                                <tr v-if="purchase.warehouse">
                                    <td>Warehouse</td>
                                    <td>{{purchase.warehouse.name}}</td>
                                </tr>
                                <tr>
                                    <td>Purchase</td>
                                    <td><router-link :to="'/purchases/view/'+purchase.id">{{purchase.bill_number}}</router-link></td>
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
                            {{props.row.qty.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}
                            <q-popup-edit v-model="props.row.qty"
                              buttons
                              label-set="Save"
                              label-cancel="Close"
                              :validate="() => $refs.qty_edit.validate() ? true : false"
                              @hide="update();props.row.qty = parseInt(props.row.qty);"
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
      purchase: {
        id: null,
        bill_no: null,
        supplier: {
          id: null,
          name: null
        },
        warehouse: null
      },
      created_by: {
        name: null
      },
      created_at: null,
      status: null,
      remarks: '',
      items: [],
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
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        }
      ]
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Purchase Returns', link: '/purchase_returns' },
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
      this.$axios.post('purchase_returns/' + this.$route.params.id, {
        _method: 'PUT',
        items: this.items
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
      this.$axios.get('purchase_returns/' + this.$route.params.id).then((res) => {
        this.serial_no = res.data.serial_no
        this.purchase = res.data.purchase
        this.created_by = res.data.created_by
        this.created_at = res.data.created_at
        this.status = res.data.status
        this.remarks = res.data.remarks
        this.items = res.data.items
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
    activate () {
      this.$axios.post('purchase_returns/activate/' + this.$route.params.id, {
        remarks: this.remarks
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$router.back()
          this.$q.notify({ type: 'positive', message: 'Purchase Return Activated Successfully' })
        }
      })
    }
  }
}
</script>
