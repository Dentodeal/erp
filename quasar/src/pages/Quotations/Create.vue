<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" v-on:save="saveFn()" v-on:load="templateDialog = true"/>
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md' : 'q-px-xs q-mt-sm'">
      <q-card>
        <q-card-section>
          <div class="row q-col-gutter-md">
            <div class="col-md-3 col-12 q-mt-md">
              <q-select
                :options="['Standard','Export']"
                filled
                dense
                :disable="$route.params.id && $route.params.id > 0"
                square
                label="Type"
                v-model="model.type"
                ref="type"
                :rules="[(v) => !!v || 'Required']"
                @input="onTypeChange"
              />
            </div>
            <div class="col-md-3 col-12 q-mt-md">
              <q-input
                readonly
                dense
                filled
                square
                label="Status"
                v-model="model.status"
              />
            </div>
            <div class="col-12 col-md-3 q-mt-md">
              <q-select
                v-model="model.created_by"
                label="Created By"
                dense
                readonly
                option-value="id"
                option-label="name"
                filled
                square
                ref="created_by"
                :rules="[(v) => !!v || 'Required']"
                :options="createdByOptions"
              />
            </div>
            <div class="col-md-3 col-12 q-mt-md">
              <q-select
                v-model="model.representative"
                label="Representative"
                dense
                option-value="id"
                option-label="name"
                filled
                square
                :options="createdByOptions"
                ref="representative"
                :rules="[(v) => !!v || 'Required']"
              />
            </div>
          </div>
          <div class="row q-col-gutter-md">
            <div class="col-md-6 col-12 q-mt-md">
              <q-select
                filled
                square
                dense
                label="Customer/Lead"
                v-model="model.customer"
                use-input
                fill-input
                hide-selected
                option-value="id"
                option-label="name"
                :options="customerOptions"
                @filter="customerFilterFn"
                ref="customer"
                :rules="[(v) => !!v || 'Required']"
                @input="loadAddress()"
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

            <div class="col-12 col-md-3 q-mt-md">
              <q-input
                filled
                dense
                v-model="model.valid_until"
                readonly
                label="Valid Until"
                hint="Click on calender icon to enter date"
                ref="valid_until"
                :rules="[(v) => !!v || 'Required']"
              >
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      ref="qDateProxy"
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date v-model="model.valid_until">
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Close"
                            color="primary"
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>
            <div class="col-12 col-md-3 q-mt-md">
              <q-input
                filled
                dense
                v-model="model.ship_date"
                readonly
                label="Ship Date"
                hint="Click on calender icon to enter date"
              >
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy
                      ref="qDateProxy"
                      transition-show="scale"
                      transition-hide="scale"
                    >
                      <q-date v-model="model.ship_date">
                        <div class="row items-center justify-end">
                          <q-btn
                            v-close-popup
                            label="Close"
                            color="primary"
                            flat
                          />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>
          </div>
          <div class="row" v-if="model.billingAddress">
            <div class="col-12 col-md-6">
              {{model.billingAddress}}
            </div>
          </div>
          <div class="row q-col-gutter-md q-mt-md">
            <div class="col-12 col-md">
              <q-input dense v-model="model.ship_via" label="Ship Via" filled square />
            </div>
            <div class="col-12 col-md">
              <q-input dense v-model="model.po_number" label="PO Number" filled square />
            </div>
            <div class="col-12 col-md-auto">
              <q-select emit-value map-options dense v-model="model.inco_term" :options="incoTerms" label="Inco Term" filled square
              hint="International Commercial Term"
              ref="inco_term"
              :rules="[(v) => !!v || 'Required']"/>
            </div>
            <div class="col-12 col-md">
              <q-input dense v-model="model.fob_point" label="Shipping Point" filled square />
            </div>
            <div class="col-md col-12">
              <q-select
                v-model="model.pricelist"
                label="Pricelist"
                dense
                option-value="id"
                option-label="name"
                filled
                square
                :options="pricelists"
                ref="pricelist"
                :rules="[(v) => !!v || 'Required']"
              />
            </div>
            <div class="col-md col-12">
              <q-select
                v-model="model.warehouse"
                label="Warehouse"
                dense
                option-value="id"
                option-label="name"
                filled
                square
                :options="warehouses"
                ref="warehouse"
                :rules="[(v) => !!v || 'Required']"
              />
            </div>
          </div>
          <div class="row q-mt-md q-col-gutter-md">
            <div class="col-12 col-md-3"  v-if="model.type !== 'Export'">
              <q-checkbox
                label="Rate Includes GST ?"
                v-model="model.rate_includes_gst"
                color="green-10"
              />
            </div>
            <div class="col-12 col-md-3" v-if="model.type !== 'Export'">
              <q-checkbox
                label="Include Flood Cess ?"
                v-model="model.flood_cess_included"
                :true-value="1"
                :false-value="0"
                color="orange-10"
                disable
              />
            </div>
            <div class="col-12 col-md-3">
              <q-select
              :options="bankAccounts"
              v-model="model.bank"
              dense filled
              option-label="name"
              label="Bank Account"
              ref="bank"
              :rules="[(v) => !!v || 'Required']"
              >
              <template v-slot:option="scope">
                <q-item
                  v-bind="scope.itemProps"
                  v-on="scope.itemEvents"
                >
                  <q-item-section>
                    <q-item-label>{{scope.opt.name}}</q-item-label>
                    <q-item-label caption>Account Name: {{ scope.opt.acc_name }}</q-item-label>
                    <q-item-label caption>Bank: {{ scope.opt.bank_name }}</q-item-label>
                    <q-item-label caption>Acc No. {{ scope.opt.acc_no }}</q-item-label>
                    <q-item-label caption>IFSC: {{ scope.opt.ifsc }}</q-item-label>
                    <q-item-label caption>UPI: {{ scope.opt.upi }}</q-item-label>
                    <q-item-label caption>UPI: {{ scope.opt.swift }}</q-item-label>
                    <q-item-label caption>UPI: {{ scope.opt.bank_address }}</q-item-label>
                  </q-item-section>
                </q-item>
              </template>
              </q-select>
            </div>
            <div class="col-12 col-md-3">
              <q-select v-model="model.currency" label="Currency" :options="currencyOptions"
              ref="currency"
              filled dense
              emit-value
              map-options
              :rules="[(v) => !!v || 'Required']"
              >
              <template v-slot:option="scope">
                <q-item
                  v-bind="scope.itemProps"
                  v-on="scope.itemEvents"
                >
                  <q-item-section>
                    <q-item-section>
                      <q-item-label>{{scope.opt.label}}</q-item-label>
                    </q-item-section>
                    <q-item-section side top>
                      <q-item-label>{{ scope.opt.symbol }}</q-item-label>
                    </q-item-section>
                  </q-item-section>
                </q-item>
              </template>
              </q-select>
            </div>
          </div>
          <div class="row q-mt-md">
            <div class="col">
                <q-markup-table>
                  <thead>
                    <tr>
                      <th class="text-left">#</th>
                      <th class="text-left">Product</th>
                      <th class="text-right" v-if="model.type !== 'Export'">GST</th>
                      <th class="text-right">Qty</th>
                      <th class="text-right">Rate</th>
                      <th v-if="!rate_includes_gst && model.type !== 'Export'" class="text-right">Taxable</th>
                      <th v-if="!rate_includes_gst && model.type !== 'Export'" class="text-right">Tax Amount</th>
                      <th class="text-right">Total</th>
                      <th class="text-right"></th>
                    </tr>
                  </thead>
                  <draggable v-model="model.items" handle=".handle" tag="tbody">
                    <tr v-for="(item,i) in model.items" :key="i">
                      <td class="handle"><q-icon name="drag_indicator" class="text-grey" style="font-size: 32px; cursor:pointer;"/>{{ i + 1 }}</td>
                      <td>{{ item.product_name }}
                        <q-badge
                          v-if="!item.product_id"
                          align="top"
                          outline
                          color="primary"
                          label="New"
                        />
                        <q-btn flat icon="visibility" round size="sm" @click.stop="showMaskDetails(item,i)">
                          <q-tooltip>View Masking Details</q-tooltip>
                        </q-btn>
                        <q-popup-edit
                          v-model="model.items[i].product_name"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          @hide="
                            () => {
                              item.product_id == 0
                                ? (model.items[i].product_name = capitaliseFn(
                                    model.items[i].product_name
                                  ))
                                : '';
                              product = null;
                            }
                          "
                          @show="
                            () => {
                              product = item.product_name;
                            }
                          "
                        >
                          <q-select
                            filled
                            square
                            dense
                            label="Product"
                            :rules="[(v) => !!v || 'Required']"
                            lazy-rules="ondemand"
                            :options="productOptions"
                            @filter="productFilter"
                            use-input
                            fill-input
                            hide-selected
                            v-model="product"
                            :loading="prodLoading"
                            option-value="id"
                            option-label="name"
                            @input-value="
                              (v) => (model.items[i].product_name = capitaliseFn(v))
                            "
                            @input="
                              model.items[i].product_name = product.name;
                              model.items[i].product_id = product.id;
                            "
                          >
                            <template v-slot:no-option>
                              <q-item>
                                <q-item-section class="text-grey">
                                  No results
                                </q-item-section>
                              </q-item>
                            </template>
                          </q-select>
                        </q-popup-edit>
                      </td>
                      <td class="text-right" v-if="model.type !== 'Export'">
                        {{ item.gst }}%
                        <q-popup-edit
                          v-model="model.items[i].gst"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          @hide="updateRow(i)"
                        >
                          <q-select
                            v-model="model.items[i].gst"
                            :options="gstOptions"
                            map-options
                            emit-value
                            dense
                            autofocus
                          />
                        </q-popup-edit>
                      </td>
                      <td class="text-right">
                        {{ item.qty }}
                        <q-popup-edit
                          v-model="model.items[i].qty"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          :validate="v => qtyEditValidation(v) === true ? true : false"
                          @hide="
                            updateRow(i);
                            model.items[i].qty = parseInt(model.items[i].qty);
                          "
                        >
                          <q-input
                            ref="qty_edit"
                            :rules="[qtyEditValidation]"
                            v-model="model.items[i].qty"
                            dense
                            autofocus
                          />
                        </q-popup-edit>
                      </td>
                      <td class="text-right">
                        {{currencySymbol}}{{ item.rate }}
                        <q-popup-edit
                          v-model="model.items[i].rate"
                          buttons
                          label-set="Save"
                          label-cancel="Close"
                          :validate="v => rateEditValidation(v) === true ? true : false"
                          @hide="
                            updateRow(i);
                            model.items[i].rate = parseFloat(model.items[i].rate).toFixed(2);
                          "
                        >
                          <q-input
                            :rules="[rateEditValidation]"
                            ref="rate_edit"
                            v-model="model.items[i].rate"
                            dense
                            autofocus
                          />
                        </q-popup-edit>
                      </td>
                      <td class="text-right" v-if="!rate_includes_gst">
                        {{ item.taxable }}
                      </td>
                      <td class="text-right" v-if="!rate_includes_gst">
                        {{ item.tax_amount }}
                      </td>

                      <td class="text-right">
                        {{currencySymbol}}{{ item.total }}
                      </td>
                      <td class="text-right">
                        <q-btn
                          round
                          icon="delete"
                          flat
                          color="black"
                          @click="deleteFn(i)"
                        />
                      </td>
                    </tr>
                </draggable>
              </q-markup-table>
            </div>
          </div>
          <q-form ref="rowForm">
            <div class="row q-col-gutter-xs q-mt-md">
              <div class="col-4">
                <q-select
                  filled
                  square
                  dense
                  label="Product"
                  ref="product"
                  :rules="[(v) => !!v || 'Required']"
                  lazy-rules="ondemand"
                  :options="productOptions"
                  @filter="productFilter"
                  use-input
                  fill-input
                  hide-selected
                  v-model="product"
                  :loading="prodLoading"
                  option-value="id"
                  option-label="name"
                  @input-value="(v) => (product = capitaliseFn(v))"
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
              <div class="" v-if="model.type==='Export'">
                <q-checkbox v-model="useMask" label="Use Mask?"/>
              </div>
              <div class="col" v-if="model.type !== 'Export'">
                <q-select
                  filled
                  square
                  dense
                  v-model="gst"
                  ref="gst"
                  :rules="[(v) => !!v || 'Required']"
                  lazy-rules="ondemand"
                  :options="gstOptions"
                  label="GST"
                  map-options
                  emit-value
                />
              </div>
              <div class="col">
                <q-input
                  filled
                  square
                  dense
                  v-model="qty"
                  label="Qty"
                  class="input-right"
                  @focus="qty && $event.target.select()"
                  ref="qty"
                  :rules="[
                    (v) => !!v || 'Required',
                    (v) => Number.isInteger(Number(v)) || 'Must be an Integer',
                    (v) => Number(v) > 0 || 'Invalid',
                  ]"
                  @blur="
                    () => {
                      qty = qty ? parseInt(qty).toString() : null;
                    }
                  "
                  lazy-rules="ondemand"
                  :hint="
                    product
                      ? stock && stock > 0
                        ? stock + ' Available'
                        : 'Out of stock'
                      : ''
                  "
                />
              </div>
              <div class="col">
                <q-input
                  filled
                  square
                  dense
                  :prefix="currencySymbol"
                  v-model="rate"
                  label="Rate"
                  @focus="rate && $event.target.select()"
                  @blur="
                    () => {
                      rate = rate ? parseFloat(rate).toFixed(2) : null;
                    }
                  "
                  ref="rate"
                  :rules="[rateValidation]"
                  lazy-rules="ondemand"
                  class="input-right"
                />
              </div>
              <div class="col" v-if="!rate_includes_gst">
                <q-input
                  filled
                  square
                  dense
                  :prefix="currencySymbol"
                  v-model="row_taxable"
                  readonly
                  label="Taxable"
                />
              </div>
              <div class="col" v-if="!rate_includes_gst">
                <q-input
                  filled
                  square
                  :prefix="currencySymbol"
                  dense
                  v-model="row_tax_amount"
                  readonly
                  label="Tax Amount"
                />
              </div>
              <div class="col">
                <q-input
                  filled
                  square
                  dense
                  :prefix="currencySymbol"
                  v-model="row_total"
                  readonly
                  label="Total"
                />
              </div>
              <div class="">
                <q-btn icon="add" round flat color="teal" @click="addRowValidation()" />
              </div>
            </div>
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-4">
                <q-input filled dense v-model="maskName" label="Mask Name" v-if="useMask"/>
              </div>
            </div>
          </q-form>
          <div class="row q-mt-xs">
            <div class="col-12 col-md-10 q-px-md">
            Total Weight: {{totalWeight}}gm
            </div>
            <div class="col-12 col-md-2">
              <div class="column">
                <div class="col">
                  <q-input
                    label="Subtotal(Incl GST)"
                    class="input-right"
                    dense
                    :prefix="currencySymbol"
                    filled
                    square
                    readonly
                    v-model="model.subtotal"
                    ref="subtotal"
                    :rules="[
                      (v) => v > 0 || 'Invalid',
                      (v) => !isNaN(v) || 'Invalid',
                    ]"
                  />
                </div>
                <div class="col">
                  <q-input
                    label="Discount"
                    class="input-right"
                    dense
                    filled
                    square
                    :prefix="currencySymbol"
                    v-model="model.discount"
                    ref="discount"
                    :rules="[(v) => !isNaN(v) || 'Invalid']"
                    @focus="$event.target.select()"
                  />
                </div>
                <div class="col">
                  <q-input
                    label="Freight"
                    class="input-right"
                    dense
                    filled
                    square
                    :prefix="currencySymbol"
                    v-model="model.freight"
                    ref="freight"
                    :rules="[(v) => !isNaN(v) || 'Invalid']"
                    @focus="$event.target.select()"
                  />
                </div>
                <div class="col"  v-if="model.type === 'Export'">
                  <q-input
                    label="Prev. Balance"
                    class="input-right"
                    dense
                    filled
                    square
                    :prefix="currencySymbol"
                    v-model="model.prev_balance"
                    ref="prev_balance"
                    :rules="[(v) => !isNaN(v) || 'Invalid']"
                    @focus="$event.target.select()"
                  />
                </div>
                <div class="col" v-if="model.type === 'Export'">
                  <q-input
                    label="Bank Charges"
                    class="input-right"
                    dense
                    filled
                    square
                    :prefix="currencySymbol"
                    v-model="model.bank_charges"
                    ref="bank_charges"
                    :rules="[(v) => !isNaN(v) || 'Invalid']"
                    @focus="$event.target.select()"
                  />
                </div>
                <div class="col">
                  <q-input
                    label="Round"
                    class="input-right"
                    dense
                    filled
                    readonly
                    square
                    :prefix="currencySymbol"
                    v-model="model.round"
                    ref="round"
                    :rules="[(v) => !isNaN(v) || 'Invalid']"
                    @focus="$event.target.select()"
                  />
                </div>
                <div class="col">
                  <q-input
                    label="Total"
                    class="input-right"
                    dense
                    filled
                    readonly
                    square
                    :prefix="currencySymbol"
                    v-model="model.total"
                    ref="total"
                    :rules="[
                      (v) => v > 0 || 'Invalid',
                      (v) => !isNaN(v) || 'Invalid',
                    ]"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="row q-col-gutter-md">
            <div class="col-12 col-md">
              <q-input
                label="Contact Person"
                hint="If left blank, Representative set above will be used"
                v-model="model.contact_person"
                filled
                square
                dense
              />
            </div>
            <div class="col-12 col-md">
              <q-input
                label="Contact Phone"
                v-model="model.contact_phone"
                filled
                dense
                square
                ref="contact_phone"
                :rules="[(v) => !!v || 'Required']"
              />
            </div>
            <div class="col-12 col-md">
              <q-input
                label="Contact Email"
                v-model="model.contact_email"
                filled
                dense
                square
                ref="contact_email"
                :rules="[(v) => !!v || 'Required']"
              />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="text-subtitle1 q-mt-md">Instructions</div>
              <q-editor v-model="model.instructions" min-height="5rem" />
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="text-subtitle1 q-mt-md">Terms</div>
              <q-editor v-model="model.terms" min-height="5rem" />
              <div class="text-caption">The terms in this text box will be added along with default terms & conditions in quotation print</div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="text-subtitle1 q-mt-md">Remarks</div>
              <q-editor v-model="model.remarks" min-height="5rem" />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
    <page-toolbar
      :page-title="''"
      :buttons="toolbarButtons"
      @save="saveFn()"
      class="q-mt-md"
    />
    <q-drawer
      v-model="templateDialog"
      :width="500"
      persistent
      overlay
      bordered
      elevated
      :breakpoint="1400"
      side="right"
      behaviour="mobile"
      content-class="bg-grey-3"
    >
      <q-scroll-area class="fit">
        <q-table
          title="Quotation Templates"
          :data="quotation_templates"
          :columns="[
          {name: 'name', label: 'Name', field: 'name', align: 'left', sortable: true},
          {name: 'actions', label: 'Actions', field: 'actions', align: 'right', sortable: false}
          ]"
          row-key="name">
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="name" :props="props">
                {{ props.row.name }}
              </q-td>
              <q-td key="actions" :props="props">
                <q-btn color="primary" label="Load" @click="loadTemplate(props.row.id)"/>
              </q-td>
            </q-tr>
          </template>
        </q-table>
        <q-btn class="q-mt-md" label="Close" color="primary" @click="templateDialog = false"/>
      </q-scroll-area>
    </q-drawer>
    <q-dialog v-model="maskDialog">
      <q-card style="width:500px">
        <q-card-section>
          <div class="row">
            <div class="col">
              <q-checkbox v-model="useMask" label="Use Mask?"/>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <q-input filled dense v-model="maskName" label="Mask Name"/>
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <q-btn flat color="primary" no-caps label="Ok" @click="closeMaskDialog"/>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
import draggable from 'vuedraggable'
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'QuotationsCreate',
  components: {
    PageToolbar,
    Breadcrumbs,
    draggable

  },
  data () {
    return {
      currencyOptions: [
        {
          label: 'US Dollar',
          symbol: '$',
          value: 'USD'
        },
        {
          label: 'Indian Rupee',
          symbol: '₹',
          value: 'INR'
        }
      ],
      maskDialog: false,
      maskEditIndex: null,
      model: {
        currency: 'INR',
        bank: null,
        rate_includes_gst: true,
        serial_no: null,
        customer: null,
        warehouse: null,
        pricelist: null,
        items: [],
        subtotal: 0,
        total: 0,
        round: 0,
        discount: 0,
        freight: 0,
        flood_cess_included: 0,
        representative: null,
        created_by: null,
        remarks: '',
        instructions: '',
        terms: '',
        status: 'Draft',
        inco_term: 'FOB',
        fob_point: null,
        po_number: null,
        contact_person: null,
        contact_phone: null,
        contact_email: null,
        ship_date: null,
        ship_via: null,
        billingAddress: null,
        type: 'Standard',
        prev_balance: 0,
        bank_charges: 0,
        valid_until: null
      },
      incoTerms: [
        { label: 'Free On Board(FOB)', value: 'FOB' },
        { label: 'Free Alongside Ship (FAS)', value: 'FOB' },
        { label: 'Cost and Freight (CFR)', value: 'CFR' },
        { label: 'Cost, Insurance and Freight (CIF)', value: 'CIF' },
        { label: 'Free On Board(FOB)', value: 'FOB' }
      ],
      weight: 0,
      bankAccounts: [],
      warehouses: [],
      pricelists: [],
      representativeOptions: [],
      search: null,
      table_loading: false,
      product: null,
      useMask: false,
      maskName: null,
      gst: null,
      qty: null,
      rate: null,
      row_taxable: null,
      row_tax_amount: null,
      row_total: null,
      prodLoading: false,
      productOptions: [],
      gstOptions: [],
      createdByOptions: [],
      stock: null,
      stockOptions: [],
      cost: null,
      min_margin: 0,
      templateDialog: false,
      quotation_templates: [],
      customerOptions: []
    }
  },
  computed: {
    currencySymbol () {
      if (this.model.currency === 'INR') {
        return '₹'
      }
      return '$'
    },
    totalWeight () {
      return this.$_.sumBy(this.model.items, v => v.product ? parseInt(v.qty) * parseFloat(v.product.weight) : 0)
    },
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('create_quotation') && !this.$route.params.id) {
        arr.push({
          label: 'Load Template',
          id: 'load',
          type: 'button',
          color: 'blue-10',
          icon: 'publish'
        })
      }
      if (this.$store.getters.hasPermissionTo('create_quotation')) {
        arr.push({
          label: 'Save as Draft',
          id: 'save',
          type: 'button',
          color: 'teal',
          icon: 'save'
        })
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Quotations', link: '/quotations' },
        {
          label: this.$route.params.id ? this.model.serial_no : 'Create',
          link: '/quotations/view/' + this.$route.params.id
        }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
      }
      return arr
    },
    columns () {
      const arr = [
        {
          name: 'sl_no',
          field: 'sl_no',
          label: '#',
          sortable: true
        },
        {
          name: 'name',
          field: 'prouduct_name',
          label: 'Product',
          sortable: true,
          align: 'left'
        },
        {
          name: 'gst',
          field: 'gst',
          label: 'GST',
          sortable: true
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: true
        },
        {
          name: 'rate',
          field: 'rate',
          label: 'Rate',
          sortable: true
        }
      ]
      if (!this.rate_includes_gst) {
        arr.push(
          {
            name: 'taxable',
            field: 'taxable',
            label: 'Taxable',
            sortable: true
          },
          {
            name: 'tax_amount',
            field: 'tax_amount',
            label: 'Tax Amount',
            sortable: true
          }
        )
      }
      arr.push(
        {
          name: 'total',
          field: 'total',
          label: 'Total',
          sortable: true
        },
        {
          name: 'actions',
          field: 'actions',
          label: '',
          sortable: false
        }
      )
      return arr
    },
    subtotal () {
      return this.model.subtotal
    },
    freight () {
      return this.model.freight
    },
    discount () {
      return this.model.discount
    },
    rate_includes_gst () {
      return this.model.rate_includes_gst
    },
    bankCharges () {
      return this.model.bank_charges
    },
    prevBalance () {
      return this.model.prev_balance
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('create_quotation')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      const userPermission = ['create_quotation', 'update_quotation']
      Promise.all([
        this.$axios.get('warehouses').then((res) => {
          this.warehouses = res.data.model
          if (this.warehouses.length === 1) {
            this.model.warehouse = this.warehouses[0]
          }
        }),
        this.$axios.get('pricelists').then((res) => {
          this.pricelists = res.data.model
          if (this.pricelists.length === 1) {
            this.model.pricelist = this.pricelists[0]
          }
        }),
        this.$axios.get('gst_options').then((res) => {
          this.gstOptions = res.data
        }),
        this.$axios.get('quotation_templates').then((res) => {
          this.quotation_templates = res.data.model
        }),
        this.$axios.get(
          'users/has_permission?permissions=' +
            encodeURIComponent(JSON.stringify(userPermission))
        )
          .then((res) => {
            this.createdByOptions = res.data
            this.model.created_by = this.$store.getters.user
          }),
        this.$axios.get('config/back_accounts').then((res) => {
          this.bankAccounts = res.data.value
        })
      ]).finally(() => {
        this.$q.loading.hide()
      })
      if (this.$route.params.id) {
        this.$q.loading.show()
        this.$axios
          .get('quotations/' + this.$route.params.id)
          .then((res) => {
            this.model = res.data
            this.model.remarks = res.data.remarks ? res.data.remarks : ''
            this.model.instructions = res.data.instructions
              ? res.data.instructions
              : ''
            this.model.terms = res.data.terms ? res.data.terms : ''
          })
          .finally(() => {
            this.loadAddress()
            this.$q.loading.hide()
          })
      }
      if (this.$route.params.customer_id) {
        this.$axios.get('customers/' + this.$route.params.customer_id).then((res) => {
          this.model.customer = {
            name: res.data.name,
            id: res.data.id
          }
          if (res.data.name !== 'OTC') { this.loadAddress() }
        })
      }
    }
  },
  methods: {
    addRowValidation () {
      this.$refs.rowForm.validate().then((success) => {
        if (typeof (this.product) === 'object' && this.$_.findIndex(this.model.items, (v) => v.product_id === this.product.id) !== -1) {
          this.$q.dialog({
            title: 'Duplicate Entry !!',
            message: 'This product is already entered. Would you like to continue?',
            cancel: true,
            persistent: true,
            ok: {
              label: 'Continue'
            }
          }).onOk(() => {
            if (this.cost > 0 && parseFloat(this.rate) < parseFloat(this.cost)) {
              this.$q.dialog({
                title: 'Warning',
                message: 'The rate you entered is less than cost [' + this.cost + ']. Continue?',
                cancel: true
              }).onOk(() => {
                this.addRow()
              })
            } else {
              this.addRow()
            }
          })
        } else {
          if (this.cost > 0 && parseFloat(this.rate) < parseFloat(this.cost)) {
            this.$q.dialog({
              title: 'Warning',
              message: 'The rate you entered is less than cost [' + this.cost + ']. Continue?',
              cancel: true
            }).onOk(() => {
              this.addRow()
            })
          } else {
            this.addRow()
          }
        }
      })
    },
    addRow () {
      const qty = parseInt(this.qty)
      const rate = this.rate ? parseFloat(this.rate) : 0
      const gst = this.gst ? parseFloat(this.gst) : 0
      let taxable = 0
      let taxAmount = 0
      let total = 0
      if (!this.model.rate_includes_gst) {
        taxable = (rate * qty)
        /*
        if (this.flood_cess_included && gst !== 5) {
          taxAmount = taxable * ((gst + 1) / 100)
        } else {
          taxAmount = taxable * (gst / 100)
        }
        */
        taxAmount = taxable * (gst / 100)
        total = taxable + taxAmount
      } else {
        total = (rate * qty)
      }
      this.model.items.push({
        name: this.product.name,
        product_name: typeof this.product === 'object' ? this.product.name : this.product,
        product_id: typeof this.product === 'object' ? this.product.id : 0,
        use_mask: this.useMask,
        mask_name: this.maskName,
        gst: this.gst,
        product: {
          weight: this.weight
        },
        qty: qty,
        rate: this.rate,
        taxable: taxable.toFixed(2),
        tax_amount: taxAmount.toFixed(2),
        total: total.toFixed(2),
        stock: this.stock,
        cost: this.cost,
        min_margin: this.min_margin
      })
      this.addtoSubtotal(total)
      this.product = null
      this.gst = null
      this.qty = null
      this.rate = null
      this.weight = 0
      this.useMask = false
      this.maskName = null
      this.maskEditIndex = null
      this.row_discount = null
      this.row_taxable = null
      this.row_tax_amount = null
      this.row_total = null
      this.stock = null
      this.cost = null
      this.$nextTick(() => {
        this.$refs.product.focus()
      })
    },
    addtoSubtotal (val) {
      this.model.subtotal = (parseFloat(this.model.subtotal) + parseFloat(val)).toFixed(2)
    },
    deleteFn (index) {
      this.$q
        .dialog({
          title: 'Confirm',
          message: 'Would you like to delete this record?',
          cancel: true,
          persistent: true
        })
        .onOk(() => {
          if (this.model.items[index].id) {
            this.$q.loading.show()
            this.$axios.get('quotations/delete_item/' + this.model.items[index].id).then((res) => {
              if (res.data.message === 'success') {
                const total = parseFloat(this.model.items[index].total)
                this.model.subtotal = parseFloat(this.model.subtotal) - total
                this.model.items.splice(index, 1)
              }
            }).finally(() => {
              this.$q.loading.hide()
            })
          } else {
            const total = parseFloat(this.model.items[index].total)
            this.model.subtotal = parseFloat(this.model.subtotal) - total
            this.model.items.splice(index, 1)
          }
        })
        .onCancel(() => {})
    },
    updateRow (index) {
      const oldTotal = parseFloat(this.model.items[index].total)
      const qty = parseInt(this.model.items[index].qty)
      const rate = parseFloat(this.model.items[index].rate)
      const gst = parseFloat(this.model.items[index].gst)
      let total = 0
      if (!this.model.rate_includes_gst) {
        let taxable = 0
        taxable = rate * qty
        this.model.items[index].taxable = taxable.toFixed(2)
        let taxAmount = 0
        /*
        if (this.flood_cess_included && gst !== 5) {
          taxAmount = taxable * ((gst + 1) / 100)
        } else {
          taxAmount = taxable * (gst / 100)
        }
        */
        taxAmount = taxable * (gst / 100)
        this.model.items[index].tax_amount = taxAmount.toFixed(2)
        total = taxable + taxAmount
        this.model.items[index].total = total.toFixed(2)
      } else {
        total = rate * qty
        this.model.items[index].total = total.toFixed(2)
      }
      const diff = total - oldTotal
      this.addtoSubtotal(diff)
    },
    updateTotal () {
      const subtotal = parseFloat(this.model.subtotal)
      const discount = parseFloat(this.model.discount)
      const freight = parseFloat(this.model.freight)
      const bankCharges = parseFloat(this.model.bank_charges)
      const prevBalance = parseFloat(this.model.prev_balance)

      let total = 0
      total = subtotal - discount + freight + bankCharges + prevBalance
      this.model.round = (Math.round(total) - total).toFixed(2)
      this.model.total = Math.round(total).toFixed(2)
    },
    customerFilterFn (val, update, abort) {
      if (val) {
        this.$axios
          .get(
            'customers_search?include_leads=1&billable=1&search=' + encodeURIComponent(val)
          )
          .then((res) => {
            update(() => {
              this.customerOptions = res.data
            })
          })
      } else {
        abort()
      }
    },
    qtyEditValidation (val) {
      if (!val) return 'Required'
      else if (isNaN(val)) return 'invalid'
      else if (!Number.isInteger(Number(val))) return 'Must be Integer'
      return true
    },
    rateEditValidation (val) {
      if (val === null || val === undefined || val === '') return 'Required'
      else if (isNaN(val)) return 'Invalid'
      else if (parseFloat(val) < 0) return 'Invalid'
      return true
    },
    rateValidation (val) {
      if (val === null || val === undefined || val === '') return 'Required'
      else if (isNaN(val)) return 'Invalid'
      else if (parseFloat(val) < 0) return 'Invalid'
      return true
    },
    updateFields () {
      const qty = this.qty ? parseInt(this.qty) : 0
      const rate = this.rate ? parseFloat(this.rate) : 0
      const gst = this.gst ? parseFloat(this.gst) : 0
      if (!this.model.rate_includes_gst) {
        let taxable = 0
        taxable = rate * qty
        this.row_taxable = taxable.toFixed(2)
        let taxAmount = 0
        /*
        if (this.flood_cess_included && gst !== 5) {
          taxAmount = taxable * ((gst + 1) / 100)
        } else {
          taxAmount = taxable * (gst / 100)
        }
        */
        taxAmount = taxable * (gst / 100)
        this.row_tax_amount = taxAmount.toFixed(2)
        const total = taxable + taxAmount
        this.row_total = total.toFixed(2)
      } else {
        this.row_total = (rate * qty).toFixed(2)
      }
    },
    productFilter (val, update, abort) {
      if (val) {
        this.prodLoading = true
        this.$axios
          .get('products/search?inclall=1&keyword=' + encodeURIComponent(val))
          .then((res) => {
            update(() => {
              this.productOptions = res.data
            })
          })
          .finally(() => {
            this.prodLoading = false
          })
      } else {
        abort()
      }
    },

    saveFn (status = 'Draft') {
      if (
        this.$refs.customer.validate() &
        this.$refs.representative.validate() &
        this.$refs.pricelist.validate() &
        this.$refs.warehouse.validate() &
        this.$refs.created_by.validate() &
        this.$refs.valid_until.validate() &
        this.$refs.contact_phone.validate() &
        this.$refs.contact_email.validate() &
        this.$refs.bank.validate() &
        this.$refs.type.validate() &
        this.$refs.inco_term.validate() &
        this.$refs.currency.validate() &
        this.validateItemsCount()
      ) {
        const obj = this.model
        obj._method = this.$route.params.id ? 'PUT' : 'POST'
        let route = 'quotations'
        if (this.$route.params.id) {
          route = 'quotations/' + this.$route.params.id
        }
        this.$q.loading.show()
        this.$axios
          .post(route, obj)
          .then((res) => {
            if (res.data.message === 'success') {
              this.$q.loading.hide()
              this.$q.notify({
                type: 'positive',
                message: 'Saved Succesfully.'
              })
              this.$router.back()
            }
          })
          .catch((error) => {
            this.$q.loading.hide()
            if (error.response.status === 422) {
              this.$q.notify({
                type: 'negative',
                message: error.response.data.message
              })
              error.response.data.errors.forEach((err) => {
                this.$q.notify({
                  type: 'negative',
                  message: err
                })
              })
            }
          })
          .finally(() => {
            this.$q.loading.hide()
          })
      } else {
        this.$q.notify({
          type: 'negative',
          message: 'Invalid data given !!!'
        })
      }
    },
    validateItemsCount () {
      if (this.model.items.length > 0) {
        return true
      }
      this.$q.notify({
        type: 'negative',
        message: 'There should be atleast one item'
      })
      return false
    },
    capitaliseFn (str) {
      if (str) {
        const splitStr = str.split(' ')
        for (let i = 0; i < splitStr.length; i++) {
          splitStr[i] =
            splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1)
        }
        return splitStr.join(' ')
      }
      return ''
    },
    loadTemplate (id) {
      const ind = this.quotation_templates.findIndex((item) => item.id === id)
      this.$q.dialog({
        title: 'Caution',
        message: 'Do you really want to load \'' + this.quotation_templates[ind].name + '\' template ?. The data you entered will be replaced with the content of template. Click OK to continue',
        cancel: true
      }).onOk(() => {
        this.$q.loading.show()
        this.$axios.get('quotation_templates/' + id).then((res) => {
          Object.keys(res.data).forEach((key) => {
            // console.log(key)
            if (key !== 'customer' && key !== 'subtotal' && key !== 'total' && key !== 'round' && key !== 'terms' && key !== 'instructions' && key !== 'remarks') {
              // console.log(key)
              this.model[key] = res.data[key]
            }
          })
          if (!this.$route.params.customer_id) {
            // console.log('kitti')
            this.model.customer = res.data.customer
          }
          // this.model = res.data
          this.remarks = res.data.remarks ? res.data.remarks : ''
          this.instructions = res.data.instructions
            ? res.data.instructions
            : ''
          this.terms = res.data.terms ? res.data.terms : ''
          if (this.model.rate_includes_gst) {
            this.$q.loading.show()
            let subtotal = 0
            this.model.items.forEach((item, i) => {
              let total = 0
              const rate = parseFloat(item.rate)
              const qty = parseInt(item.qty)
              total = qty * rate
              subtotal += parseFloat(total.toFixed(2))
              this.model.items[i].total = total.toFixed(2)
            })
            this.model.subtotal = subtotal
            this.$q.loading.hide()
          } else {
            this.$q.loading.show()
            let subtotal = 0
            this.model.items.forEach((item, i) => {
              let total = 0
              let taxable = 0
              let taxAmount = 0
              const rate = parseFloat(item.rate)
              const qty = parseInt(item.qty)
              const gst = parseFloat(item.gst)
              /*
              if (this.flood_cess_included && gst !== 5) {
                gst = gst + 1
              }
              */
              taxable = rate * qty
              taxAmount = taxable * (gst / 100)
              total = taxable + taxAmount
              this.model.items[i].taxable = taxable.toFixed(2)
              this.model.items[i].tax_amount = taxAmount.toFixed(2)
              this.model.items[i].total = total.toFixed(2)
              subtotal += parseFloat(total)
            })
            this.model.subtotal = subtotal.toFixed(2)
            this.$q.loading.hide()
          }
        }).finally(() => {
          this.loadAddress()
          this.templateDialog = false
          this.$q.loading.hide()
        })
      })
    },
    loadAddress () {
      if (this.model.customer) {
        if (this.model.customer.name !== 'OTC') {
          this.$q.loading.show()
          this.model.billingAddress = null
          this.$axios.get('customer_addresses/' + this.model.customer.id).then((res) => {
            if (res.data.representatives.length === 1) {
              this.model.representative = res.data.representatives[0]
            }

            res.data.addresses.forEach((addr) => {
              const str = this.addressFormat(addr)
              if (addr.tag_name === 'billing') {
                this.model.billingAddress = str
                /*
                if (addr.state === 'Kerala') this.flood_cess_included = 1
                else this.flood_cess_included = 0
                */
              }
            })
          }).finally(() => {
            this.$q.loading.hide()
          })
        } else {
          /*
          this.flood_cess_included = 1
          */
        }
      }
    },
    addressFormat (addr) {
      let str = ''
      str += addr.line_1 + ', '
      if (addr.line_2) { str += addr.line_2 + ', ' }

      if (addr.district) {
        str += addr.district + ', '
      }
      str += addr.state + ', '
      if (addr.pin) { str += 'PIN: ' + addr.pin + ', ' }
      str += addr.country + ', '
      str += 'Ph: '
      addr.phones.forEach((item) => {
        str += '(' + item.country_code + ')' + item.content + ', '
      })
      str = str.substr(0, str.length - 2)
      return str
    },
    showMaskDetails (item, ind) {
      this.maskName = item.mask_name
      this.useMask = item.use_mask
      this.maskEditIndex = ind
      this.maskDialog = true
    },
    closeMaskDialog () {
      this.model.items[this.maskEditIndex].use_mask = this.useMask
      this.model.items[this.maskEditIndex].mask_name = this.maskName
      this.useMask = false
      this.maskName = null
      this.maskEditIndex = null
      this.maskDialog = false
    },
    onTypeChange (val) {
      if (val === 'Export') {
        this.model.rate_includes_gst = true
      }
    }
  },
  watch: {
    product (newV, oldV) {
      if (newV && typeof newV === 'object') {
        this.$axios
          .get('products/basic/' + newV.id + '/' + this.model.warehouse.id)
          .then((res) => {
            this.gst = res.data.gst
            this.expirable = res.data.expirable
            this.weight = res.data.weight
            this.hsn = res.data.hsn
            this.mrp = res.data.mrp
            this.stock = res.data.available_stock
            this.stockOptions = res.data.stock_options
            this.cost = res.data.cost ? res.data.cost : 0
            this.min_margin = res.data.min_margin ? res.data.min_margin : 0
            this.maskName = res.data.mask_name
          })
      } else {
        this.gst = null
        this.stock = null
      }
    },
    qty (newV, oldV) {
      this.updateFields()
    },
    rate (newV, oldV) {
      this.updateFields()
    },
    row_discount (newV, oldV) {
      this.updateFields()
    },
    gst (newV, oldV) {
      this.updateFields()
    },
    subtotal () {
      this.updateTotal()
    },
    discount () {
      this.updateTotal()
    },
    freight () {
      this.updateTotal()
    },
    bankCharges () {
      this.updateTotal()
    },
    prevBalance () {
      this.updateTotal()
    },
    rate_includes_gst (newV, oldV) {
      if (newV) {
        this.$q.loading.show()
        let subtotal = 0
        this.model.items.forEach((item, i) => {
          let total = 0
          const rate = parseFloat(item.rate)
          const qty = parseInt(item.qty)
          total = qty * rate
          subtotal += parseFloat(total.toFixed(2))
          this.model.items[i].total = total.toFixed(2)
        })
        this.model.subtotal = subtotal
        this.$q.loading.hide()
      } else {
        this.$q.loading.show()
        let subtotal = 0
        this.model.items.forEach((item, i) => {
          let total = 0
          let taxable = 0
          let taxAmount = 0
          const rate = parseFloat(item.rate)
          const qty = parseInt(item.qty)
          const gst = parseFloat(item.gst)
          /*
          if (this.flood_cess_included && gst !== 5) {
            gst = gst + 1
          }
          */
          taxable = rate * qty
          taxAmount = taxable * (gst / 100)
          total = taxable + taxAmount
          this.model.items[i].taxable = taxable.toFixed(2)
          this.model.items[i].tax_amount = taxAmount.toFixed(2)
          this.model.items[i].total = total.toFixed(2)
          subtotal += parseFloat(total)
        })
        this.model.subtotal = subtotal.toFixed(2)
        this.$q.loading.hide()
      }
    }
    /*
    flood_cess_included (newV, oldV) {
      if (!this.rate_includes_gst) {
        this.$q.loading.show()
        let subtotal = 0
        this.items.forEach((item, i) => {
          let total = 0
          let taxable = 0
          let taxAmount = 0
          const rate = parseFloat(item.rate)
          const qty = parseInt(item.qty)
          let gst = parseFloat(item.gst)
          if (this.flood_cess_included && gst !== 5) {
            gst = gst + 1
          }
          taxable = rate * qty
          taxAmount = taxable * (gst / 100)
          total = taxable + taxAmount
          this.items[i].taxable = taxable.toFixed(2)
          this.items[i].tax_amount = taxAmount.toFixed(2)
          this.items[i].total = total.toFixed(2)
          subtotal += parseFloat(total)
        })
        this.subtotal = subtotal.toFixed(2)
        this.$q.loading.hide()
      }
    }
    */
  }
}
</script>
