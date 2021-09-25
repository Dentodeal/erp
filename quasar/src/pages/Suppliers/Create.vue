<template>
    <q-page>
        <page-toolbar  :buttons="toolbarButtons"
            v-on:save="saveFn()"
            v-on:sendtoaccounts="sendToAccounts()"
            />
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-xs-12 col-md-6">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input
                                        square
                                        dense
                                        outlined
                                        v-model="name"
                                        label="Name"
                                        ref="name"
                                        :rules="[val => !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        :error-message="errors.name"
                                        :error="errors.name.length > 0"
                                        @blur="name = capitaliseFn(name)"
                                        />
                                </div>
                            </div>
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input square  dense outlined v-model="gst_number" label="GST No." ref="gst_number" :error-message="gst_error_message" :error="gst_error_message.length > 0"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md q-mt-xs" v-for="(item,index) in emails" :key="index+'email'">
                        <div class="col-xs-12 col-md-6">
                            <div class="row">
                                <q-input
                                    class="col-10"
                                    dense
                                    v-model="emails[index].content"
                                    :label="'Email '+ (index+1)"
                                    outlined
                                    square
                                    :error-message="emails_errors[index].message"
                                    :error="emails_errors[index].message.length > 0"
                                    @input="validateEmails"
                                    />
                                <div class="col-2">
                                    <q-btn icon="delete" class="q-mt-sm" round flat v-if="emails.length > 1" @click="emails.splice(index,1);emails_errors.splice(index,1)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <q-btn class="" color="secondary" label="Add Email" flat @click="emails.push({id:emails.length+1,content:''}); emails_errors.push({message:''})"/>
                    </div>
                    <div class="text-subtitle1 q-mt-xs"> Billing Address</div>
                    <div class="row q-col-gutter-md" >
                        <div class="col-xs-12 col-md-6">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input
                                        square
                                        dense
                                        outlined
                                        v-model="billing_address.line_1"
                                        label="Line 1" ref="billing_address_line_1"
                                        :rules="[val => !!val || 'Required']" lazy-rules="ondemand"
                                        @blur="billing_address.line_1 = capitaliseFn(billing_address.line_1)"
                                        />
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input square dense outlined v-model="billing_address.line_2" label="Line 2" @blur="billing_address.line_2 = capitaliseFn(billing_address.line_2)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md">
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input square dense outlined v-model="billing_address.pin" label="Pin / Zip Code" ref="billing_address_pin" :rules="[billingPinRule]" lazy-rules="ondemand"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-select
                                        use-input
                                        dense
                                        fill-input
                                        hide-selected
                                        square
                                        outlined
                                        @filter="countryfilterFn"
                                        :options="country_options"
                                        ref="billing_address_country" :rules="[val =>  !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        v-model="billing_address.country"
                                        @input="loadBillingState"
                                        option-label="name"
                                        option-value="id"
                                        label="Country"
                                        >
                                    </q-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-select
                                        use-input
                                        dense
                                        fill-input
                                        hide-selected
                                        square
                                        outlined
                                        :disable="!billing_address.country"
                                        :options="state_options"
                                        @filter="statefilterFn"
                                        ref="billing_address_state" :rules="[val => !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        :loading="billing_address_state_loading"
                                        option-label="name"
                                        option-value="id"
                                        v-model="billing_address.state"
                                        @input="loadBillingDistrict"
                                        label="State"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input
                                        v-if="billing_address.country != 'India'"
                                        square
                                        dense
                                        outlined
                                        v-model="billing_address.district"
                                        ref="billing_address.district"
                                        label="District"
                                    />
                                    <q-select
                                        v-else
                                        use-input
                                        dense
                                        fill-input
                                        hide-selected
                                        square
                                        outlined
                                        :disable="!billing_address.state"
                                        ref="billing_address_district" :rules="[billingDistrictRule]"
                                        lazy-rules="ondemand"
                                        @filter="districtfilterFn"
                                        :options="district_options"
                                        option-label="name"
                                        option-value="id"
                                        :loading="billing_address_district_loading"
                                        v-model="billing_address.district"
                                        label="District"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md" v-for="(item,index) in billing_address.phones" :key="index">
                        <div class="col-xs-12 col-md-6">
                            <div class="row">
                                <div class="col-3">
                                    <q-select
                                        v-model="billing_address.phones[index].country_code"
                                        :options="phonecodes"
                                        dense
                                        emit-value
                                        outlined square
                                        label="Code"
                                        />
                                </div>
                                <q-input
                                    class="col-7"
                                    :ref="'billing_phone_'+index"
                                    v-model="billing_address.phones[index].content"
                                    :label="'Phone '+ (index+1)"
                                    outlined
                                    square
                                    dense
                                    :error-message="billing_address_phones_errors[index].message"
                                    :error="billing_address_phones_errors[index].message.length > 0"
                                    @input="validateBillingPhones"
                                    />
                                <div class="col-2">
                                    <q-btn icon="delete" class="q-mt-sm" round flat v-if="billing_address.phones.length > 1" @click="billing_address.phones.splice(index,1);billing_address_phones_errors.splice(index,1)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <q-btn class="" color="secondary" label="Add Phone" flat @click="addBillingPhone"/>
                    <div class="text-subtitle1">Remarks</div>
                    <q-editor v-model="remarks" min-height="5rem" />
                </q-card-section>
            </q-card>
        </div>
        <page-toolbar :page-title="''" :buttons="toolbarButtons" v-on:save="saveFn()"
            v-on:sendtoaccounts="sendToAccounts()" class="q-mt-md"/>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'CustomersCreate',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      name: null,
      status: 'Draft',
      gst_number: '',
      gst_error_message: '',
      errors: {
        name: ''
      },
      emails: [{
        id: 1,
        content: ''
      }],
      emails_errors: [{
        message: ''
      }],
      billing_address: {
        line_1: null,
        line_2: null,
        pin: null,
        district: null,
        state: null,
        country: '',
        phones: [{
          id: 1,
          content: '',
          country_code: '+91'
        }]
      },
      billing_address_district_loading: false,
      billing_address_state_loading: false,
      billing_address_phones_errors: [{
        message: ''
      }],
      district_options: [],
      state_options: [],
      country_options: [],
      countries: [],
      phonecodes: [],
      states: [],
      districts: [],
      remarks: ''
    }
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Suppliers', link: '/suppliers' },
        { label: this.$route.params.id ? this.name : 'Create', link: '/suppliers/view/' + this.$route.params.id }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
      }
      return arr
    },
    toolbarButtons () {
      const arr = []

      if (this.$store.getters.hasPermissionTo('tally_supplier')) {
        arr.push({
          label: 'Send to Accounts',
          id: 'sendtoaccounts',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
      }

      arr.push({
        label: 'Save as Draft',
        id: 'save',
        type: 'button',
        color: 'teal',
        icon: 'save'
      })
      return arr
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('create_customer')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      this.billing_address.country = 'India'
      this.loadBillingState()
      Promise.all([
        this.$axios.get('countries').then((res) => {
          this.countries = res.data
        }),
        this.$axios.get('phonecodes').then((res) => {
          this.phonecodes = res.data
        })
      ]).then(() => {
        if (this.$route.params.id) {
          this.$axios.get('suppliers/' + this.$route.params.id).then((res) => {
            // console.log(res.data)
            this.name = res.data.name
            this.status = res.data.status
            this.gst_number = res.data.gst_number
            this.remarks = res.data.remarks ? res.data.remarks : ''
            const emails = []
            res.data.emails.forEach((item) => {
              this.emails_errors.push({ message: '' })
              emails.push({
                id: item.id,
                content: item.content
              })
            })
            if (emails.length > 0) { this.emails = emails }
            res.data.addresses.forEach((item) => {
              if (item.tag_name === 'billing') {
                const phones = []
                item.phones.forEach((phone) => {
                  this.billing_address_phones_errors.push({ message: '' })
                  phones.push({
                    id: phone.id,
                    content: phone.content,
                    country_code: phone.country_code
                  })
                })
                this.billing_address = {
                  line_1: item.line_1,
                  line_2: item.line_2,
                  pin: item.pin,
                  country: item.country,
                  state: item.state,
                  district: item.district,
                  phones: phones
                }
              }
            })
          }).finally(() => {
            this.$store.commit('setPageTitle', 'Edit Supplier : ' + this.name)
          })
        } else {
          this.$store.commit('setPageTitle', 'Create Supplier')
        }
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  },
  methods: {
    countryfilterFn (val, update, abort) {
      update(() => {
        const needle = val.toLocaleLowerCase()
        this.country_options = this.countries.filter(v => v.toLocaleLowerCase().indexOf(needle) > -1)
      })
    },
    statefilterFn (val, update, abort) {
      update(() => {
        const needle = val.toLocaleLowerCase()
        this.state_options = this.states.filter(v => v.toLocaleLowerCase().indexOf(needle) > -1)
      })
    },
    districtfilterFn (val, update, abort) {
      update(() => {
        const needle = val.toLocaleLowerCase()
        this.district_options = this.districts.filter(v => v.toLocaleLowerCase().indexOf(needle) > -1)
      })
    },
    billingPinRule (val) {
      if (this.billing_address.country === 'India') {
        if (!val) {
          return 'Required'
        } else if (isNaN(val)) {
          return 'PIN must be numeric'
        } else if (val.length !== 6) {
          return 'PIN must be exactly 6 digits'
        }
        return true
      }
      return true
    },
    billingDistrictRule (val) {
      if (this.billing_address.country === 'India') {
        return (val && val.length > 0) || 'Required'
      }
      return true
    },
    resetBillingPhonesErrors () {
      this.billing_address_phones_errors.forEach((item, index) => {
        this.$set(this.billing_address_phones_errors[index], 'message', '')
      })
    },
    resetEmails () {
      this.emails_errors.forEach((item, index) => {
        this.$set(this.emails_errors[index], 'message', '')
      })
    },
    validateEmails () {
      let result = false
      this.resetEmails()
      // eslint-disable-next-line no-useless-escape
      const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      this.emails.forEach((email, index) => {
        if (email.content) {
          if (re.test(String(email.content).toLowerCase())) {
            result = true
          } else {
            this.$set(this.emails_errors[index], 'message', 'Invalid Email')
            result = false
          }
        }
        result = true
      })
      return result
    },
    validateGst () {
      this.gst_error_message = ''
      if (this.has_gst && this.gst_number.length < 1) {
        this.gst_error_message = 'Required'
        return false
      }
      return true
    },
    saveFn (status = 'Draft') {
      if (this.validateFn()) {
        this.errors.name = ''
        this.emails_errors.forEach((item, i) => {
          this.emails_errors[i].message = ''
        })
        this.billing_address_phones_errors.forEach((item, i) => {
          this.billing_address_phones_errors[i].message = ''
        })
        const obj = {
          _method: this.$route.params.id ? 'PUT' : 'POST',
          status: status,
          remarks: this.remarks
        }
        obj.name = this.name
        obj.gst_number = this.has_gst ? this.gst_number : ''
        obj.billing_address = this.billing_address
        obj.emails = this.emails
        let route = 'suppliers'
        if (this.$route.params.id) {
          route = 'suppliers/' + this.$route.params.id
        }
        this.$q.loading.show()
        this.$axios.post(route, obj).then((res) => {
          if (res.data.message === 'success') {
            this.$q.loading.hide()
            this.$q.notify({
              type: 'positive',
              message: 'Saved Succesfully.'
            })
            this.$router.back()
          }
        }).catch((error) => {
          this.$q.loading.hide()
          if (error.response.status === 422) {
            this.$q.notify({
              type: 'negative',
              message: error.response.data.message
            })
            Object.keys(error.response.data.errors).forEach((key, i) => {
              if (key.indexOf('emails') !== -1) {
                this.emails_errors[Number(key.substr(key.indexOf('emails') + 7))].message = error.response.data.errors[key][0]
              } else if (key.indexOf('name') !== -1) {
                this.errors.name = error.response.data.errors[key][0]
              } else if (key.indexOf('billing_address.phones') !== -1) {
                this.billing_address_phones_errors[Number(key.substr(key.indexOf('billing_address.phones') + 23))].message = error.response.data.errors[key][0]
              }
            })
          }
        }).finally(() => {
          this.$q.loading.hide()
        })
      } else {
        this.$q.notify({
          type: 'negative',
          message: 'The form is incomplete. Please verify'
        })
      }
    },
    sendToAccounts () {
      this.saveFn('Pending Activation')
    },
    validateFn () {
      return this.$refs.name.validate() &
            this.validateEmails() &
            this.validateGst()
    },
    loadBillingState () {
      this.billing_address.state = null
      this.billing_address.district = null
      this.billing_address_state_loading = true
      this.getStates(this.billing_address.country).finally(() => {
        this.billing_address_state_loading = false
      })
    },
    getStates (country) {
      return this.$axios.get('states/' + encodeURIComponent(country)).then((res) => {
        this.states = res.data
      })
    },
    loadBillingDistrict () {
      if (this.billing_address.state) {
        this.billing_address.district = null
        this.billing_address_district_loading = true
        this.getDistricts(this.billing_address.state).finally(() => {
          this.billing_address_district_loading = false
        })
      }
    },
    getDistricts (state) {
      return this.$axios.get('districts/' + encodeURIComponent(state)).then((res) => {
        this.districts = res.data
      })
    },
    addBillingPhone () {
      this.billing_address.phones.push({
        id: this.billing_address.phones.length + 1,
        content: '',
        country_code: '+91'
      })
      this.billing_address_phones_errors.push({
        message: ''
      })
      this.$nextTick(() => {
        const ind = this.billing_address.phones.length - 1
        const key = 'billing_phone_' + ind
        this.$refs[key][0].focus()
      })
    },
    capitaliseFn (str) {
      if (str) {
        const splitStr = str.split(' ')
        for (let i = 0; i < splitStr.length; i++) {
          splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1)
        }
        return splitStr.join(' ')
      }
      return ''
    }
  }
}
</script>
