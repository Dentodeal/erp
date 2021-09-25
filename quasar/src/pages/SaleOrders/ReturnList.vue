<template>
  <q-page>
    <page-toolbar
      :buttons="toolbarButtons"
    />
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
                    <router-link :to="'/sale_returns/view/' + props.row.sale_return.id">{{props.row.sale_return.serial_no}}</router-link>
                  </q-td>
                  <q-td class="text-right">
                      <div v-if="props.row.gst">
                          {{props.row.gst}}%
                      </div>
                  </q-td>
                  <q-td class="text-right">
                      {{props.row.qty}}

                  </q-td>
                  <q-td class="text-right">
                      {{props.row.rate}}
                  </q-td>
                  <q-td class="text-right">
                    {{props.row.expiry_date}}
                  </q-td>
                  <q-td class="text-right">
                      {{props.row.is_free ? 'Yes':'No'}}
                  </q-td>
                  <q-td class="text-right" v-if="!rate_includes_gst">
                      {{props.row.taxable}}
                  </q-td>
                  <q-td class="text-right" v-if="!rate_includes_gst">
                      {{props.row.tax_amount}}
                  </q-td>
                  <q-td class="text-right">
                      {{props.row.total}}
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
  name: 'SaleOrdersReturns',
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
        { label: 'Sale Orders', link: '/sale_orders' },
        { label: this.serial_no, link: '/sale_orders/view/' + this.$route.params.id },
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
          name: 'gst',
          field: 'gst',
          label: 'GST',
          sortable: true
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        },
        {
          name: 'rate',
          field: 'rate',
          label: 'Rate',
          sortable: true
        },
        {
          name: 'expiry_date',
          field: 'expiry_date',
          label: 'Exp Date',
          sortable: true
        },
        {
          name: 'is_free',
          field: 'is_free',
          label: 'Free ?',
          sortable: true
        }
      ]
      if (!this.rate_includes_gst) {
        arr.push({
          name: 'taxable',
          field: 'taxable',
          label: 'Taxable',
          sortable: true
        },
        {
          name: 'tax_amount',
          field: 'tax_amount',
          label: 'Tax Amt',
          sortable: true
        })
      }
      arr.push({
        name: 'total',
        field: 'total',
        label: 'Total',
        sortable: true
      })
      return arr
    }
  },
  mounted () {
    this.$q.loading.show()
    this.$axios.get('sale_orders/returns/' + this.$route.params.id).then((res) => {
      this.items = res.data.items
      this.serial_no = res.data.model.serial_no
      this.rate_includes_gst = res.data.model.rate_includes_gst
    }).finally(() => {
      this.$store.commit('setPageTitle', 'Sale Returns of ' + this.serial_no)
      this.$q.loading.hide()
    })
  }
}
</script>
