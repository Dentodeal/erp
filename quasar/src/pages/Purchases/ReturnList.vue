<template>
  <q-page>
    <page-toolbar/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <q-card-section>
          <q-table
            :data="items"
            :columns="columns">
            <template v-slot:body="props">
              <q-tr :props="props">
                  <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                  <q-td>
                      {{props.row.product.name}}
                      <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard',props.row.name)"/>
                  </q-td>
                  <q-td>
                    <router-link :to="'/purchase_returns/view/' + props.row.purchase_return.id">{{props.row.purchase_return.serial_no}}</router-link>
                  </q-td>
                  <q-td class="text-right">
                      <q-badge color="green" class="q-pa-sm" v-if="props.row.purchase_return.status === 'Active'">{{props.row.purchase_return.status}}</q-badge>
                      <q-badge color="grey-9" class="q-pa-sm" v-if="props.row.purchase_return.status === 'Draft'">{{props.row.purchase_return.status}}</q-badge>
                  </q-td>
                  <q-td class="text-right">
                      {{props.row.qty}}
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.expiry_date}}
                  </q-td>
              </q-tr>
          </template>
          </q-table>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'PurchaseReturns',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      toolBarButtons: [],
      serial_no: null,
      items: [],
      rate_includes_gst: null
    }
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Purchases', link: '/purchases' },
        { label: this.serial_no, link: '/purchases/view/' + this.$route.params.id },
        { label: 'Returns' }
      ]
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
          name: 'record',
          field: 'record',
          label: 'Record',
          sortable: true,
          align: 'left'
        },
        {
          name: 'status',
          field: row => row.purchase_return.status,
          label: 'Status',
          sortable: true,
          align: 'right'
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        },
        {
          name: 'expiry_date',
          field: 'expiry_date',
          label: 'Exp Date',
          sortable: true
        }
      ]
      return arr
    }
  },
  mounted () {
    this.$q.loading.show()
    this.$axios.get('purchases/returns/' + this.$route.params.id).then((res) => {
      this.items = res.data.items
      this.serial_no = res.data.model.bill_number
      this.rate_includes_gst = res.data.model.rate_includes_gst
    }).finally(() => {
      this.$store.commit('setPageTitle', 'Sale Returns of ' + this.serial_no)
      this.$q.loading.hide()
    })
  }
}
</script>
