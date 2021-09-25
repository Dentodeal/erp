<template>
    <q-page>
        <page-toolbar :buttons="toolbarButtons"
            v-on:save="saveFn()" v-on:activate="activate()" v-on:sendforapproval="sendforapproval"/>
        <breadcrumbs :breadcrumbs="breadcrumbs"/>
        <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
            <q-card>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-xs-12 col-md-6">
                            <div class="row q-mt-md">
                                <div class="col">
                                    <q-select
                                        outlined
                                        square
                                        dense
                                        v-model="type"
                                        :options="type_options"
                                        :label="getTypeLabel"
                                        ref="type"
                                        tabindex="1"
                                        :rules="[val => !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        />
                                </div>
                                <div class="col">
                                    <q-input v-if="!isLead" dense outlined square readonly v-model="status" label="Status"/>
                                </div>
                            </div>
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input
                                        tabindex="2"
                                        square
                                        dense
                                        outlined
                                        v-model="name"
                                        label="Name"
                                        :disable="status != 'Draft'"
                                        ref="name"
                                        :rules="[val => !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        :error-message="errors.name"
                                        :error="errors.name.length > 0"
                                        @blur="name = capitaliseFn(name)"
                                        />
                                </div>
                            </div>
                            <div class="row q-mt-md">
                                <div class="col">
                                    <q-input square dense
                                      :disable="!has_gst" outlined
                                      v-model="gst_number" label="GST No."
                                      ref="gst_number"
                                      :error-message="gst_error_message"
                                      :error="gst_error_message.length > 0"/>
                                </div>
                                <div class="col">
                                    <q-checkbox  v-model="has_gst" label="Has GST?" @input="$refs.gst_number.focus()"/>
                                </div>
                                <div class="col">
                                  <q-input class="input-right"
                                    v-model.number="initial_balance"
                                    label="Initial Balance" outlined square dense
                                    ref="initial_balance"
                                    @focus="$refs.initial_balance.select()"
                                    :rules="[v => !isNaN(v) || 'Invalid', v => !(v === null || v === '') || 'Invalid']"/>
                                </div>
                            </div>
                            <div class="row q-mt-md">
                                <div class="col">
                                    <q-select
                                        square outlined
                                        dense
                                        v-model="tags" label="Tags"
                                        ref="tags"
                                        :options="tagOptions"
                                        use-input
                                        use-chips
                                        multiple
                                        input-debounce="0"
                                        @new-value="newTag"
                                        @filter="tagFilterFn"/>
                                </div>
                            </div>
                            <div class="row q-mt-md">
                                <div class="col">
                                    <q-select
                                        tabindex="3"
                                        label="Representative"
                                        outlined square
                                        dense
                                        v-model="representatives"
                                        :options="representativeOptions"
                                        option-value="id"
                                        option-label="name"
                                        map-options
                                        emit-value

                                        ref="representative"
                                        :rules="[val => val.length > 0 || 'Required']"
                                        lazy-rules="ondemand"
                                        use-chips
                                        multiple>
                                        <template v-slot:no-option>
                                            <q-item>
                                                <q-item-section class="text-grey">
                                                No results
                                                </q-item-section>
                                            </q-item>
                                        </template>
                                    </q-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="text-subtitle2"> Remarks</div>
                            <q-editor rows="10" v-model="remarks"/>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md q-mt-xs" v-for="(item,index) in emails" :key="index+'email'">
                        <div class="col-xs-12 col-md-6">
                            <div class="row">
                                <q-input
                                    tabindex="4"
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
                    <div class="row" v-if="!isLead">
                        <q-toggle v-model="non_billable_account" label="Non Billable Account"/>
                    </div>
                    <div class="text-subtitle1 q-mt-md" v-if="!non_billable_account"> Billing Address</div>
                    <div v-if="!non_billable_account">
                        <div class="row q-col-gutter-md" >
                            <div class="col-xs-12 col-md-6">
                                <div class="row q-mt-xs">
                                    <div class="col">
                                        <q-input
                                            tabindex="5"
                                            square
                                            outlined
                                            dense
                                            v-model="billing_address.line_1"
                                            label="Line 1" ref="billing_address_line_1"
                                            :rules="[val => (!isLead && !val) ? 'Required':true]" lazy-rules="ondemand"
                                            @blur="billing_address.line_1 = capitaliseFn(billing_address.line_1)"
                                            />
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="row q-mt-xs">
                                    <div class="col">
                                        <q-input tabindex="6" square dense outlined v-model="billing_address.line_2" label="Line 2" @blur="billing_address.line_2 = capitaliseFn(billing_address.line_2)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row q-col-gutter-md">
                            <div class="col-xs-12 col-md-3">
                                <div class="row q-mt-xs">
                                    <div class="col">
                                        <q-input dense square outlined v-model="billing_address.pin" label="Pin / Zip Code"
                                        ref="billing_address_pin" :rules="[billingPinRule]" lazy-rules="ondemand"
                                        tabindex="7"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="row q-mt-xs">
                                    <div class="col">
                                        <q-select
                                            use-input
                                            fill-input
                                            dense
                                            hide-selected
                                            square
                                            outlined
                                            @filter="countryfilterFn"
                                            :options="country_options"
                                            ref="billing_address_country" :rules="[val =>  (!isLead && !val) ? 'Required':true]"
                                            lazy-rules="ondemand"
                                            v-model="billing_address.country"
                                            @input="loadBillingState"
                                            label="Country"
                                            tabindex="8"
                                            >
                                        </q-select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="row q-mt-xs">
                                    <div class="col">
                                        <q-select
                                            square
                                            dense
                                            outlined
                                            :disable="!billing_address.country"
                                            :options="states"
                                            ref="billing_address_state" :rules="[val => (!isLead && !val) ? 'Required':true]"
                                            lazy-rules="ondemand"
                                            :loading="billing_address_state_loading"
                                            v-model="billing_address.state"
                                            @input="loadBillingDistrict"
                                            label="State"
                                            tabindex="9"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <div class="row q-mt-xs">
                                    <div class="col">
                                        <q-input
                                            v-if="billing_address.country != 'India'"
                                            square
                                            outlined
                                            dense
                                            v-model="billing_address.district"
                                            ref="billing_address_district"
                                            label="District"
                                            @blur="billing_address.district = capitaliseFn(billing_address.district)"
                                            tabindex="10"
                                        />
                                        <q-select
                                            v-else
                                            dense
                                            square
                                            outlined
                                            :disable="!billing_address.state"
                                            ref="billing_address_district" :rules="[billingDistrictRule]"
                                            lazy-rules="ondemand"
                                            :options="districts"
                                            :loading="billing_address_district_loading"
                                            v-model="billing_address.district"
                                            label="District"
                                            tabindex="11"
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
                                            emit-value
                                            dense
                                            outlined square
                                            label="Code"
                                            />
                                    </div>
                                    <q-input
                                        class="col-7"
                                        tabindex="12"
                                        :ref="'billing_phone_'+index"
                                        v-model="billing_address.phones[index].content"
                                        :label="'Phone '+ (index+1)"
                                        outlined
                                        dense
                                        square
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
                        <div class="row" v-if="!isLead">
                            <div class="col">
                                <div class="text-subtitle1 q-mt-md"> Shipping Address(es)</div>
                                <q-markup-table v-if="shipping_addresses.length > 0">
                                    <thead>
                                        <th class="text-left">Default</th>
                                        <th>Address</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(addr,i) in shipping_addresses" :key="i">
                                            <td><q-radio v-model="default_shipping" :val="addr.id"/></td>
                                            <td><div class="text-subtitle1">{{addressFormat(addr)}}</div></td>
                                            <td>
                                                <q-btn round flat icon="edit" @click="editAddress(i)"/>
                                                <q-btn round flat icon="delete" @click="deleteAddress(i)"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </q-markup-table>
                                <q-btn color="secondary" class="q-mt-md" @click="openShippingDialog" label="Add Shipping Address"/>
                                <q-btn color="secondary" class="q-mt-md" @click="duplicateBilling" flat tabindex="13" label="Copy Billing Address"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="text-subtitle1 q-mt-md"> Linked Accounts</div>
                            <div class="row q-col-gutter-sm" v-for="(acc,i) in linked_accounts" :key="i">
                                <div class="col">
                                    <q-select label="Relation Type" :options="relationOptions" v-model="linked_accounts[i].relation_type" outlined square/>
                                </div>
                                <div class="col">
                                    <q-select
                                        use-input
                                        :disable="linked_accounts[i].relation_type == null"
                                        v-model="linked_accounts[i].account"
                                        outlined
                                        square
                                        dense
                                        @filter="(val,update,abort)=>{customerFilterFn(val,update,abort,i)}"
                                        label="Search Customer"
                                        option-value="id"
                                        option-label="name"
                                        :options="customerOptions"
                                        >
                                        <template v-slot:no-option>
                                            <q-item>
                                                <q-item-section class="text-grey">
                                                No results
                                                </q-item-section>
                                            </q-item>
                                        </template>
                                    </q-select>
                                </div>
                                <div class="col">
                                    <q-input dense label="Specify Relation" v-model="linked_accounts[i].specification" outlined square/>
                                </div>
                                <div class="col-1">
                                    <q-btn round flat icon="delete" @click="linked_accounts.splice(i,1)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <q-btn class="" flat color="primary" label="Add Relation" @click="linked_accounts.push({relation_type:null,account:null,specification:null})"/>
                </q-card-section>
            </q-card>
        </div>
        <q-dialog v-model="shippingDialog" full-width persistent>
            <q-card>
                <q-bar dark>
                    <q-space />
                    <q-btn dense flat icon="close" @click="closeShippigDialog">
                        <q-tooltip>Close</q-tooltip>
                    </q-btn>
                </q-bar>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-xs-12 col-md-6">
                            <div class="row q-mt-md">
                                <div class="col">
                                    <q-input square outlined dense
                                        v-model="shipping_address.line_1"
                                        label="Line 1"
                                        ref="shipping_address_line_1"
                                        :rules="[val => !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        @blur="shipping_address.line_1 = capitaliseFn(shipping_address.line_1)"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="row q-mt-md">
                                <div class="col">
                                    <q-input dense square outlined v-model="shipping_address.line_2" label="Line 2" @blur="shipping_address.line_2 = capitaliseFn(shipping_address.line_2)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md">
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input square outlined dense
                                        v-model="shipping_address.pin" label="Pin / Zip Code" ref="shipping_address_pin" :rules="[shippingPinRule]" lazy-rules="ondemand"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-select
                                        use-input
                                        fill-input
                                        hide-selected
                                        dense
                                        square
                                        outlined
                                        @filter="countryfilterFn"
                                        :options="country_options"
                                        v-model="shipping_address.country"
                                        ref="shipping_address_country" :rules="[val => !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        option-label="name"
                                        option-value="id"
                                        label="Country"
                                        @input="loadShippingState"
                                        >
                                    </q-select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-select
                                        square
                                        dense
                                        outlined
                                        :options="states"
                                        :loading="shipping_address_state_loading"
                                        ref="shipping_address_state" :rules="[val => !!val || 'Required']"
                                        lazy-rules="ondemand"
                                        v-model="shipping_address.state"
                                        @input="loadShippingDistrict"
                                        label="State"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="row q-mt-xs">
                                <div class="col">
                                    <q-input
                                        v-if="shipping_address.country != 'India'"
                                        square
                                        dense
                                        outlined
                                        ref="shipping_address_district"
                                        v-model="shipping_address.district"
                                        label="District"
                                        @blur="shipping_address.district = capitaliseFn(shipping_address.district)"
                                    />
                                    <q-select
                                        v-else
                                        square
                                        dense
                                        outlined
                                        ref="shipping_address_district" :rules="[shippingDistrictRule]"
                                        lazy-rules="ondemand"
                                        :options="districts"
                                        :loading="shipping_address_district_loading"
                                        v-model="shipping_address.district"
                                        label="District"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row q-col-gutter-md " v-for="(item,index) in shipping_address.phones" :key="index">
                        <div class="col-xs-12 col-md-6">
                            <div class="row">
                                <div class="col-3">
                                    <q-select
                                        v-model="shipping_address.phones[index].country_code"
                                        :options="phonecodes"
                                        emit-value
                                        dense
                                        outlined square
                                        label="Code"
                                        />
                                </div>
                                <q-input
                                    class="col-7"
                                    :ref="'shipping_phone_'+index"
                                    v-model="shipping_address.phones[index].content"
                                    :label="'Phone '+ (index+1)"
                                    outlined
                                    dense
                                    square
                                    :error-message="shipping_address_phones_errors[index].message"
                                    :error="shipping_address_phones_errors[index].message.length > 0"
                                    @input="validateShippingPhones"
                                    />
                                <div class="col-2">
                                    <q-btn icon="delete" class="q-mt-sm" round flat v-if="shipping_address.phones.length > 1" @click="shipping_address.phones.splice(index,1);shipping_address_phones_errors.splice(index,1)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <q-btn class="" color="secondary" label="Add Phone" flat @click="addShippingPhone()"/>
                </q-card-section>
                <q-card-actions>
                    <q-btn label="Submit" color="green"  @click="addShipping"/>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <page-toolbar :page-title="''" :buttons="toolbarButtons" v-on:save="saveFn()"
          v-on:activate="activate()" class="q-mt-md"
          v-on:sendforapproval="sendforapproval"/>
    </q-page>
