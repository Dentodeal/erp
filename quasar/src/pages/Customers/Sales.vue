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
                        >
                            <template v-slot:body-cell-sl_no="props">
                                <q-td :props="props">
                                    {{props.rowIndex+1}}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-serial_no="props">
                                <q-td :props="props">
                                    <router-link :to="'/sale_orders/view/'+props.row.id">{{props.row.serial_no}}</router-link>
                                </q-td>
                            </template>
                            <template v-slot:body-cell-created_at="props">
                                <q-td :props="props">
                                    {{getLocaleString(props.row.created_at)}}
                                </q-td>
                            </template>
                            <template v-slot:body-cell-total="props">
                                <q-td :props="props">
                                    {{props.row.total}}
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
          field: 'serial_no',
          align: 'left',
          sortable: true
        },
        {
          name: 'status',
          label: 'Status',
          field: 'status',
          align: 'left',
          sortable: true
        },
        {
          name: 'payment_status',
          label: 'Payment Status',
          field: 'payment_status',
          align: 'left',
          sortable: true
        },
        {
          name: 'created_at',
          label: 'Created At',
          field: 'created_at',
          align: 'right',
          sortable: true
        },
        {
          name: 'total',
          label: 'Total',
          field: 'total',
          align: 'right',
          sortable: true
        }
      ]
    }
  },
  mounted () {
    this.$q.loading.show()
    Promise.all([
      this.$axios.get('customers/sales/' + this.$route.params.id).then((res) => {
        this.sales = res.data
      }),
      this.$axios.get('customers/' + this.$route.params.id).then((res) => {
        this.name = res.data.name
      })
    ]).finally(() => {
      this.$q.loading.hide()
      this.$store.commit('setPageTitle', this.getTitle)
    })
  },
  computed: {
    getTitle () {
      return 'Sale Orders of ' + this.name
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Customers', link: '/customers' },
        { label: this.name, link: '/customers/view/' + this.$route.params.id },
        { label: 'Sales' }
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
