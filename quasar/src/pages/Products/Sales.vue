<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
        <q-card>
            <q-card-section>
                <div class="row">
                    <div class="col">
                        <q-table
                            :columns="columns"
                            :data="sales"
                            row-key="serial_no"
                            :pagination="{rowsPerPage:20}"
                        >
                            <template v-slot:body-cell-sl_no="props">
                                <q-td :props="props">
                                    {{props.rowIndex+1}}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-serial_no="props">
                                <q-td :props="props">
                                    <router-link :to="'/sale_orders/view/' + props.row.sale_order.id">{{props.row.sale_order.serial_no}}</router-link>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-status="props">
                                <q-td :props="props">
                                    {{props.row.sale_order.status}}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-customer="props">
                                <q-td :props="props">
                                    <router-link :to="'/customers/view/' + props.row.sale_order.customer.id">{{props.row.sale_order.customer.name}}</router-link>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-customer_type="props">
                                <q-td :props="props">
                                    {{props.row.sale_order.customer.type}}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-qty="props">
                                <q-td :props="props">
                                    {{props.row.qty}}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-rate="props">
                                <q-td :props="props">
                                    {{getRate(props.row)}}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-created_at="props">
                                <q-td :props="props">
                                    {{getLocaleString(props.row.sale_order.created_at)}}
                                </q-td>
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
  name: 'SaleOrderIndex',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      name: null,
      sales: [],
      columns: [
        {
          name: 'sl_no',
          label: '#',
          field: 'sl_no',
          align: 'left'
        },
        {
          name: 'serial_no',
          label: 'Serial No',
          field: (row) => row.sale_order.serial_no,
          align: 'left',
          sortable: true
        },
        {
          name: 'status',
          label: 'Status',
          field: (row) => row.sale_order.status,
          align: 'left',
          sortable: true
        },
        {
          name: 'customer',
          label: 'Customer',
          field: (row) => row.sale_order.customer.name,
          align: 'left',
          sortable: true
        },
        {
          name: 'customer_type',
          label: 'Customer Type',
          field: (row) => row.sale_order.customer.type,
          align: 'left',
          sortable: true
        },
        {
          name: 'qty',
          label: 'Qty',
          field: 'qty',
          align: 'right',
          sortable: true
        },
        {
          name: 'rate',
          label: 'Rate(Incl GST)',
          field: 'rate',
          align: 'right',
          sortable: true
        },
        {
          name: 'created_at',
          label: 'Created At',
          field: (row) => row.sale_order.created_at,
          align: 'right',
          sortable: true
        }
      ]
    }
  },
  mounted () {
    this.$q.loading.show()
    Promise.all([
      this.$axios.get('products/sales/' + this.$route.params.id).then((res) => {
        this.sales = res.data.filter((item) => {
          return (item.sale_order != null && item.sale_order.customer != null)
        })
      }),
      this.$axios.get('products/' + this.$route.params.id).then((res) => {
        this.name = res.data.name
      })
    ]).finally(() => {
      this.$store.commit('setPageTitle', 'Sale Orders of ' + this.name)
      this.$q.loading.hide()
    })
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '/products' },
        { label: this.name, link: '/products/view/' + this.$route.params.id },
        { label: 'Sales' }
      ]
      return arr
    },
    toolbarButtons () {
      const arr = []

      return arr
    }
  },
  methods: {
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
    },
    getRate (row) {
      if (row.sale_order.rate_includes_gst) return row.rate
      return (parseFloat(row.rate) + (parseFloat(row.tax_amount) / parseFloat(row.qty))).toFixed(2)
    }
  }
}
</script>
