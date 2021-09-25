<template>
  <q-card style="min-width:500px;">
    <q-card-section>
      <div class="text-subtitle1">Payment Details</div>
    </q-card-section>
    <q-separator inset/>
    <q-card-section>
      <div class="row q-col-gutter-md">
        <div class="col-6 justify-center column">
          <div class="text text-right">Paid Amount</div>
        </div>
        <div class="col-6">
          <q-input
            outlined square dense
            class="input-right" v-model="paid_amount"
            ref="paid_amount" @focus="$refs.paid_amount.select()"
            :disable="selected.length > 0"
            :rules="[v => !!v || 'Required', v => parseFloat(v) >= 0 || 'Invalid']"/>
        </div>
      </div>
      <div class="row q-col-gutter-md q-mt-sm">
        <div class="col-6">
          <div class="text text-right">{{computedText}}</div>
        </div>
        <div class="col-6">
          <div :class="cbalance < 0 ? 'text text-right q-pr-sm text-green':  'text text-right q-pr-sm text-red'">{{Math.abs(cbalance).toFixed(2)}}</div>
        </div>
      </div>
      <div class="row q-col-gutter-md q-mt-sm" v-if="parseFloat(cbalance) < 0">
        <div class="col-6 justify-center column">
          <div class="text text-right">Settle using prepaid amount ?</div>
        </div>
        <div class="col-6">
          <q-checkbox v-model="settle_using_cbalance"/>
        </div>
      </div>
      <div class="row q-col-gutter-md q-mt-sm">
        <div class="col-6">
          <div class="text text-right">Remaining Amount</div>
        </div>
        <div class="col-6">
          <div class="text text-right q-pr-sm">{{remain_amount.toFixed(2)}}</div>
        </div>
      </div>
      <div class="text-subtitle2" v-if="parseFloat(this.paid_amount) > 0">Payment Via</div>
      <div class="row q-col-gutter-sm" v-if="parseFloat(this.paid_amount) > 0">
        <q-radio val="cash" label="Cash" v-model="payment_via"/>
        <q-radio val="bank" label="Bank" v-model="payment_via"/>
        <q-radio val="cheque" label="Cheque" v-model="payment_via"/>
        <q-radio val="upi" label="UPI" v-model="payment_via"/>
        <q-radio val="card" label="Card" v-model="payment_via"/>
      </div>
      <div class="row justify-center q-mb-md" v-if="unsettledOrders.length > 0">
        <q-table separator="cell"
          :rows-per-page-options="[0]"
          hide-bottom
          selection="multiple"
          :selected.sync="selected"
          @selection="onSelection"
          row-key="id"
          :columns="[
            {label: 'Serial No.', name: 'serial_no', field: 'serial_no', align: 'left'},
            {label: 'Invoiced At', name: 'invoiced_at', field: 'invoiced_at', align: 'right'},
            {label: 'Balance', name: 'balance', field: 'balance', align: 'right'},
            {label: 'Settle By', name: 'settle_by', field: 'settle_by', align: 'right'},
          ]"
          :data="unsettledOrders"
          title="Unsettled Orders">
          <template v-slot:body="props">
            <q-tr :props="props" :class="props.row.serial_no === serial_no ? 'bg-green text-white':''">
              <q-td>
                <q-checkbox v-model="props.selected" color="accent" :disable="props.selected ? false : remain_amount === 0 ? true : false"/>
              </q-td>
              <q-td>
                {{ props.row.serial_no }}
              </q-td>
              <q-td>
                {{ getLocaleString(props.row.invoiced_at) }}
              </q-td>
              <q-td >
                {{ props.row.balance }}
              </q-td>
              <q-td>
                {{ props.row.settle_by }}
              </q-td>
            </q-tr>
          </template>
          <template v-slot:header-selection="">

          </template>
        </q-table>
      </div>
      <div class="row">
        <div class="col">
          <q-input outlined square dense label="Reference ID" rows="1" type="textarea" v-model="reference_id"/>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="text-subtitle2">Remarks</div>
          <q-editor v-model="remarks" min-height="3rem"/>
        </div>
      </div>
    </q-card-section>
    <q-card-actions>
      <q-btn color="primary" label="Submit" @click="submit"/>
      <q-btn flat color="grey-10" label="cancel" @click="$emit('close')"/>
    </q-card-actions>
  </q-card>
