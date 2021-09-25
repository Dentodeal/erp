<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"
        v-on:sendforapproval="sendForApproval"
        v-on:approve="approve"
        v-on:activate="activate"
        v-on:revert="revert"
        v-on:disable="disable"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <q-card>
        <q-card-section>
            <div class="row q-col-gutter-md">
                <div class="col-xs-12 col-md-6">
                    <q-markup-table separator="cell" wrap-cells>
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td>{{name}}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{type}}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>{{status}}</td>
                            </tr>
                            <tr>
                                <td>GST No.</td>
                                <td>{{gst_number}}</td>
                            </tr>
                             <tr >
                                <td>Initial Balance</td>
                                <td>{{initial_balance}}</td>
                            </tr>
                            <tr>
                                <td v-if="representatives.length == 1">
                                    representatives
                                </td>
                                <td>
                                    {{getRepresentatives()}}
                                </td>
                            </tr>
                            <tr v-for="(email,i) in emails" :key="'email-'+i">
                                <td><div v-if="i==0">Emails</div></td>
                                <td>{{email.content}}</td>
                            </tr>
                            <tr>
                                <td>Non Billable Account ? </td>
                                <td>{{non_billable_account ? 'Yes':'No'}}</td>
                            </tr>
                            <tr v-if="addresses.length > 0">
                                <td>Billing Address</td>
                                <td>{{getBillingAddress()}}</td>
                            </tr>
                            <template v-if="addresses.length > 0">
                                <tr v-for="(address,i) in addresses" :key="'tag-'+i">
                                    <template v-if="address.tag_name != 'billing'">
                                        <td><div>Shipping Address<span v-if="default_shipping == address.id"> (Default)</span></div></td>
                                        <td>{{addressFormat(address)}}</td>
                                    </template>
                                </tr>
                            </template>
                            <tr>
                                <td>Tags</td>
                                <td>{{getTags()}}</td>
                            </tr>
                        </tbody>
                    </q-markup-table>
                </div>
                <div class="col-xs-12 col-md-6">
                  <div class="row" v-if="linked.length > 0">
                    <div class="text-h5 q-mb-md col-12">Relations</div>
                    <q-markup-table separator="cell" wrap-cells v-if="linked.length > 0" style="width:100%">
                        <thead>
                            <th class="text-left">Customer Name</th>
                            <th class="text-left">Relation Type</th>
                            <th class="text-left">Specification</th>
                        </thead>
                        <tbody>
                            <tr v-for="(item,i) in linked" :key="'linked'+i">
                                <td><router-link :to="'/customers/view/'+item.id">{{item.name}}</router-link></td>
                                <td>{{item.pivot.relation_type}}</td>
                                <td>{{item.pivot.specification}}</td>
                            </tr>
                        </tbody>
                    </q-markup-table>
                  </div>
                  <q-separator class="q-my-sm"/>
                  <div class="row">
                    <div class="col-12">
                        <q-btn v-if="$store.getters.hasPermissionTo('view_sale_order')" label="Sales" class="q-ml-md q-mt-md" color="blue-10" :to="'/customers/sales/'+$route.params.id"/>
                        <q-btn v-if="$store.getters.hasPermissionTo('view_sale_order')" label="Ledger" class="q-ml-md q-mt-md" color="blue-7" :to="'/customers/ledger/'+$route.params.id"/>
                    </div>
                  </div>
                  <q-separator class="q-my-sm"/>
                  </div>
            </div>
        </q-card-section>
    </q-card>
    <page-toolbar :page-title="''" :buttons="toolbarButtons"
        v-on:sendforapproval="sendForApproval"
        v-on:approve="approve"
        v-on:activate="activate"
        v-on:revert="revert"
        v-on:disable="disable" class="q-mt-md"/>
  </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'CustomersView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      name: null,
      status: null,
      type: null,
      gst_number: null,
      addresses: [],
      linked: [],
      representatives: [],
      tags: [],
      emails: [],
      default_shipping: null,
      non_billable_account: null,
      prevRoute: null,
      initial_balance: null,
      balance: 0,
      transactions: []
    }
  },
  beforeRouteEnter (to, from, next) {
    next(vm => {
      vm.prevRoute = from
    })
  },
  mounted () {
    this.$q.loading.show()
    this.$axios.get('customers/' + this.$route.params.id).then((res) => {
      this.name = res.data.name
      this.status = res.data.status
      this.type = res.data.type
      this.gst_number = res.data.gst_number
      this.addresses = res.data.addresses
      this.linked = res.data.linked
      this.representatives = res.data.representatives
      this.tags = res.data.tags
      this.emails = res.data.emails
      this.default_shipping = res.data.default_shipping
      this.non_billable_account = res.data.non_billable_account
      this.initial_balance = res.data.initial_balance
      this.balance = res.data.balance
      this.transactions = res.data.transactions
    }).finally(() => {
      this.$store.commit('setPageTitle', 'Customer: ' + this.name)
      this.$q.loading.hide()
    })
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_sale_order') && (this.status === 'Active' || this.status === 'Approved')) {
        arr.push({
          label: 'Create Sale Order',
          id: 'createso',
          type: 'a',
          link: '/sale_orders/create/' + this.$route.params.id,
          color: 'blue-10',
          icon: 'add'
        })
      }
      if (this.$store.getters.hasPermissionTo('create_sale_order') && this.status !== 'Disabled') {
        arr.push({
          label: 'Create Quotation',
          id: 'createqo',
          type: 'a',
          link: '/quotations/create/' + this.$route.params.id,
          color: 'blue-7',
          icon: 'add'
        })
      }
      if (this.$store.getters.hasPermissionTo('approve_customer') && this.status !== 'Disabled') {
        arr.push({
          label: 'Disable',
          id: 'disable',
          type: 'button',
          color: 'red-10',
          icon: 'close'
        })
      }
      if (this.$store.getters.hasPermissionTo('create_customer') && this.status === 'Draft') {
        arr.push({
          label: 'Send for Approval',
          id: 'sendforapproval',
          type: 'button',
          color: 'orange-10',
          icon: 'pan_tool'
        })
      }
      if (this.$store.getters.hasPermissionTo('revert_customer') && this.status !== 'Draft') {
        arr.push({
          label: 'Revert',
          id: 'revert',
          type: 'button',
          color: 'grey-10',
          icon: 'undo'
        })
      }
      if (this.$store.getters.hasPermissionTo('approve_customer') && this.status === 'Pending Approval') {
        arr.push({
          label: 'Approve',
          id: 'approve',
          type: 'button',
          color: 'green-10',
          icon: 'check'
        })
      }
      if (this.$store.getters.hasPermissionTo('activate_customer') && this.status !== 'Active') {
        arr.push({
          label: 'Activate',
          id: 'activate',
          type: 'button',
          color: 'green-10',
          icon: 'check'
        })
      }
      if (this.$store.getters.hasPermissionTo('update_customer')) {
        arr.push({
          label: 'Edit',
          id: 'edit',
          type: 'a',
          link: '/customers/edit/' + this.$route.params.id,
          color: 'teal',
          icon: 'edit'
        })
      }
      return arr
    },
    breadcrumbs () {
      if (this.prevRoute && this.prevRoute.name === 'PhonesView') {
        return [
          { label: 'Dashboard', link: '/' },
          { label: 'Phones', link: '/phones' },
          { label: this.$route.params.name, link: '/phones/view/' + this.prevRoute.params.id },
          { label: 'Customers', link: '/customers' },
          { label: this.name }
        ]
      } else {
        const arr = [
          { label: 'Dashboard', link: '/' },
          { label: 'Customers', link: '/customers' },
          { label: this.name }
        ]
        return arr
      }
    }

  },
  methods: {
    getBillingAddress () {
      let addressIndex = null
      this.addresses.forEach((address, i) => {
        if (address.tag_name === 'billing') { addressIndex = i }
      })
      if (addressIndex != null) { return this.addressFormat(this.addresses[addressIndex]) }
      return ''
    },
    addressFormat (addr) {
      let str = ''
      str += addr.line_1 + ', '
      if (addr.line_2) { str += addr.line_2 + ', ' }
      if (addr.district) {
        str += addr.district + ', '
      }
      if (addr.pin) { str += 'PIN: ' + addr.pin + ', ' }
      str += addr.state + ', '
      str += addr.country + ', '
      addr.phones.forEach((item) => {
        str += '(' + item.country_code + ')' + item.content + ', '
      })
      str = str.substr(0, str.length - 2)
      return str
    },
    getRepresentatives () {
      let str = ''
      this.representatives.forEach((rep, i) => {
        str += rep.name + ', '
      })
      str = str.substr(0, str.length - 2)
      return str
    },
    getTags () {
      let str = ''
      this.tags.forEach((tag, i) => {
        str += tag.content + ', '
      })
      if (str.length > 0) { str = str.substr(0, str.length - 2) }
      return str
    },
    sendForApproval () {
      this.$axios.get('customers/request_approval/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.push('/customers')
        }
      })
    },
    approve () {
      this.$axios.get('customers/approve/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.push('/customers')
        }
      })
    },
    activate () {
      this.$axios.get('customers/activate/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.push('/customers')
        }
      })
    },
    revert () {
      this.$axios.get('customers/revert/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.push('/customers')
        }
      })
    },
    disable () {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like disable this customer?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.$axios.get('customers/disable/' + this.$route.params.id).then((res) => {
          if (res.data.message === 'success') {
            this.$q.notify({
              type: 'positive',
              message: 'Data Updated Successfully'
            })
            this.$router.push('/customers')
          }
        })
      })
    }
  }
}
</script>
