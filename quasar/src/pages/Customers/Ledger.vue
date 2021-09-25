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
                          class="my-sticky-header-table"
                            hide-bottom
                            :columns="columns"
                            :data="items"
                            :rows-per-page-options="[0]"
                        >
                            <template v-slot:body="props">
                              <q-tr :props="props"
                              :class="props.row.type === 'Balance' || props.row.type === 'Total'? 'bg-blue-grey-1 text': ''">
                                <q-td >
                                    {{props.rowIndex+1}}
                                </q-td>
                                <q-td class="text-right">
                                    {{getLocaleString(props.row.date)}}
                                </q-td>
                                <q-td>
                                  <template v-if="props.row.type === 'sale_order'">
                                    <router-link :to="'/sale_orders/view/' + props.row.record_id">{{props.row.record}}</router-link>
                                  </template>
                                  <template v-else-if="props.row.type === 'sale_return'">
                                    <router-link :to="'/sale_returns/view/' + props.row.record_id">{{props.row.record}}</router-link>
                                  </template>
                                  <template v-else>
                                    {{props.row.record}}
                                  </template>
                                </q-td>
                                <q-td >
                                    {{props.row.type}}
                                </q-td>
                                <q-td :class="props.row.type === 'Balance' ? 'text-right text-green text-weight-bold': 'text-right text-weight-bold'">
                                    {{props.row.debit ? parseFloat(props.row.debit).toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2}) : ''}}
                                </q-td>
                                <q-td :class="props.row.type === 'Balance' ? 'text-right text-red text-weight-bold': 'text-right text-weight-bold'">
                                    {{props.row.credit ? parseFloat(props.row.credit).toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2}) : ''}}
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
  name: 'CustomersLedger',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      name: null,
      items: [],
      columns: [
        {
          name: 'sl_no',
          label: '#',
          field: 'sl_no',
          align: 'left'
        },
        {
          name: 'date',
          label: 'Date',
          field: 'date',
          align: 'right',
          sortable: true
        },
        {
          name: 'record',
          label: 'Record',
          field: 'record',
          align: 'left',
          sortable: true
        },
        {
          name: 'type',
          label: 'Record Type',
          field: 'type',
          align: 'left',
          sortable: true
        },
        {
          name: 'debit',
          label: 'Debit',
          field: 'debit',
          align: 'right',
          sortable: true
        },
        {
          name: 'credit',
          label: 'Credit',
          field: 'credit',
          align: 'right',
          sortable: true
        }
      ]
    }
  },
  mounted () {
    this.$q.loading.show()
    Promise.all([
      this.$axios.get('customers/ledger/' + this.$route.params.id).then((res) => {
        this.items = res.data
        const debitSum = this.$_.sumBy(this.items, (v) => v.debit ? parseFloat(v.debit) : 0)
        const creditSum = this.$_.sumBy(this.items, (v) => v.credit ? parseFloat(v.credit) : 0)
        const balance = Math.abs(parseFloat(creditSum) - parseFloat(debitSum))
        // console.log(creditSum)
        if (balance) {
          if (creditSum < debitSum) {
            this.items.push({ type: 'Total', debit: debitSum.toFixed(2), credit: creditSum.toFixed(2) })
            this.items.push({ type: 'Balance', credit: balance.toFixed(2) })
          } else {
            this.items.push({ type: 'Total', debit: debitSum.toFixed(2), credit: creditSum.toFixed(2) })
            this.items.push({ type: 'Balance', debit: balance.toFixed(2) })
          }
        } else {
          this.items.push({ type: 'Total', debit: debitSum.toFixed(2), credit: creditSum.toFixed(2) })
        }
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
      return 'Ledger of ' + this.name
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Customers', link: '/customers' },
        { label: this.name, link: '/customers/view/' + this.$route.params.id },
        { label: 'Ledger' }
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
