<template>
  <q-card class="bg-primary">
    <q-bar>
      <q-space />
      <q-btn dense flat icon="close" v-close-popup>
        <q-tooltip content-class="bg-white text-primary">Close</q-tooltip>
      </q-btn>
    </q-bar>

    <q-card-section>
      <div class="text-h6 text-white">Partial Conversion</div>
    </q-card-section>

    <q-card-section class="q-pt-none">
      <q-stepper v-model="step" ref="stepper" color="primary" animated>
        <q-step :name="1" title="Selection" icon="check">
          <q-table
            @selection="selectedFn"
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
            :data="datawithIndex"
            title="Please select products to process"
            selection="multiple"
            :selected.sync="selectedForConversion"
            row-key="index"
          >
            <template v-slot:body-selection="scope">
              <q-checkbox v-model="scope.selected" :disable="scope.row.converted_qty === scope.row.qty || !scope.row.product_id"/>
            </template>
            <template v-slot:body-cell-ind="props">
              <q-td :props="props">
                {{ props.rowIndex + 1 }}
              </q-td>
            </template>
            <template v-slot:body-cell-product="props">
              <q-td :props="props">
                {{ props.row.product_name }}
                <q-badge v-if="!props.row.product_id" align="top" outline color="primary" label="New"/>
                <q-badge v-if="props.row.converted_qty == props.row.qty" align="top" outline color="green-7" label="Converted"/>
                <q-badge v-if="props.row.converted_qty > 0 && props.row.converted_qty != props.row.qty" align="top" outline color="orange-7" label="Partially Converted"/>
              </q-td>
            </template>
          </q-table>
          <q-stepper-navigation>
            <q-btn
              @click="
                step = 2;
                sanitizeSelection()
              "
              color="primary"
              label="Continue"
              :disable="selectedForConversion.length == 0"
            />
          </q-stepper-navigation>
        </q-step>
        <q-step :name="2" title="Details" icon="save">
          <q-table
            :columns="columns"
            title="Selected Items"
            :data="selectedForConversion"
            row-key="index"
            wrap-cells
            :rows-per-page-options="[0]"
          >
            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td class="text-right">{{ props.rowIndex + 1 }}</q-td>
                <q-td>
                  {{ props.row.product_name }}
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
                          selectedForConversion[props.rowIndex].qty
                        ) === true
                          ? true
                          : false
                    "
                    @hide="
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
          </q-table>
          <q-stepper-navigation>
            <q-btn color="primary" label="back" @click="step = 1" />
            <q-btn
              color="green"
              label="Submit"
              class="q-ml-md"
              @click="submitQ()"
            />
          </q-stepper-navigation>
        </q-step>
      </q-stepper>
    </q-card-section>
  </q-card>
</template>
<script>
export default {
  name: 'ConvertDialog',
  props: ['items'],
  data () {
    return {
      step: 1,
      selectedForConversion: [],
      errorQty: false,
      errorMessageQty: '',
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
  computed: {
    datawithIndex () {
      return this.items.map((val, idx) => ({ ...val, index: idx }))
    }
  },
  methods: {
    selectedFn ({ rows, added, keys }) {
      const row = rows[0]
      if (added) {
        if (parseInt(row.converted_qty) > 0 && parseInt(row.converted_qty) !== parseInt(row.qty)) {
          rows[0].qty = parseInt(row.qty) - parseInt(row.converted_qty)
        }
      } else {
        if (parseInt(row.converted_qty) > 0 && parseInt(row.converted_qty) !== parseInt(row.qty)) {
          rows[0].qty = parseInt(row.qty) + parseInt(row.converted_qty)
        }
      }
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
    sanitizeSelection () {
      this.selectedForConversion = this.selectedForConversion.filter((item) => {
        return parseInt(item.converted_qty) !== parseInt(item.qty) && parseInt(item.product_id) !== 0
      })
    },
    submitQ () {
      this.$q.loading.show()
      this.$axios.post('quotations/partial_convert/' + this.$route.params.id, {
        items: this.selectedForConversion
      }).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'success',
            message: 'Quotation converted successfully'
          })
          this.$router.push({ path: '/sale_orders/view/' + res.data.id })
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
      }).finally(() => {
        this.$q.loading.hide()
      })
    }
  }
}
</script>
