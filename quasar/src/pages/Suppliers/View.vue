<template>
    <q-page>
        <page-toolbar :page-title="name+' Details' " :buttons="toolbarButtons" v-on:sendtoaccounts="sendToAccounts()"
         v-on:activate="activate()"
         v-on:revert="revert()"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-xs-12 col-md-6">
                            <q-markup-table separator="cell">
                                <tbody>
                                    <tr >
                                        <td>Status</td>
                                        <td>{{status}}</td>
                                    </tr>
                                    <tr >
                                        <td>Name</td>
                                        <td>{{name}}</td>
                                    </tr>
                                    <tr >
                                        <td>GST No</td>
                                        <td>{{gst_number}}</td>
                                    </tr>
                                    <tr v-for="(email,i) in emails" :key="i+'email'">
                                        <td><div v-if="i==0">Emails</div></td>
                                        <td>{{email.content}}</td>
                                    </tr>
                                    <tr v-if="billing_address">
                                        <td>Address</td>
                                        <td>{{addressFormat(billing_address)}}</td>
                                    </tr>
                                    <tr >
                                        <td>Created At</td>
                                        <td>{{created_at}}</td>
                                    </tr>
                                </tbody>
                            </q-markup-table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col q-pl-sm q-mt-md">
                            <div class="text-subtitle1">Remarks</div>
                            <div class="text" v-html="remarks"></div>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </div>
         <page-toolbar :page-title="''" v-on:sendtoaccounts="sendToAccounts()"
         v-on:activate="activate()" v-on:revert="revert()" class="q-mt-md"/>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SuppliersView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      name: null,
      status: null,
      gst_number: null,
      emails: [],
      billing_address: null,
      remarks: null,
      created_at: null
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('approve_supplier') && this.status === 'Pending Activation') {
        arr.push({
          label: 'Activate',
          id: 'activate',
          type: 'button',
          color: 'green-10',
          icon: 'check'
        })
      }
      if (this.$store.getters.hasPermissionTo('approve_supplier') && this.status === 'Active') {
        arr.push({
          label: 'Revert',
          id: 'revert',
          type: 'button',
          color: 'grey-10',
          icon: 'undo'
        })
      }
      if (this.$store.getters.hasPermissionTo('create_supplier') && this.status === 'Draft') {
        arr.push({
          label: 'Send to Accounts',
          id: 'sendtoaccounts',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
      }
      if (this.$store.getters.hasPermissionTo('update_supplier') && this.status === 'Draft') {
        arr.push({
          label: 'Edit',
          id: 'edit',
          type: 'a',
          link: '/suppliers/edit/' + this.$route.params.id,
          color: 'teal',
          icon: 'edit'
        })
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Suppliers', link: '/suppliers' },
        { label: this.name, link: '' }
      ]
      return arr
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('viewAny_supplier')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.getDataFromApi()
    }
  },
  methods: {
    getDataFromApi () {
      this.$q.loading.show()
      this.$axios.get('suppliers/' + this.$route.params.id).then((res) => {
        this.name = res.data.name
        this.status = res.data.status
        this.gst_number = res.data.gst_number
        this.billing_address = res.data.addresses[0]
        this.emails = res.data.emails
        this.created_at = this.getLocaleString(res.data.created_at)
        this.remarks = res.data.remarks
      }).finally(() => {
        this.$store.commit('setPageTitle', 'Supplier: ' + this.name)
        this.$q.loading.hide()
      })
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
    sendToAccounts () {
      this.$axios.get('suppliers/request_activation/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$router.back()
          this.$q.notify({
            color: 'orange-10',
            message: 'Supplier is sent for activation'
          })
        }
      })
    },
    activate () {
      this.$axios.get('suppliers/activate/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$router.back()
          this.$q.notify({
            type: 'positive',
            message: 'Supplier is activated'
          })
        }
      })
    },
    revert () {
      this.$axios.get('suppliers/revert/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$router.back()
          this.$q.notify({
            color: 'grey-10',
            message: 'Supplier is reverted successfully'
          })
        }
      })
    }
  }
}
</script>
