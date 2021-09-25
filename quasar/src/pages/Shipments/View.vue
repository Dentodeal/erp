<template>
    <q-page>
        <page-toolbar
            :buttons="toolbarButtons"
            v-on:complete="completeFn()"
            v-on:save="saveFn()"
            v-on:sms="sms()"
            v-on:generatelabel="generatelabel()"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <div class="row q-col-gutter-md q-pa-md ">
                    <div class="col">
                        <q-markup-table wrap-cells separator="cell">
                            <tbody>
                                <tr>
                                    <td>Serial No</td>
                                    <td>{{serial_no}}</td>
                                </tr>
                                <tr>
                                    <td>Customer</td>
                                    <td>{{sale_order.customer.name}}</td>
                                </tr>
                                <tr>
                                    <td>Source</td>
                                    <td>{{sale_order.source}}</td>
                                </tr>
                                <tr v-if="sale_order">
                                    <td>Sale order</td>
                                    <td> <router-link :to="'/sale_orders/view/'+sale_order_id">{{sale_order.serial_no}}</router-link></td>
                                </tr>
                                <tr v-if="sale_order.cod">
                                    <td colspan="2"><div class="text-center text-subtitle2">COD</div></td>
                                </tr>
                            </tbody>
                        </q-markup-table>
                    </div>
                    <div class="col">
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
                                    <td>Representative</td>
                                    <td>{{sale_order.representative.name}}</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>{{getLocaleString(created_at)}}</td>
                                </tr>
                                <tr>
                                    <td>Order No</td>
                                    <td>{{sale_order.order_no}}</td>
                                </tr>
                            </tbody>
                        </q-markup-table>
                    </div>
                </div>
                <div class="row q-mt-md q-px-md">
                    <div class="col">
                        <div class="text-h6">Details</div>
                        <div class="row q-col-gutter-md">
                            <div class="col">
                                <div class="text-subtitle2 q-mt-md">Dimensions & Weight</div>
                                <div class="row q-col-gutter-md ">
                                    <div class="col-3">
                                        <q-input v-model="length" label="L" hint="in cms" outlined square dense/>
                                    </div>
                                    <div class="col-3">
                                        <q-input v-model="width" label="W" hint="in cms" outlined square dense/>
                                    </div>
                                    <div class="col-3">
                                        <q-input v-model="height" label="H" hint="in cms" outlined square dense/>
                                    </div>
                                     <div class="col-3">
                                        <q-input v-model="weight" filled bg-color="green-2" label="Weight" hint="in grams" outlined square dense/>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="text-subtitle2 q-mt-md">Tracking</div>
                                <div class="row q-col-gutter-md q-mb-sm">
                                    <div class="col">
                                        <q-select v-model="courier_id"
                                            :options="logisticOptions"
                                            label="Courier" outlined square dense
                                            option-value="id"
                                            emit-value
                                            map-options
                                            option-label="name"/>
                                    </div>
                                    <div class="col">
                                        <q-input v-model="tracking_number" label="Tracking Number"  outlined square dense/>
                                    </div>
                                </div>
                                <div class="row" v-if="courier_id">
                                    <div class="col">
                                        <q-btn no-caps flat label="View Details" @click="openLogisticDetails"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row q-mt-md q-px-md">
                    <div class="col ">
                        <div class="text-subtitle2">Remarks</div>
                        <q-editor v-model="remarks"/>
                    </div>
                </div>
            </q-card>
        </div>
        <page-toolbar :page-title="''" :buttons="toolbarButtons"
            v-on:complete="completeFn()"
            v-on:save="saveFn()"
            v-on:generatelabel="generatelabel()"
            class="q-mt-md"/>
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
      sale_order_id: null,
      customer: {
        name: null
      },
      sale_order: {
        serial_no: null,
        source: null,
        representative: {
          name: null
        },
        customer: {
          name: null
        }
      },
      created_by: {
        name: null
      },
      created_at: null,
      status: null,
      remarks: '',
      length: null,
      width: null,
      height: null,
      weight: null,
      courier_id: null,
      tracking_number: null,
      logisticOptions: []
    }
  },
  mounted () {
    this.$axios.get('logistic_partners').then((res) => {
      this.logisticOptions = res.data.model
    })
    this.getDataFromApi()
  },
  computed: {
    toolbarButtons () {
      const arr = []
      arr.push({
        label: 'Generate Label',
        id: 'generatelabel',
        type: 'button',
        color: 'grey-10',
        icon: 'get_app',
        flat: true
      })
      if (this.status === 'Complete') {
        arr.push({
          label: 'Send SMS',
          id: 'sms',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
      }
      if (this.status !== 'Complete') {
        arr.push({
          label: 'Save',
          id: 'save',
          type: 'button',
          color: 'green',
          icon: 'save'
        })
        if ((this.$store.getters.hasPermissionTo('update_shipment') && this.weight && this.length && this.width && this.height) || this.sale_order.otc) {
          arr.push({
            label: 'Ship',
            id: 'complete',
            type: 'button',
            color: 'green-10',
            icon: 'check'
          })
        }
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Shipments', link: '/shipments' },
        { label: this.serial_no }
      ]
      return arr
    }
  },
  methods: {
    getDataFromApi () {
      this.$q.loading.show()
      this.$axios.get('shipments/' + this.$route.params.id).then((res) => {
        this.serial_no = res.data.serial_no
        this.sale_order_id = res.data.sale_order_id
        this.sale_order = res.data.sale_order
        this.created_by = res.data.created_by
        this.created_at = res.data.created_at
        this.status = res.data.status
        this.remarks = res.data.remarks ? res.data.remarks : ''
        this.length = res.data.length
        this.width = res.data.width
        this.height = res.data.height
        this.weight = res.data.weight
        this.courier_id = res.data.courier_id
        this.tracking_number = res.data.tracking_number
      }).finally(() => {
        this.$store.commit('setPageTitle', 'Shipments: ' + this.serial_no)
        this.$q.loading.hide()
      })
    },
    completeFn () {
      const obj = {
        length: this.length,
        width: this.width,
        height: this.height,
        weight: this.weight,
        courier_id: this.courier_id,
        tracking_number: this.tracking_number,
        remarks: this.remarks,
        _method: 'PUT'
      }
      this.$axios.post('shipments/complete/' + this.$route.params.id, obj).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data saved successfully'
          })
          this.$router.back()
        }
      })
    },
    saveFn () {
      const obj = {
        length: this.length,
        width: this.width,
        height: this.height,
        weight: this.weight,
        courier_id: this.courier_id,
        tracking_number: this.tracking_number,
        remarks: this.remarks,
        _method: 'PUT'
      }
      this.$axios.post('shipments/' + this.$route.params.id, obj).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data saved successfully'
          })
          this.$router.back()
        }
      })
    },
    ship () {
      this.$q.dialog({
        title: 'Select Shipment Generation Mode',
        options: {
          type: 'radio',
          model: 'shipment_gen_mode',
          items: [
            { label: 'Full Shipment', value: 'full' },
            { label: 'Split Shipment', value: 'split' }
          ]
        }
      }).onOk((data) => {
        if (data === 'full') {
          this.$axios.get('shipments/ship/' + this.$route.params.id).then((res) => {
            if (res.data.message === 'success') {
              // this.getDataFromApi()
              this.$q.notify({
                type: 'positive',
                message: 'Shipment generated succesfully'
              })
              this.$router.push({ path: '/shipments/view/' + res.data.shipment_id })
            }
          })
        } else if (data === 'split') {
          this.shipmentSelectionDialog = true
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
    openLogisticDetails () {
      this.$axios.get('logistic_partners/' + this.courier_id).then((res) => {
        let str = '<ul>'
        res.data.contacts.forEach((item) => {
          str += '<li>' + item.name + ', ' + item.phone + ' ' + item.designation + '</li>'
        })
        str += '</ul>'
        this.$q.dialog({
          title: res.data.name,
          message: '<p>' + res.data.address + '<br/>' + str,
          html: true
        })
      })
    },
    generatelabel () {
      this.$q.loading.show()
      this.$axios({
        url: 'shipments/label/' + this.$route.params.id,
        method: 'GET',
        responseType: 'blob' // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', this.serial_no.replace(/\//g, '') + '.pdf')
        document.body.appendChild(link)
        link.click()
      }).finally(() => {
        this.$q.loading.hide()
      })
    },
    sms () {
      this.$q.loading.show()
      this.$axios.get('shipments/sms/' + this.$route.params.id).then(() => {
        this.$q.notify({ message: 'SMS Sent' })
      }).finally(() => this.$q.loading.hide())
    }
  }
}
</script>