</template>
<script>
export default {
  data () {
    return {
      paid_amount: null,
      settle_using_cbalance: false,
      payment_via: null,
      reference_id: null,
      remarks: '',
      cbalance: null,
      unsettledOrders: [],
      selected: []
    }
  },
  computed: {
    computedText () { return parseFloat(this.cbalance) < 0 ? 'Prepaid Amount' : 'Customer Balance' },
    remain_amount () {
      let availableAmount = 0
      if (this.settle_using_cbalance) availableAmount = parseFloat(this.cbalance) < 0 ? Math.abs(parseFloat(this.cbalance)) + parseFloat(this.paid_amount) : parseFloat(this.paid_amount)
      else availableAmount = parseFloat(this.paid_amount)
      const selectedSum = parseFloat(this.$_.sumBy(this.selected, (t) => parseFloat(t.balance)))
      return selectedSum > availableAmount ? 0 : availableAmount - selectedSum
    }
  },
  props: ['customer', 'status', 'total', 'id', 'route', 'serial_no'],
  mounted () {
    if (this.status === 'Invoice Pending') this.paid_amount = this.total
    else this.paid_amount = 0
    this.$q.loading.show()
    this.$axios.get('customers/balance/' + this.customer.id).then((res) => {
      this.cbalance = (res.data.toFixed(2))
      if (res.data > 0) {
        this.$axios.get('customers/sale_orders/unsettled/' + this.customer.id).then((res2) => {
          this.unsettledOrders = res2.data
          this.unsettledOrders.forEach((item, ind) => {
            this.unsettledOrders[ind].balance = parseFloat(item.balance_amount).toFixed(2)
          })
        }).finally(() => {
          if (this.status === 'Invoice Pending') {
            const obj = { id: this.id, serial_no: this.serial_no, balance: this.total }
            this.unsettledOrders.push(obj)
          }
        })
      }
    }).finally(() => {
      if (this.unsettledOrders.length === 0) {
        const obj = { id: this.id, serial_no: this.serial_no, balance: this.total }
        this.unsettledOrders.push(obj)
      }
      this.$q.loading.hide()
    })
  },
  watch: {

  },
  methods: {
    onSelection ({ rows, keys, added, evt }) {
      if (added === true) {
        const ind = this.$_.findIndex(this.unsettledOrders, ['id', keys[0]])
        this.unsettledOrders[ind].settle_by = parseFloat(this.remain_amount) > parseFloat(rows[0].balance) ? rows[0].balance : parseFloat(this.remain_amount).toFixed(2)
      } else {
        let ind = this.$_.findIndex(this.unsettledOrders, ['id', keys[0]])
        this.unsettledOrders[ind].settle_by = null
        ind = this.$_.findIndex(this.selected, ['id', keys[0]])
        this.selected[ind].settle_by = null
      }
    },
    submit () {
      if (this.paid_amount > 0 && !this.payment_via) {
        this.$q.notify({ message: 'Payment Via Not Specified', type: 'negative' })
      } else if (parseFloat(this.remain_amount) > 0 && this.selected.length !== this.unsettledOrders.length) {
        this.$q.notify({ message: 'There are sale orders need to be settled', type: 'negative' })
      } else {
        this.$q.loading.show()
        this.$axios.post('sale_orders/' + this.route + '/' + this.id, {
          items: this.selected,
          payment_via: this.payment_via,
          reference_id: this.reference_id,
          remarks: this.remarks,
          paid_amount: this.paid_amount,
          remain_amount: this.remain_amount,
          settle_using_cbalance: this.settle_using_cbalance
        }).then((res) => {
          this.$emit('close')
        }).finally(() => {
          this.$q.loading.hide()
        })
      }
    }
  }
}
</script>
