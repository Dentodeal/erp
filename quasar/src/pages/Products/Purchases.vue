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
                            row-key="bill_number"
                        >
                            <template v-slot:body="props">
                              <q-tr :props="props">
                                <q-td class="text-left">
                                    {{props.rowIndex+1}}
                                </q-td>
                                <q-td class="text-left">
                                    <router-link :to="'/purchases/view/' + props.row.purchase.id">{{props.row.purchase.bill_number}}</router-link>
                                </q-td>
                                <q-td class="text-left">
                                    {{props.row.purchase.status}}
                                </q-td>
                                <q-td class="text-right">
                                    {{props.row.qty}}
                                </q-td>
                                <q-td class="text-right">
                                    {{props.row.cost}}
                                </q-td>
                                <q-td class="text-right">
                                    {{props.row.purchase.bill_date}}
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
          name: 'bill_number',
          label: 'Bill No.',
          field: (row) => row.purchase.bill_number,
          align: 'left',
          sortable: true
        },
        {
          name: 'status',
          label: 'Status',
          field: (row) => row.purchase.status,
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
          name: 'cost',
          label: 'Cost',
          field: 'cost',
          align: 'right',
          sortable: true
        },
        {
          name: 'bill_date',
          label: 'Bill Date',
          field: (row) => row.purchase.bill_date,
          align: 'right',
          sortable: true
        }
      ]
    }
  },
  mounted () {
    this.$q.loading.show()
    Promise.all([
      this.$axios.get('products/purchases/' + this.$route.params.id).then((res) => {
        this.sales = res.data.filter((item) => item.purchase !== null)
      }),
      this.$axios.get('products/' + this.$route.params.id).then((res) => {
        this.name = res.data.name
      })
    ]).finally(() => {
      this.$store.commit('setPageTitle', 'Purchases of ' + this.name)
      this.$q.loading.hide()
    })
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '/products' },
        { label: this.name, link: '/products/view/' + this.$route.params.id },
        { label: 'Purchases' }
      ]
      return arr
    },
    toolbarButtons () {
      const arr = []
      return arr
    }
  }
}
</script>
