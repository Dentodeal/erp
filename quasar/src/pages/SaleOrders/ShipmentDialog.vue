<template>
  <q-card class="bg-blue-7">
    <q-bar>
      <q-space />
      <q-btn dense flat icon="close" v-close-popup>
        <q-tooltip content-class="bg-white text-primary">Close</q-tooltip>
      </q-btn>
    </q-bar>

    <q-card-section>
      <div class="text-h6 text-white">Split Shipment</div>
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
            title="Please select products for split shipment"
            selection="multiple"
            :selected.sync="selectedForSplit"
          >
            <template v-slot:body-cell-ind="props">
              <q-td :props="props">
                {{ props.rowIndex + 1 }}
              </q-td>
            </template>
            <template v-slot:body-cell-product="props">
              <q-td :props="props">
                {{ props.row.product.name }}
              </q-td>
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
              :disable="selectedForSplit.length == 0"
            />
          </q-stepper-navigation>
        </q-step>
        <q-step :name="2" title="Details" icon="save">
          <q-table
            :columns="columns"
            title="Selected Items"
            :data="selectedForSplit"
            row-key="sl_no"
            wrap-cells
            :rows-per-page-options="[0]"
          >
            <template v-slot:body="props">
              <q-tr :props="props">
                <q-td class="text-right">{{ props.rowIndex + 1 }}</q-td>
                <q-td>
                  {{ props.row.name }}
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
                          selectedForSplit[props.rowIndex].qty
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
          </q-table>
          <div class="row q-mt-md">
            <div class="col">
              <div class="text-subtitle2">Remarks</div>
              <q-editor v-model="remarks" rows="3" />
            </div>
          </div>
          <q-stepper-navigation>
            <q-btn color="primary" label="back" @click="step = 1" />
            <q-btn
              color="green"
              label="Submit"
              class="q-ml-md"
              @click="submitShipment()"
            />
          </q-stepper-navigation>
        </q-step>
      </q-stepper>
    </q-card-section>
  </q-card>
</template>
<script>
export default {
  props: ['items'],
  data () {
    return {
      step: 1,
      selectedForSplit: [],
      remarks: '',
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
  methods: {
    qtyEditValidation (val, original) {
      if (!val) {
        return 'Required'
      } else if (!Number.isInteger(Number(val))) {
        return 'Qty must be an integer'
      } else {
        return true
      }
    },
    submitShipment () {
      this.$axios.post('sale_orders/ship/' + this.$route.params.id, {
        mode: 'split',
        items: this.selectedForSplit
      }).then((res) => {
        // console.log(res)
      }).error((error) => {
        if (error.response.status === 422) {
          // console.log(error.response)
        }
      })
    }
  }
}
</script>
