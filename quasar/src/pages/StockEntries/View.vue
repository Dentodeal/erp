<template>
  <q-page>
    <page-toolbar
    :buttons="toolbarButtons"
    v-on:activate="activate()"
    v-on:sendtoaccounts="sendtoaccounts()"
    v-on:revert="revert()"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <q-card-section>
          <div class="row q-col-gutter-md q-mb-md">
            <div class="col">
                <q-input v-model="model.name" outlined dense square readonly label="Name"/>
            </div>
            <div class="col">
                <q-input v-model="model.created_by.name" outlined dense square readonly label="Created By"/>
            </div>
            <div class="col">
                <q-input v-model="model.status" outlined dense square readonly label="Status"/>
            </div>
          </div>
          <div class="row">
            <div class="col">
                <q-table
                :columns="columns"
                title="Items"
                :data="model.items"
                row-key="sl_no"
                wrap-cells
                :rows-per-page-options="[0]"
                :filter="search"
                >
                <template v-slot:top-right>
                    <q-input borderless dense debounce="300" v-model="search" placeholder="Search">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                    </q-input>
                </template>
                <template v-slot:body="props">
                    <q-tr :props="props">
                      <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                      <q-td>
                        <router-link :to="'/products/view/' + props.row.product.id">
                        {{props.row.product.name}}
                        </router-link>
                        <q-btn icon="content_copy" flat round @click="$store.dispatch('copyToClipboard', props.row.product.name)"></q-btn>
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.weight}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.length}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.breadth}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.height}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.gtin}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.reorder_code}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.origin_country}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.qty}}
                      </q-td>
                      <q-td class="text-right">
                        {{props.row.expirable ? 'Yes' : 'No'}}
                      </q-td>
                      <q-td class="text-right">
                        <q-btn v-if="props.row.expirable" flat color="primary" label="Expiry Data" @click="loadExpiryData(props.row)"/>
                      </q-td>
                    </q-tr>
                </template>
                </q-table>
            </div>
          </div>
        <q-card-section>
          <div class="text-subtitle2">Remarks</div>
          <div v-html="model.remarks"></div>
        </q-card-section>
        </q-card-section>
      </q-card>
    </div>
    <q-dialog v-model="expiryDialog">
      <q-card style="width: 500px">
        <q-card-section>
          <div class="text-subtitle1">Expiry Details</div>
        </q-card-section>
        <q-card-section>
          <div class="row" v-for="(item, i) in expiry_data" :key="i">
            <div class="col">
              <q-input filled dense v-model="expiry_data[i].date"
              label="Expiry Date" readonly>
              </q-input>
            </div>
            <div class="col">
              <q-input outlined square dense v-model="expiry_data[i].qty" label="Qty" readonly/>
            </div>
          </div>
        </q-card-section>
        <q-card-actions>
          <q-btn label="close" @click="closeExpiryDialog" flat color="primary"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'StockEntriesView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  mounted () {
    if (this.$route.params.id) {
      this.$axios.get('stock_entries/' + this.$route.params.id).then((res) => {
        this.model = res.data
      })
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.model.status === 'Draft') {
        arr.push({
          label: 'Send to Accounts',
          id: 'sendtoaccounts',
          type: 'button',
          color: 'blue',
          icon: 'forward'
        })
        arr.push({
          label: 'Edit',
          id: 'edit',
          type: 'a',
          color: 'grey-9',
          link: '/stock_entries/edit/' + this.$route.params.id,
          icon: 'edit'
        })
      } else {
        arr.push({
          label: 'Revert',
          id: 'revert',
          type: 'button',
          color: 'grey-9',
          icon: 'undo'
        })
      }
      if ((this.$store.getters.hasPermissionTo('invoice_sale_order') || this.$store.getters.hasRole('Super Admin')) && this.model.status === 'Pending') {
        arr.push({
          label: 'Activate',
          id: 'activate',
          type: 'button',
          color: 'teal',
          icon: 'check'
        })
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Stock Entries', link: '/stock_entries' },
        { label: this.model.name, link: '/stock_entries/view/' + this.$route.params.id }
      ]
      return arr
    }
  },
  data () {
    return {
      model: {
        created_by: {
          name: null
        },
        status: null,
        name: null,
        remarks: '',
        items: []
      },
      search: null,
      expiry_data: [
        { date: null, qty: null }
      ],
      expiryDialog: false,
      columns: [
        {
          name: 'sl_no',
          field: 'sl_no',
          label: '#',
          sortable: true
        },
        {
          name: 'product',
          field: (row) => row.product.name,
          label: 'Product',
          sortable: true,
          align: 'left'
        },
        {
          name: 'weight',
          field: 'weight',
          label: 'W',
          sortable: true
        },
        {
          name: 'length',
          field: 'length',
          label: 'L',
          sortable: true
        },
        {
          name: 'breadth',
          field: 'breadth',
          label: 'B',
          sortable: true
        },
        {
          name: 'height',
          field: 'height',
          label: 'H',
          sortable: true
        },
        {
          name: 'gtin',
          field: 'gtin',
          label: 'GTIN',
          sortable: true
        },
        {
          name: 'mpn',
          field: 'mpn',
          label: 'MPN',
          sortable: true
        },
        {
          name: 'origin_country',
          field: 'origin_country',
          label: 'Country',
          sortable: true
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        },
        {
          name: 'expirable',
          field: 'expirable',
          label: 'Expirable',
          sortable: true
        },
        {
          name: 'expiry_data',
          field: 'expiry_data',
          label: 'Expiry Data',
          sortable: true
        }
      ]
    }
  },
  methods: {
    loadExpiryData (data) {
      const ind = this.$_.findIndex(this.model.items, (item) => item.id === data.id)
      this.expiry_data = this.model.items[ind].expiry_data
      this.expiryDialog = true
    },
    closeExpiryDialog () {
      this.expiryDialog = false
    },
    activate () {
      this.$q.loading.show()
      this.$axios.get('stock_entries/activate/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            message: 'Activated Successfully',
            type: 'positive'
          })
          this.$router.push({ path: '/stock_entries' })
        }
      }).finally(() => {
        this.$q.loading.hide()
      })
    },
    sendtoaccounts () {
      this.$q.loading.show()
      this.$axios.get('stock_entries/sendtoaccounts/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            message: 'Sent to Accounts Successfully',
            type: 'positive'
          })
          this.$router.push({ path: '/stock_entries' })
        }
      }).finally(() => {
        this.$q.loading.hide()
      })
    },
    revert () {
      this.$q.loading.show()
      this.$axios.get('stock_entries/revert/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            message: 'Reverted Successfully',
            type: 'info'
          })
          this.$router.push({ path: '/stock_entries' })
        }
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  }
}
</script>
