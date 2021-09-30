<template>
    <q-page>
        <page-toolbar/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <q-card-section>
                    <div class="row">
                        <div class="col">
                            <q-markup-table wrap-cells separator="cell" bordered square dark class="bg-blue-grey-10">
                                <tbody>
                                    <tr>
                                        <td><div class="text-subtitle1">Landing Price:<span class="text-green-5"> {{landing}}</span></div></td>
                                        <td><div class="text-subtitle1">Cost Price: <span class="text-blue-5"> {{cost}}</span></div></td>
                                        <td><div class="text-subtitle1">MRP: <span class="text-red-5"> {{mrp}}</span></div></td>
                                        <td><div class="text-subtitle1">GST: <span class="text-green-5"> {{gst}}</span></div></td>
                                    </tr>
                                    <tr>
                                        <td><div class="text-subtitle1">GSP Customer <span class="text-grey-6"> {{gsp_customer}}</span></div></td>
                                        <td><div class="text-subtitle1">GSP Dealer <span class="text-grey-6"> {{gsp_dealer}}</span></div></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </q-markup-table>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md q-mt-md">
                        <div class="col">
                            <q-input outlined square label="Min Margin"
                                v-model.number="min_margin" :rules="[v=> !!v && !isNaN(v) || 'Invalid']"
                                ref="min_margin"
                                lazy-rules="ondemand"/>
                        </div>
                        <div class="col">
                            <q-input outlined square readonly :value="Math.ceil(cost*(1+(min_margin/100)))" label="Minimum Selling Price"/>
                        </div>
                        <div class="col">
                            <q-btn label="Save" color="blue-10" @click="saveMinMargin"/>
                        </div>
                    </div>
                    <div class="row q-mt-md">
                        <div class="col-12">
                            <div class="text-h6">Pricelists</div>

                            <div class="row q-col-gutter-md" v-for="(pricelist,i) in pricelists" :key="i">
                                <div class="col">
                                    <q-input outlined square :label="pricelist.name" readonly/>
                                </div>
                                <div class="col">
                                    <q-input outlined square v-model.number="pricelists[i].margin" suffix="%" label="Margin(%)"/>
                                </div>
                                <div class="col">
                                    <q-input outlined square readonly :value="Math.ceil(cost*(1+(pricelists[i].margin/100)))" label="Selling Price"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 q-mt-md">
                            <q-btn label="Save Pricelist" color="primary" @click="savePricelist" class="q-mr-md "/>
                            <q-btn label="load purchase data" @click="loadPurchases" color="teal-10" depressed/>
                        </div>
                    </div>
                    <div class="row q-mt-md" v-if="purchases.length > 0">
                        <div class="col">
                            <q-table
                                :columns="columns"
                                :data="purchases"
                                :title="'Purchases of '+name"
                            >
                                <template v-slot:body="props">
                                  <q-tr :props="props">
                                    <q-td class="text-left">
                                        {{props.rowIndex+1}}
                                    </q-td>
                                    <q-td class="text-left">
                                        {{props.row.purchase.bill_number}}
                                    </q-td>
                                    <q-td class="text-left">
                                        {{props.row.purchase.status}}
                                    </q-td>
                                    <q-td class="text-left">
                                        {{props.row.purchase.type}}
                                    </q-td>
                                    <q-td class="text-right">
                                        {{props.row.purchase.bill_date}}
                                    </q-td>
                                    <q-td class="text-right">
                                        {{props.row.qty}}
                                    </q-td>
                                    <q-td class="text-right">
                                        {{props.row.rate}}
                                    </q-td>
                                    <q-td class="text-center">
                                        <q-btn flat color="blue-10" :to="'/purchases/view/'+props.row.purchase_id" label="view bill"/>
                                    </q-td>
                                  </q-tr>
                                </template>
                            </q-table>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </div>
    </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ProductsCost',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('view_product_pricing')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      Promise.all([
        this.$axios.get('/products/cost/' + this.$route.params.id).then((res) => {
          this.landing = res.data.landing ? res.data.landing : 0
          this.cost = res.data.cost ? res.data.cost : 0
        }),
        this.$axios.get('/products/' + this.$route.params.id).then((res) => {
          this.mrp = res.data.mrp
          this.gst = res.data.gst
          this.gsp_customer = res.data.gsp_customer
          this.gsp_dealer = res.data.gsp_dealer
          this.name = res.data.name
          this.min_margin = res.data.min_margin
        }),
        this.$axios.get('pricelists').then((res) => {
          const pricelists = res.data.model
          pricelists.forEach((item, i) => {
            pricelists[i].margin = 0
          })
          this.$axios.get('products/pricelists/' + this.$route.params.id).then((res2) => {
            if (res2.data) {
              res2.data.forEach((v) => {
                pricelists.forEach((item, i) => {
                  if (item.id === v.pricelist_id) { pricelists[i].margin = v.margin }
                })
              })
            }
            this.pricelists = pricelists
          })
        })
      ]).finally(() => {
        this.$store.commit('setPageTitle', 'Cost & Pricing: ' + this.name)
        this.$q.loading.hide()
      })
    }
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '/products' },
        { label: this.name, link: '/products/view/' + this.$route.params.id },
        { label: 'Cost' }
      ]
      return arr
    }
  },
  data () {
    return {
      landing: 0,
      cost: 0,
      name: null,
      mrp: null,
      gst: null,
      gsp_customer: null,
      gsp_dealer: null,
      columns: [
        {
          name: 'sl_no',
          label: '#',
          field: 'sl_no',
          align: 'left'
        },
        {
          name: 'bill_number',
          label: 'Bill No.',
          field: 'bill_number',
          align: 'left'
        },
        {
          name: 'status',
          label: 'Status',
          field: 'status',
          align: 'left'
        },
        {
          name: 'type',
          label: 'Type',
          field: 'type',
          align: 'left'
        },
        {
          name: 'bill_date',
          label: 'Bill Date',
          field: 'bill_date',
          align: 'right'
        },
        {
          name: 'qty',
          label: 'Qty',
          field: 'qty',
          align: 'right'
        },
        {
          name: 'rate',
          label: 'Rate',
          field: 'rate',
          align: 'right'
        },
        {
          name: 'action',
          label: 'Action',
          field: 'action',
          align: 'center'
        }
      ],
      purchases: [],
      pricelists: [],
      min_margin: 0
    }
  },
  methods: {
    loadPurchases () {
      this.$axios.get('products/purchases/' + this.$route.params.id).then((res) => {
        this.purchases = res.data.filter((item) => item.purchase !== null)
      })
    },
    saveMinMargin () {
      if (this.$refs.min_margin.validate()) {
        this.$axios.post('products/min_margin/' + this.$route.params.id, { min_margin: this.min_margin }).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              message: 'Minimum Margin saved successfully',
              type: 'positive'
            })
          }
        })
      }
    },
    savePricelist () {
      const obj = {
        pricelists: this.pricelists
      }
      this.$axios.post('products/pricelists/' + this.$route.params.id, obj).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            message: 'Pricelist saved successfully',
            type: 'positive'
          })
        }
      })
    }
  }
}
</script>
