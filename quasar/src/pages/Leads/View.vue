<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons"
    v-on:converttocustomer="convert()"
        />
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <q-card>
        <q-card-section>
            <div class="row q-col-gutter-md">
                <div class="col-xs-12 col-md-6">
                    <q-markup-table separator="cell" wrap-cells>
                        <tbody>
                            <tr>
                                <td>Status</td>
                                <td>{{status}}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{name}}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{type}}</td>
                            </tr>
                            <tr>
                                <td>GST No.</td>
                                <td>{{gst_number}}</td>
                            </tr>
                            <tr>
                                <td v-if="representatives.length == 1">
                                    Representatives
                                </td>
                                <td>
                                    {{getRepresentatives()}}
                                </td>
                            </tr>
                            <tr v-for="(email,i) in emails" :key="'email-'+i">
                                <td><div v-if="i==0">Emails</div></td>
                                <td>{{email.content}}</td>
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
                            <tr>
                                <td>Created At</td>
                                <td>{{getLocaleString(this.created_at)}}</td>
                            </tr>
                            <tr>
                                <td>Updated At</td>
                                <td>{{getLocaleString(this.updated_at)}}</td>
                            </tr>
                        </tbody>
                    </q-markup-table>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="row">
                        <div class="col">
                            <q-btn label="Add Remarks" @click="remarkDialog = true" color="primary"/>
                            <q-btn label="Update Status" @click="statusDialog = true" color="secondary" class="q-ml-sm"/>
                        </div>
                    </div>
                    <div v-if="linked.length > 0" class="text-subtitle2 q-mb-md">Relations</div>
                    <q-markup-table separator="cell" wrap-cells v-if="linked.length > 0">
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
                    <div class="text-subtitle2 q-my-md">Remarks</div>
                    <div class="row">
                        <div class="col-12">
                            <div class="text" v-html="remarks"></div>
                        </div>
                    </div>
                </div>
            </div>
        </q-card-section>
    </q-card>
    <q-dialog persistent v-model="remarkDialog">
        <q-card style="width:550px">
            <q-card-section>
                <div class="text-subtitle1">Remark</div>
            </q-card-section>
            <q-card-section>
                <q-editor v-model="new_remark" rows="5"/>
            </q-card-section>
            <q-card-actions>
                <q-btn color="primary" @click="submitRemark" label="Submit"/>
                <q-btn color="primary" flat @click="new_remark='';remarkDialog = false" label="Cancel"/>
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog persistent v-model="statusDialog">
        <q-card style="width:550px;">
            <q-card-section>
                <div class="text-subtitle1">Update Status</div>
            </q-card-section>
            <q-card-section>
                <q-option-group
                    :options="statusOptions"
                    type="radio"
                    v-model="status"
                />
            </q-card-section>
            <q-card-actions>
                <q-btn color="primary" @click="submitStatus" label="Submit"/>
                <q-btn color="primary" flat @click="statusDialog = false" label="Cancel"/>
            </q-card-actions>
        </q-card>
    </q-dialog>
    <page-toolbar :page-title="''" :buttons="toolbarButtons"
         class="q-mt-md"/>
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
      remarks: '',
      new_remark: '',
      remarkDialog: false,
      statusDialog: false,
      created_at: null,
      updated_at: null,
      statusOptions: [
        { label: 'Draft', value: 'Draft' },
        { label: 'Attempting Contact', value: 'Attempting Contact' },
        { label: 'Working', value: 'Working' },
        { label: 'Qualified', value: 'Qualified' },
        { label: 'Disqualified', value: 'Disqualified' },
        { label: 'Reattempt Required', value: 'Reattempt Required' }
      ]
    }
  },
  beforeRouteEnter (to, from, next) {
    next(vm => {
      vm.prevRoute = from
    })
  },
  mounted () {
    this.getDataFromApi()
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('update_lead') && this.status === 'Draft') {
        arr.push({
          label: 'Convert To Customer',
          id: 'converttocustomer',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
        arr.push({
          label: 'Edit',
          id: 'edit',
          type: 'a',
          link: '/leads/edit/' + this.$route.params.id,
          color: 'teal',
          icon: 'edit'
        })
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Leads', link: '/leads' },
        { label: this.name }
      ]
      return arr
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
      if (addr.pin) { str += addr.pin + ', ' }
      if (addr.district) {
        str += addr.district + ', '
      }
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
    submitRemark () {
      if (this.new_remark.length > 0) {
        this.$axios.post('customers/remark/' + this.$route.params.id, {
          remark: this.new_remark
        }).then((res) => {
          if (res.data.message === 'success') {
            this.getDataFromApi()
            this.$q.notify({
              type: 'positive',
              message: 'Remark updated'
            })
            this.new_remark = ''
            this.remarkDialog = false
          }
        })
      } else {
        this.new_remark = ''
        this.remarkDialog = false
      }
    },
    submitStatus () {
      this.$axios.post('customers/status/' + this.$route.params.id, {
        status: this.status
      }).then((res) => {
        if (res.data.message === 'success') {
          this.getDataFromApi()
          this.$q.notify({
            type: 'positive',
            message: 'Status updated'
          })
          this.statusDialog = false
        }
      })
    },
    getDataFromApi () {
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
        this.remarks = res.data.remarks
        this.created_at = res.data.created_at
        this.updated_at = res.data.updated_at
      }).finally(() => {
        this.$store.commit('setPageTitle', 'Leads: ' + this.name)
        this.$q.loading.hide()
      })
    },
    getLocaleString (val) {
      if (val) {
        const d = new Date(val)
        return d.toLocaleString('en-IN')
      }
      return ''
    },
    convert () {
      this.$axios.get('customers/convert/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$router.push({ path: '/customers/view/' + this.$route.params.id })
        }
      }).catch((error) => {
        if (error.response.status === 422) {
          this.$q.notify({ type: 'negative', message: 'Data incomlete' })
        }
      })
    }
  }
}
</script>