</template>
<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'CustomerForm',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  props: {
    isLead: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      name: null,
      type: null,
      status: 'Draft',
      type_options: [],
      gst_number: '',
      gst_error_message: '',
      has_gst: false,
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
      shipping_address: {
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
      shipping_address_district_loading: false,
      shipping_address_state_loading: false,
      shipping_address_phones_errors: [{
        message: ''
      }],
      default_shipping: null,
      district_options: [],
      state_options: [],
      country_options: [],
      shipping_addresses: [],
      countries: [],
      phonecodes: [],
      states: [],
      districts: [],
      shippingDialog: false,
      address_ids_to_delete: [],
      edit_address_index: null,
      linked_accounts: [],
      customerOptions: [],
      customerSearch: null,
      non_billable_account: false,
      representatives: [],
      representativeOptions: [],
      tags: [],
      tagOptions: [],
      remarks: '',
      initial_balance: 0
    }
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: this.isLead ? 'Leads' : 'Customers', link: this.isLead ? '/leads' : '/customers' },
        { label: this.$route.params.id ? this.name : 'Create', link: this.isLead ? '/leads/view/' + this.$route.params.id : '/customers/view/' + this.$route.params.id }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
      }
      return arr
    },
    relationOptions () {
      return ['Works here', 'Sibling branch of', 'Owner of', 'Related to', 'Works at', 'Belongs to']
    },
    toolbarButtons () {
      const arr = []
      if (!this.isLead) {
        if (this.$store.getters.hasPermissionTo('activate_customer')) {
          arr.push({
            label: 'Activate and Save',
            id: 'activate',
            type: 'button',
            color: 'green-10',
            icon: 'check'
          })
        }
        arr.push({
          label: 'Save as Draft',
          id: 'save',
          type: 'button',
          color: 'teal',
          icon: 'save'
        })
        arr.push({
          label: 'Send for Approval',
          id: 'sendforapproval',
          type: 'button',
          color: 'orange-10',
          icon: 'pan_tool'
        })
      } else {
        arr.push({
          label: 'Save',
          id: 'save',
          type: 'button',
          color: 'teal',
          icon: 'save'
        })
      }
      return arr
    },
    getTitle () {
      let str = ''
      if (this.$route.params.id) {
        str += 'Edit: ' + this.name
      } else {
        if (this.isLead) {
          str += 'Create Lead'
        } else {
          str += 'Create Customer'
        }
      }
      return str
    },
    getTypeLabel () { return this.isLead ? 'Lead Type' : 'Customer Type' }
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
        }),
        this.$axios.get('representatives').then((res) => {
          this.representativeOptions = res.data
        }),
        this.$axios.get('customers/types').then((res) => {
          this.type_options = res.data
        })
      ]).then(() => {
        if (this.$route.params.id) {
          this.$axios.get('customers/' + this.$route.params.id).then((res) => {
            // console.log(res.data)
            this.status = res.data.status
            this.name = res.data.name
            this.has_gst = res.data.has_gst
            this.type = res.data.type
            this.gst_number = res.data.gst_number
            this.default_shipping = res.data.default_shipping
            this.initial_balance = res.data.initial_balance
            const emails = []
            this.non_billable_account = res.data.non_billable_account
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
              if (item.tag_name === 'shipping') {
                const phones = []
                item.phones.forEach((phone) => {
                  this.shipping_address_phones_errors.push({ message: '' })
                  phones.push({
                    id: phone.id,
                    content: phone.content,
                    country_code: phone.country_code
                  })
                })
                this.shipping_addresses.push({
                  id: item.id,
                  line_1: item.line_1,
                  line_2: item.line_2,
                  pin: item.pin,
                  country: item.country,
                  state: item.state,
                  district: item.district,
                  phones: phones
                })
              }
            })
            res.data.linked.forEach((item) => {
              this.linked_accounts.push({
                relation_type: item.pivot.relation_type,
                account: {
                  id: item.id,
                  name: item.name
                },
                specification: item.pivot.specification
              })
            })
            res.data.tags.forEach((tag) => {
              this.tags.push(tag.content)
            })
            this.representatives = Object.keys(this.$_.groupBy(res.data.representatives, 'id')).map(v => parseInt(v, 10))
          })
        }
      }).finally(() => {
        this.$nextTick(() => {
          // console.log(this)
          this.$store.commit('setPageTitle', this.getTitle)
        })
        this.$refs.type.focus()
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
    openShippingDialog () {
      this.shipping_address.country = 'India'
      this.loadShippingState()
      this.shippingDialog = true
    },
    closeShippigDialog () {
      if (!this.billing_address.state) {
        this.loadBillingState()
        this.loadBillingDistrict()
      }
      this.resetShippingPhonesErrors()
      this.resetShippingAddress()
      this.edit_address_index = null
      this.shippingDialog = false
    },
    addShipping () {
      if (
        this.$refs.shipping_address_line_1.validate() &
                this.$refs.shipping_address_pin.validate() &
                this.$refs.shipping_address_country.validate() &
                this.$refs.shipping_address_state.validate() &
                this.$refs.shipping_address_district.validate() &
                this.validateShippingPhones()
      ) {
        const obj = this.$_.cloneDeep(this.shipping_address)
        if (this.edit_address_index != null) {
          this.shipping_addresses[this.edit_address_index] = obj
        } else {
          obj.id = 'temp_' + this.shipping_addresses.length + 1
          this.shipping_addresses.push(obj)
        }
        this.closeShippigDialog()
      }
    },
    resetShippingAddress () {
      this.shipping_address.line_1 = null
      this.shipping_address.line_2 = null
      this.shipping_address.pin = null
      this.shipping_address.district = ''
      this.shipping_address.district_loading = false
      this.shipping_address.state = null
      this.shipping_address.state_loading = false
      this.shipping_address.country = ''
      this.shipping_address.phones = [{
        id: 1,
        content: '',
        country_code: '+91'
      }]
      this.shippingDialog = false
    },
    billingPinRule (val) {
      if (this.billing_address.country === 'India') {
        if (!this.isLead && !val) {
          return 'Required'
        } else if (val && isNaN(val)) {
          return 'PIN must be numeric'
        } else if (val && val.length !== 6) {
          return 'PIN must be exactly 6 digits'
        }
        return true
      }
      return true
    },
    shippingPinRule (val) {
      if (this.shipping_address.country === 'India') {
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
        if (this.isLead && this.billing_address_state) {
          return 'Required'
        } else if (!this.isLead) {
          return (val && val.length > 0) || 'Required'
        }
      }
      return true
    },
    shippingDistrictRule (val) {
      if (this.shipping_address.country === 'India') {
        return (val && val.length > 0) || 'Required'
      }
      return true
    },
    resetShippingPhonesErrors () {
      this.shipping_address_phones_errors.forEach((item, index) => {
        this.$set(this.shipping_address_phones_errors[index], 'message', '')
      })
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
    validateShippingPhones () {
      this.resetShippingPhonesErrors()
      let result = false
      this.shipping_address.phones.forEach((phone, index) => {
        if (this.shipping_address.country === 'India') {
          if (!phone.content) {
            this.$set(this.shipping_address_phones_errors[index], 'message', 'Required')
            result = false
          } else if (isNaN(phone.content)) {
            this.$set(this.shipping_address_phones_errors[index], 'message', 'Phone must be numeric.')
            result = false
          } else if (phone.content.length !== 10) {
            this.$set(this.shipping_address_phones_errors[index], 'message', 'Phone must be 10 digits.')
            result = false
          } else {
            result = true
          }
        } else {
          if (!phone.content) {
            this.$set(this.shipping_address_phones_errors[index], 'message', 'Required')
            result = false
          } else if (isNaN(phone.content)) {
            this.$set(this.shipping_address_phones_errors[index], 'message', 'Phone must be numeric.')
            result = false
          } else {
            result = true
          }
        }
      })
      return result
    },
    validateBillingPhones () {
      this.resetBillingPhonesErrors()
      let result = false
      this.billing_address.phones.forEach((phone, index) => {
        if (this.billing_address.country === 'India') {
          if (!phone.content) {
            this.$set(this.billing_address_phones_errors[index], 'message', 'Required')
            result = false
          } else if (isNaN(phone.content)) {
            this.$set(this.billing_address_phones_errors[index], 'message', 'Phone must be numeric.')
            result = false
          } else if (phone.content.length !== 10) {
            this.$set(this.billing_address_phones_errors[index], 'message', 'Phone must be 10 digits.')
            result = false
          } else {
            result = true
          }
        } else {
          if (!phone.content) {
            this.$set(this.billing_address_phones_errors[index], 'message', 'Required')
            result = false
          } else if (isNaN(phone.content)) {
            this.$set(this.billing_address_phones_errors[index], 'message', 'Phone must be numeric.')
            result = false
          } else {
            result = true
          }
        }
      })
      return result
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
    validateShippingCount () {
      if (!this.isLead) {
        if (this.shipping_addresses.length > 0) {
          return true
        } else {
          this.$q.notify({
            type: 'negative',
            message: 'There Should be atleast one shipping address'
          })
          return false
        }
      }
      return true
    },
    sendforapproval () {
      this.saveFn('Pending Approval')
    },
    activate () {
      this.saveFn('Active')
    },
    saveFn (status = 'Draft') {
      if (this.validateFn(status)) {
        this.errors.name = ''
        this.emails_errors.forEach((item, i) => {
          this.emails_errors[i].message = ''
        })
        this.billing_address_phones_errors.forEach((item, i) => {
          this.billing_address_phones_errors[i].message = ''
        })
        const obj = {
          is_lead: this.isLead ? 1 : 0,
          _method: this.$route.params.id ? 'PUT' : 'POST',
          status: this.isLead ? this.status : status,
          type: this.type,
          address_ids_to_delete: this.address_ids_to_delete,
          linked_accounts: JSON.stringify(this.linked_accounts),
          representatives: this.representatives,
          tags: this.tags,
          remarks: this.remarks,
          initial_balance: this.initial_balance
        }
        if (!this.isLead) {
          const shippingIds = Object.keys(this.$_.groupBy(this.shipping_addresses, 'id'))
          if (shippingIds.includes(this.default_shipping)) {
            obj.default_shipping = this.default_shipping
          } else {
            obj.default_shipping = this.non_billable_account ? 0 : this.shipping_addresses[0].id
          }
        }
        obj.name = this.name
        obj.gst_number = this.has_gst ? this.gst_number : ''
        obj.has_gst = this.has_gst
        obj.non_billable_account = this.non_billable_account
        obj.billing_address = this.billing_address
        obj.emails = this.emails
        obj.shipping_addresses = this.shipping_addresses
        let route = 'customers'
        if (this.$route.params.id) {
          route = 'customers/' + this.$route.params.id
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
              } else if (key.indexOf('gst_number') !== -1) {
                this.gst_error_message = error.response.data.errors[key][0]
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
    validateFn (status) {
      if (this.non_billable_account) {
        return this.$refs.name.validate() &
                this.$refs.type.validate() &
                this.validateEmails() &
                this.validateGst()
      } else {
        if (status === 'Draft') {
          return this.$refs.name.validate() &
            this.$refs.type.validate() &
            this.$refs.initial_balance.validate() &
            this.validateBillingPhones() &
            this.validateEmails() &
            this.validateGst()
        } else {
          return this.$refs.name.validate() &
            this.$refs.initial_balance.validate() &
            this.$refs.representative.validate() &
            this.$refs.billing_address_line_1.validate() &
            this.$refs.billing_address_pin.validate() &
            this.$refs.billing_address_country.validate() &
            this.$refs.billing_address_state.validate() &
            this.$refs.billing_address_district.validate() &
            this.$refs.type.validate() &
            this.validateBillingPhones() &
            this.validateEmails() &
            this.validateGst() &
            this.validateShippingCount()
        }
      }
    },
    duplicateBilling () {
      if (
        this.$refs.billing_address_line_1.validate() &
                this.$refs.billing_address_pin.validate() &
                this.$refs.billing_address_country.validate() &
                this.$refs.billing_address_state.validate() &
                this.$refs.billing_address_district.validate() &
                this.validateBillingPhones()
      ) {
        const obj = this.$_.cloneDeep(this.billing_address)
        obj.id = 'temp_' + this.shipping_addresses.length + 1
        this.shipping_addresses.push(obj)
      } else {
        this.$q.notify({
          type: 'negative',
          message: 'Please fill up billing address completely'
        })
      }
    },
    loadBillingState () {
      this.billing_address.state = null
      this.billing_address.district = null
      this.billing_address_state_loading = true
      this.getStates(this.billing_address.country).finally(() => {
        this.billing_address_state_loading = false
      })
    },
    loadShippingState (setNull = 1) {
      if (setNull) {
        this.shipping_address.state = null
        this.shipping_address.district = null
      }
      this.shipping_address_state_loading = true
      this.getStates(this.shipping_address.country).finally(() => {
        this.shipping_address_state_loading = false
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
    loadShippingDistrict (setNull = 1) {
      if (setNull) {
        this.shipping_address.district = null
      }
      this.shipping_address_district_loading = true
      this.getDistricts(this.shipping_address.state).finally(() => {
        this.shipping_address_district_loading = false
      })
    },
    getDistricts (state) {
      return this.$axios.get('districts/' + encodeURIComponent(state)).then((res) => {
        this.districts = res.data
      })
    },
    editAddress (index) {
      // console.log(index)
      this.edit_address_index = index
      this.shipping_address = this.$_.cloneDeep(this.shipping_addresses[index])
      this.loadShippingState(0)
      this.loadShippingDistrict(0)
      this.shippingDialog = true
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
    addShippingPhone () {
      this.shipping_address.phones.push({
        id: this.shipping_address.phones.length + 1,
        content: '',
        country_code: '+91'
      })
      this.shipping_address_phones_errors.push({
        message: ''
      })
      this.$nextTick(() => {
        const ind = this.shipping_address.phones.length - 1
        const key = 'shipping_phone_' + ind
        this.$refs[key][0].focus()
      })
    },
    deleteAddress (index) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Are you sure want to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        if (this.$route.params.id) {
          this.address_ids_to_delete.push(this.shipping_addresses[index].id)
        }
        this.shipping_addresses.splice(index, 1)
      }).onCancel(() => {

      })
    },
    customerFilterFn (val, update, abort, i) {
      update(() => {
        if (val) {
          let str = ''
          if (this.$route.params.id) {
            str += '&except_id=' + this.$route.params.id
          }
          if (this.linked_accounts[i].relation_type === 'Sibling branch of') {
            str += '&type=' + this.type
          }
          this.$axios.get('customers_search?' + str + '&search=' + encodeURIComponent(val) + '&relation_type=' + encodeURIComponent(this.linked_accounts[i].relation_type)).then((res) => {
            this.customerOptions = res.data
          })
        }
      })
    },
    tagFilterFn (val, update, abort, i) {
      update(() => {
        if (val) {
          const str = ''
          this.$axios.get('tag_search?' + str + '&search=' + encodeURIComponent(val)).then((res) => {
            this.tagOptions = res.data
          })
        }
      })
    },
    newTag (val, done) {
      if (val.length > 0) {
        const model = (this.tags || []).slice()

        val
          .split(/[,;|]+/)
          .map(v => v.trim())
          .filter(v => v.length > 0)
          .forEach(v => {
            if (model.includes(v) === false) {
              model.push(this.capitaliseFn(v))
            }
          })

        done(null)
        this.tags = model
      }
    },
    linkAccount () {
      if (this.customerSearch) { this.linked_accounts.push(this.customerSearch) }
      this.customerSearch = null
      this.$nextTick(() => {
        window.scrollTo(0, document.body.scrollHeight)
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
  },
  watch: {
    has_gst (newV, oldV) {
      if (oldV) {
        this.gst_error_message = ''
        this.gst_number = ''
      }
    },
    type (newV, oldV) {
      if (!this.$route.params.id) {
        if (newV === 'Doctor') {
          this.name = 'Dr. ' + (this.name ? this.name : '')
        }
      }
    }
  }
}
</script>
