<template>
    <q-page>
        <page-toolbar />
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <q-card-section>
                  <div class="row">
                    <q-card dark class="bg-cyan-9">
                      <q-card-section>
                        <div class="text-h4">Total Available Stock: {{total}}</div>
                      </q-card-section>
                    </q-card>
                  </div>
                  <div class="row">
                      <div class="col">
                          <q-table
                              title="Stock Status"
                              :columns="columns"
                              :data="stocks"
                              rows-per-page-options="0"
                          >
                              <template v-slot:body="props">
                                  <q-tr :props="props">
                                      <q-td key="sl_no" :props="props">
                                          {{props.rowIndex+1}}
                                      </q-td>
                                      <q-td key="qty" :props="props">
                                          {{props.row.qty}}
                                      </q-td>
                                      <q-td key="expiry_date" :props="props">
                                          {{props.row.expiry_date}}
                                      </q-td>
                                      <q-td key="reservation" :props="props">
                                          <q-chip color="green-10" text-color="white" v-if="props.row.reservable_id == 0">Available</q-chip>
                                          <q-chip color="orange-10" text-color="white" v-else>Reserved</q-chip>
                                      </q-td>
                                      <q-td key="reserved_under" :props="props">
                                          <router-link v-if="props.row.reservable_id != 0" :to="'/sale_orders/view/'+props.row.reservable_id">{{props.row.saleorder.serial_no}} ({{props.row.saleorder.status}})</router-link>
                                      </q-td>
                                      <q-td key="warehouse" :props="props">
                                          {{props.row.warehouse.name}}
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
  name: 'ProductsStock',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  mounted () {
    this.$q.loading.show()
    Promise.all([
      this.$axios.get('products/' + this.$route.params.id).then((res) => {
        this.expirable = res.data.expirable
        this.name = res.data.name
      }),
      this.$axios.get('/products/stock/' + this.$route.params.id).then((res) => {
        this.stocks = res.data.status
        this.total = res.data.total
      })
    ]).finally(() => {
      this.$store.commit('setPageTitle', 'Stock: ' + this.name)
      this.$q.loading.hide()
    })
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '/products' },
        { label: this.name, link: '/products/view/' + this.$route.params.id },
        { label: 'Stock' }
      ]
      return arr
    }
  },
  data () {
    return {
      expirable: null,
      stocks: [],
      name: null,
      total: 0,
      columns: [
        {
          name: 'sl_no',
          label: '#',
          field: 'sl_no',
          align: 'left'
        },
        {
          name: 'qty',
          label: 'Qty',
          field: 'qty',
          align: 'right',
          sortable: true
        },
        {
          name: 'expiry_date',
          label: 'Expiry Date',
          field: 'expiry_date',
          align: 'right',
          sortable: true
        },
        {
          name: 'reservation',
          label: 'Reservation',
          field: 'reservation',
          align: 'left',
          sortable: true
        },
        {
          name: 'reserved_under',
          label: 'Reserved Under',
          field: 'reserved_under',
          align: 'left',
          sortable: true
        },
        {
          name: 'warehouse',
          label: 'Warehouse',
          field: 'warehouse',
          align: 'left',
          sortable: true
        }
      ]
    }
  },
  methods: {
  }
}
</script>
