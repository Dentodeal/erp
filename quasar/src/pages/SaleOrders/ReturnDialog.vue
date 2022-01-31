<template>
  <q-card class="bg-primary">
    <q-bar>
      <q-space />
      <q-btn dense flat icon="close" v-close-popup>
        <q-tooltip content-class="bg-white text-primary">Close</q-tooltip>
      </q-btn>
    </q-bar>

    <q-card-section>
      <div class="text-h6 text-white">Create Sale Return</div>
    </q-card-section>

    <q-card-section class="q-pt-none">
      <q-stepper v-model="step" ref="stepper" color="primary" animated>
        <q-step :name="1" title="Selection" icon="check">
          <q-table
            :columns="[
              { name: 'ind', field: 'ind', label: '#', align: 'right' },
              {
                name: 'product',
                field: 'product',
                label: 'Product',
                align: 'left',
                sortable: true,
              },
              {
                name: 'expiry_date',
                field: 'expiry_date',
                label: 'Expiry Date',
              },
            ]"
            :data="items"
            title="Please select products to return"
            selection="multiple"
            :selected.sync="selectedForReturn"
          >
            <template v-slot:body-selection="scope">
              <q-checkbox v-model="scope.selected" :disable="scope.row.returned_qty === scope.row.qty"/>
            </template>
            <template v-slot:body-cell-ind="props">
              <q-td :props="props">
                {{ props.rowIndex + 1 }}
              </q-td>
            </template>
            <template v-slot:body-cell-product="props">
              <q-td :props="props">
                {{ props.row.product.name }}
                <q-badge v-if="props.row.returned_qty > 0 && props.row.returned_qty != props.row.qty" align="top" outline color="primary" label="Partially Returned"/>
                  <q-badge v-if="props.row.returned_qty > 0 && props.row.returned_qty == props.row.qty" align="top" outline color="red" label="Returned"/>
              </q-td>
            </template>
            <template v-slot:header-selection>
            </template>
          </q-table>
          <q-stepper-navigation>
            <q-btn
              @click="
                step = 2;
                updateSubtotal();
              "
              color="primary"
              label="Continue"
              :disable="selectedForReturn.length == 0"
            />
          </q-stepper-navigation>
        </q-step>
        <q-step :name="2" title="Details" icon="save">
          <q-table
            class="my-sticky-header-table"
            :columns="columns"
            title="Selected Items"
            :data="selectedForReturn"
            row-key="sl_no"
            wrap-cells
            :rows-per-page-options="[0]"
          >
            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td class="text-right">{{ props.rowIndex + 1 }}</q-td>
                <q-td>
                  {{ props.row.product.name }}
                </q-td>
                <q-td class="text-right">
                  {{ props.row.qty }}
                  <q-popup-edit
                    v-model="props.row.qty"
                    buttons
                    label-set="Save"
                    label-cancel="Close"
                    :validate="
                      (v) =>
                        qtyEditValidation(
                          v,
                          selectedForReturn[props.rowIndex].qty
                        ) === true
                          ? true
                          : false
                    "
                    @hide="
                      updateRow(props.rowIndex);
                      props.row.qty = parseInt(props.row.qty);
                    "
                  >
                    <q-input
                      @input="$refs.qty_edit.resetValidation()"
                      ref="qty_edit"
                      :rules="[(v) => qtyEditValidation(v, props.row.qty)]"
                      v-model="props.row.qty"
                      dense
                      autofocus
                    />
                  </q-popup-edit>
                </q-td>
                <q-td class="text-right">
                  {{ props.row.expiry_date }}
                </q-td>
              </q-tr>
            </template>
            <template v-slot:bottom-row>
              <q-tr class="bg-blue-grey-3">
                <q-td colspan="2"/>
                <q-td class="text-right">Subtotal</q-td>
                <q-td class="text-right">{{parseFloat(returnSubtotal).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-1">
                <q-td colspan="2"/>
                <q-td class="text-right">Freight</q-td>
                <q-td class="text-right">
                  {{returnFreight ? parseFloat(returnFreight).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }): '0.00'}}
                  <q-popup-edit v-model="returnFreight"
                    buttons
                    label-set="Save"
                    label-cancel="Close"
                    :validate="() => $refs.freight_edit.validate() ? true : false"
                    @hide="returnFreight = parseFloat(returnFreight).toFixed(2);updateTotal();"
                    >
                    <q-input
                        v-model="returnFreight"
                        dense
                        autofocus
                        @focus="$refs.freight_edit.select()"
                        class="input-right"
                        ref="freight_edit"
                        :rules="[v => !isNaN(v) || 'Invalid']"
                    />
                  </q-popup-edit>
                </q-td>
              </q-tr>
              <q-tr class="bg-blue-grey-3">
                <q-td colspan="2"/>
                <q-td class="text-right">Total</q-td>
                <q-td class="text-right">{{parseFloat(returnTotal).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}}</q-td>
              </q-tr>
            </template>
          </q-table>
          <div class="row q-mt-md">
            <div class="col">
              <div class="text-subtitle2">Remarks</div>
              <q-editor v-model="returnRemarks" rows="3" />
            </div>
          </div>
          <q-stepper-navigation>
            <q-btn color="primary" label="back" @click="step = 1" />
            <q-btn
              color="green"
              label="Submit"
              class="q-ml-md"
              @click="submitReturn()"
            />
          </q-stepper-navigation>
        </q-step>
      </q-stepper>
    </q-card-section>
  </q-card>
</template>
<script>
export default {
  props: ['items', 'rateIncludesGst'],
  data () {
    return {
      step: 1,
      selectedForReturn: [],
      returnFreight: 0,
      returnSubtotal: 0,
      returnTotal: 0,
      errorQty: false,
      errorMessageQty: '',
      returnRemarks: '',
      return_count: 0,
      columns: [
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
    }
  },
  watch: {
    returnSubtotal () {
      this.updateTotal()
    },
    returnFreight () {
      this.updateTotal()
    }
  },
  methods: {
    updateSubtotal () {
      let total = 0
      this.selectedForReturn.forEach((item) => {
        total += parseFloat(item.total)
      })
      this.returnSubtotal = total.toFixed(2)
      this.updateTotal()
    },
    updateRow (index) {
      const oldTotal = parseFloat(this.selectedForReturn[index].total)
      const qty = parseInt(this.selectedForReturn[index].qty)
      const rate = parseFloat(this.selectedForReturn[index].rate)
      const gst = parseFloat(this.selectedForReturn[index].gst)
      let total = 0
      if (!this.rateIncludesGst) {
        let taxable = 0
        taxable = (rate * qty)
        this.selectedForReturn[index].taxable = taxable.toFixed(2)
        const taxAmount = taxable * (gst / 100)
        this.selectedForReturn[index].tax_amount = taxAmount.toFixed(2)
        total = taxable + taxAmount
        this.selectedForReturn[index].total = total.toFixed(2)
      } else {
        total = (rate * qty)
        this.selectedForReturn[index].total = total.toFixed(2)
      }
      const diff = total - oldTotal
      this.addtoSubtotal(diff)
    },
    addtoSubtotal (val) {
      this.returnSubtotal = (parseFloat(this.returnSubtotal) + parseFloat(val)).toFixed(2)
    },
    updateTotal () {
      const subtotal = parseFloat(this.returnSubtotal)
      const freight = parseFloat(this.returnFreight)
      let total = 0
      total = (subtotal + freight)
      this.returnTotal = Math.round(total).toFixed(2)
    },
    qtyEditValidation (val, original) {
      if (!val) {
        return 'Required'
      } else if (!Number.isInteger(Number(val))) {
        return 'Qty must be an integer'
      } else {
        return true
      }
    },
    submitReturn () {
      this.$axios.post('sale_returns', {
        sale_order_id: this.$route.params.id,
        status: 'Draft',
        items: this.selectedForReturn,
        freight: this.returnFreight,
        subtotal: this.returnSubtotal,
        total: this.returnTotal,
        remarks: this.returnRemarks
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$router.push({ path: '/sale_returns/view/' + res.data.id })
        }
      }).catch((error) => {
        this.$q.notify({
          type: 'negative',
          message: error.response.data.message
        })
        let str = ''
        Object.keys(error.response.data.errors).forEach((key) => {
          if (key.indexOf('items') !== -1) {
            str += '<p>' + 'Line ' + (parseInt(key.substr(key.indexOf('.') + 1)) + 1) + ':' + error.response.data.errors[key][0] + '</p><br>'
          }
        })
        this.$q.dialog({
          title: 'Error in data submitted',
          message: str,
          html: true
        })
      })
    }
  }
}
</script>
