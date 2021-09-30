<template>
  <q-page>
    <page-toolbar
      :buttons="toolbarButtons"
      v-on:save="saveFn()"
      v-on:sendtoaccounts="sendtoaccounts()"
      v-on:sendforconfirmation="sendforconfirmation()"/>
    <breadcrumbs :breadcrumbs="breadcrumbs"/>
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md': 'q-px-xs q-mt-sm'">
      <q-card>
        <q-card-section>
          <div :class="$q.screen.gt.sm ? 'row q-col-gutter-md' : 'row q-col-gutter-xs'">
            <div :class="$q.screen.gt.sm ? 'col-md-6 col-12 q-mt-md' : 'col-md-6 col-12 q-mt-xs'">
              <q-select
                dense
                filled square
                label="Customer"
                v-model="model.customer"
                use-input
                fill-input
                hide-selected
                :options="customerOptions"
                @filter="customerFilterFn"
                option-value="id"
                option-label="name"
                ref="customer"
                :rules="[v=>!!v.name||'Required']"
                @input="onInputCustomer"
                tabindex="1">
                <template v-slot:no-option>
                    <q-item>
                        <q-item-section class="text-grey">
                        No results
                        </q-item-section>
                    </q-item>
                </template>
              </q-select>
            </div>
            <div :class="$q.screen.gt.sm ? 'col-md-6 col-12 q-mt-md' : 'col-md-6 col-12 q-mt-xs'">
              <q-select
                dense
                v-model="model.representative"
                label="Representative"
                option-value="id"
                option-label="name"
                filled square
                :options="representativeOptions"
                ref="representative"
                :rules="[v=>!!v.name||'Required']"
                tabindex="2"/>
            </div>
          </div>
          <div class="row" v-if="model.billing_address && model.customer.name!= 'OTC'">
            <div class="col-12 col-md-6 q-mt-md q-pa-sm">
              <q-card>
                <q-card-section class="bg-blue-10 text-white">
                    <div class="text-subtitle2 ">Billing Address</div>
                </q-card-section>
                <q-card-section>
                    <div class="text-subtitle1">{{model.billing_address}}</div>
                </q-card-section>
              </q-card>
            </div>
            <div class="col-12 col-md-6 q-mt-md q-pa-sm">
              <q-card>
                <q-card-section class="bg-orange-10 text-white">
                  <div class="text-subtitle2 ">Shipping Address</div>
                </q-card-section>
                <q-card-section>
                  <div class="text-subtitle1" >{{model.shipping_address.content}}</div>
                </q-card-section>
              </q-card>
              <q-btn color="blue-10" label="New" @click="openShippingDialog" class="q-mt-md"/>
              <q-btn color="orange-10" label="select" @click="addressSelectionDialog = true" class="q-mt-md q-ml-md"/>
            </div>
          </div>
          <div class="row q-col-gutter-md q-mt-xs">
            <div class="col-md col-12">
              <q-select
                dense
                v-model="model.pricelist"
                label="Pricelist"
                option-value="id"
                option-label="name"
                filled square
                :options="pricelists"
                ref="pricelist"
                :rules="[v=>!!v||'Required']"
                tabindex="3"/>
            </div>
            <div class="col-md col-12">
              <q-select
                dense
                v-model="model.warehouse"
                label="Warehouse"
                option-value="id"
                option-label="name"
                filled square
                :options="warehouses"
                ref="warehouse"
                :rules="[v=>!!v||'Required']"
                tabindex="4"/>
            </div>
            <div class="col-md col-12">
              <q-select
                dense
                label="Source"
                filled square
                v-model="model.source"
                :options="sources"
                ref="source"
                :rules="[v=>!!v||'Required']"
                tabindex="5">
                  <template v-slot:no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No results
                    </q-item-section>
                  </q-item>
                  </template>
              </q-select>
            </div>
            <div class="col-md col-12" v-show="model.source == 'Dentodeal'">
              <q-input v-model="model.order_no"
              label="Order No" filled square dense ref="order_no"
              :rules="[v => !v && model.source == 'Dentodeal' ? 'Required' : true]"
              tabindex="6"/>
            </div>
            <div class="col-md col-12">
              <q-select
                dense
                v-model="model.created_by"
                label="Created By"
                option-value="id"
                option-label="name"
                filled square
                ref="created_by_id"
                :rules="[v=>!!v.name||'Required']"
                :options="createdByOptions"
                tabindex="6"/>
            </div>
          </div>
          <div class="row q-mt-xs" v-if="model.type != 'Export'">
            <div class="col">
              <q-checkbox  label="Rate Includes GST ?" v-model="model.rate_includes_gst" color="green-10" tabindex="7"/>
              <q-checkbox label="Include Flood Cess ?" v-model="model.flood_cess_included" color="orange-10" tabindex="7" disable/>
              <q-checkbox label="OTC" v-model="model.otc" color="accent" tabindex="7"/>
              <q-checkbox label="COD" v-if="!model.otc" v-model="model.cod" color="teal-10" @input="model.cod_charge = 0" tabindex="7"/>
            </div>
          </div>
          <div class="row q-mt-xs">
            <div class="col">
              <q-table
                :columns="columns"
                title="Items"
                :data="model.items"
                wrap-cells
                :loading="table_loading"
                :rows-per-page-options="[0]"
                :filter="search"
                >
                <template v-slot:body="props">
                  <q-tr :props="props">
                    <q-td class="text-right">{{props.rowIndex+1}}</q-td>
                    <q-td>
                      {{props.row.product.name}}
                      <q-popup-edit v-model="props.row.product.name"
                        buttons
                        label-set="Save"
                        label-cancel="Close"
                        @hide="()=>{props.row.gst = gst; props.row.tax_amount = !rate_includes_gst ? parseFloat(gst) / 100 * parseFloat(props.row.rate) : 0; product=null; gst = null}"
                        @show="()=>{product=props.row.product.name}"
                        >
                        <q-select
                          filled square dense
                          label="Product"
                          :rules="[v=>!!v||'Required']"
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
                          @input="props.row.product = product;"
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
                      <q-btn flat icon="visibility" round size="sm" @click.stop="showMaskDetails(props.row,props.rowIndex)">
                        <q-tooltip>View Masking Details</q-tooltip>
                      </q-btn>
                    </q-td>
                    <q-td class="text-right">
                      {{props.row.gst}}%
                      <q-popup-edit v-model="props.row.gst"
                        buttons
                        label-set="Save"
                        label-cancel="Close"
                        @hide="updateRow(props.rowIndex)"
                        >
                        <q-select
                          v-model="props.row.gst"
                          :options="gstOptions"
                          map-options
                          emit-value
                          dense autofocus/>
                      </q-popup-edit>
                    </q-td>
                    <q-td class="text-right">
                      {{props.row.qty}}
                      <q-popup-edit v-model="props.row.qty"
                        buttons
                        label-set="Save"
                        label-cancel="Close"
                        :validate="v => qtyEditValidation(v) === true ? true : false"
                        @hide="updateRow(props.rowIndex);props.row.qty = parseInt(props.row.qty)"
                        >
                        <q-input
                          @input="$refs.qty_edit.resetValidation()"
                          ref="qty_edit"
                          :rules="[qtyEditValidation]"
                          v-model="props.row.qty"
                          dense
                          autofocus
                          />
                      </q-popup-edit>
                    </q-td>
                    <q-td class="text-right">
                      {{currencySymbol}}{{props.row.rate}}
                      <q-popup-edit v-model="props.row.rate"
                        buttons
                        label-set="Save"
                        label-cancel="Close"
                        :validate="v => rateEditValidation(v) === true ? true: false"
                        @hide="updateRow(props.rowIndex);props.row.rate = parseFloat(props.row.rate).toFixed(2)"
                        >
                        <q-input
                          @input="$refs.rate_edit.resetValidation()"
                          v-model="props.row.rate"
                          ref="rate_edit"
                          :rules="[rateEditValidation]"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td class="text-right">
                      {{props.row.expiry_date}}
                      <q-popup-edit v-model="props.row.expiry_date"
                        buttons
                        label-set="Save"
                        label-cancel="Close"
                        @show="getExpiryOptions(props.row.product.id)"
                        @hide="expiryOptions = []"
                        >
                        <q-select
                          v-model="props.row.expiry_date"
                          option-value="expiry_date"
                          option-label="expiry_date"
                          emit-value
                          :options="expiryOptions"
                          dense
                          autofocus
                        />
                      </q-popup-edit>
                    </q-td>
                    <q-td class="text-right" v-if="!model.rate_includes_gst">
                      {{currencySymbol}}{{props.row.taxable}}
                    </q-td>
                    <q-td class="text-right" v-if="!model.rate_includes_gst">
                      {{currencySymbol}}{{props.row.tax_amount}}
                    </q-td>

                    <q-td class="text-right">
                      {{currencySymbol}}{{props.row.total}}
                    </q-td>
                    <q-td class="text-right">
                      <q-btn round icon="delete" flat color="black" @click="deleteFn(props.rowIndex)"/>
                    </q-td>
                  </q-tr>
                </template>
                <template v-slot:top-right>
                    <q-input borderless dense debounce="300" v-model="search" placeholder="Search">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                    </q-input>
                </template>
              </q-table>
            </div>
          </div>
          <div class="row q-col-gutter-xs q-mt-xs">
            <div class="col-4">
              <q-select
                filled square dense
                label="Product"
                ref="product"
                :rules="[v=>!!v||'Required']"
                lazy-rules="ondemand"
                :options="productOptions"
                @filter="productFilter"
                option-value="id"
                option-label="name"
                use-input
                fill-input
                hide-selected
                v-model="product"
                :loading="prodLoading"
                @input-value="$refs.product.resetValidation()"
                @input="$refs.gst.resetValidation()"
                tabindex="8"
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
            <div class="col">
              <q-select
                filled square dense
                v-model="gst"
                ref="gst"
                :rules="[v=>!!v||'Required']"
                lazy-rules="ondemand"
                :options="gstOptions"
                label="GST"
                map-options
                emit-value
                tabindex="-1"/>
            </div>
            <div class="col">
              <q-input
                filled square dense
                v-model="qty" label="Qty"
                class="input-right" @focus="qty && $event.target.select()"
                ref="qty"
                :rules="[qtyValidation]" @blur="()=>{qty = qty ? parseInt(qty).toString():null}"
                lazy-rules="ondemand"
                :hint="product ? ((stock && stock > 0)? stock+' Available':'Out of stock'):''"
                @input="$refs.qty.resetValidation()"
                tabindex="9"/>
            </div>
            <div class="col">
              <q-input filled
                square dense v-model="rate"
                :prefix="currencySymbol"
                label="Rate" @focus="rate && $event.target.select()"
                @blur="()=>{rate = rate ? parseFloat(rate).toFixed(2):null}"
                ref="rate"  :rules="[rateValidation]"
                lazy-rules="ondemand"
                class="input-right"
                tabindex="10"/>
            </div>
            <div class="col" v-if="!model.rate_includes_gst">
              <q-input filled square dense v-model="row_taxable" :prefix="currencySymbol" readonly label="Taxable" tabindex="-1"/>
            </div>
            <div class="col" v-if="!model.rate_includes_gst">
              <q-input filled square dense v-model="row_tax_amount" :prefix="currencySymbol" readonly label="Tax Amount" tabindex="-1"/>
            </div>
            <div class="col">
              <q-input filled square dense v-model="row_total" :prefix="currencySymbol" readonly label="Total" tabindex="-1"/>
            </div>
            <div class="">
              <q-btn icon="add" round flat color="teal" @click="addRowValidation()" tabindex="11"/>
            </div>
          </div>
          <div class="row q-col-gutter-md">
            <div class="col-12 col-md-4">
              <q-input filled dense v-model="maskName" label="Mask Name" v-if="useMask"/>
            </div>
          </div>
          <div class="row q-mt-xs">
            <div class="col-12 col-md-10">
              <q-btn color="primary" label="add bundle" @click="bundleDialog = true"/>
            </div>
            <div class="col-12 col-md-2">
              <div class="column">
                <div class="col">
                  <q-input label="Subtotal(Incl GST)" class="input-right"
                  :prefix="currencySymbol"
                  dense filled square readonly v-model="model.subtotal"
                  ref="subtotal" :rules="[v=>v > 0||'Invalid',v=>!isNaN(v)||'Invalid']"
                  tabindex="-1"/>
                </div>
                <div class="col">
                  <q-input label="Discount" class="input-right"
                  :prefix="currencySymbol"
                  dense filled square v-model="model.discount"
                  ref="discount" :rules="[v=>!isNaN(v)||'Invalid']"
                  tabindex="12"/>
                </div>
                <div class="col">
                  <q-input label="Freight" class="input-right"
                  :prefix="currencySymbol"
                  dense filled square v-model="model.freight"
                  ref="freight" :rules="[v=>!isNaN(v)||'Invalid']"
                  tabindex="13">
                  </q-input>
                </div>
                <div class="col" v-if="model.cod">
                  <q-input label="COD Charge"
                  :prefix="currencySymbol"
                  class="input-right"
                  dense filled square v-model="model.cod_charge"
                  ref="freight" :rules="[v=>!isNaN(v)||'Invalid']"
                  tabindex="13"/>
                </div>
                <div class="col">
                  <q-input label="Round" class="input-right"
                  :prefix="currencySymbol"
                  dense filled square v-model="model.round"
                  ref="round" :rules="[v=>!isNaN(v)||'Invalid']"
                  tabindex="14"/>
                </div>
                <div class="col">
                  <q-input label="Total" class="input-right"
                  :prefix="currencySymbol"
                  dense filled readonly square v-model="model.total"
                  ref="total" :rules="[v=>v > 0||'Invalid',v=>!isNaN(v)||'Invalid']"
                  tabindex="-1"/>
                </div>
              </div>
            </div>
          </div>
          <div class="text-subtitle1"> Additional Information</div>
          <div class="row q-mt-sm q-col-gutter-md">
            <div class="col-12 col-md-3">
              <q-input v-model="model.po_number" label="PO Number" filled square dense tabindex="15"/>
            </div>
            <div class="col-12 col-md-3">
              <q-input filled v-model="model.order_date"
              dense
              mask="####-##-##" label="Order Date"
              :rules="[dateValidation]"
              hint="Format yyyy-mm-dd. Click on icon to select date"
              tabindex="16">
                <template v-slot:append>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                      <q-date v-model="model.order_date">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>
          </div>
          <div class="row q-col-gutter-md">
            <div class="col-12 col-md-6">
              <div class="text-h6 q-mt-md">Remarks</div>
              <q-editor v-model="model.remarks" min-height="5rem" tabindex="17"/>
            </div>
            <div class="col-12 col-md-6">
              <div class="text-h6 q-mt-md">Dentodeal Data</div>
              <div v-html="model.dentodeal_data"></div>
            </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
    <page-toolbar :page-title="''"
      :buttons="toolbarButtons"
      v-on:save="saveFn()"
      v-on:sendtoaccounts="sendtoaccounts()"
      v-on:sendforconfirmation="sendforconfirmation()"
      class="q-mt-md"/>
    <q-dialog v-model="shippingDialog" full-width persistent>
      <q-card>
        <q-bar dark>
          <q-space />
          <q-btn dense flat icon="close" @click="closeShippigDialog">
            <q-tooltip>Close</q-tooltip>
          </q-btn>
        </q-bar>
        <q-card-section>
          <div class="row">
            <div class="col">
              <q-checkbox v-model="new_shipping_address.set_as_default" label="Set as default?" color="blue-10" class="q-mx-md"/>
            </div>
          </div>
          <div class="row q-col-gutter-md">
          <div class="col-xs-12 col-md-6">
            <div class="row q-mt-md">
              <div class="col">
                <q-input square filled
                  v-model="new_shipping_address.line_1"
                  label="Line 1"
                  ref="new_shipping_address_line_1"
                  :rules="[val => !!val || 'Required']"
                  lazy-rules="ondemand"
                  @blur="new_shipping_address.line_1 = capitaliseFn(new_shipping_address.line_1)"/>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6">
            <div class="row q-mt-md">
              <div class="col">
                <q-input square filled v-model="new_shipping_address.line_2" label="Line 2"
                  @blur="new_shipping_address.line_2 = capitaliseFn(new_shipping_address.line_2)" />
              </div>
            </div>
          </div>
        </div>
        <div class="row q-col-gutter-md">
          <div class="col-xs-12 col-md-3">
            <div class="row q-mt-xs">
              <div class="col">
                <q-input square filled
                  v-model="new_shipping_address.pin" label="Pin / Zip Code" ref="new_shipping_address_pin"
                  :rules="[shippingPinRule]" lazy-rules="ondemand"/>
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
                    square
                    filled
                    @filter="countryfilterFn"
                    :options="country_options"
                    v-model="new_shipping_address.country"
                    ref="new_shipping_address_country" :rules="[val => !!val || 'Required']"
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
                    use-input
                    fill-input
                    hide-selected
                    square
                    filled
                    :options="state_options"
                    @filter="statefilterFn"
                    :loading="new_shipping_address_state_loading"
                    ref="new_shipping_address_state" :rules="[val => !!val || 'Required']"
                    lazy-rules="ondemand"
                    option-label="name"
                    option-value="id"
                    v-model="new_shipping_address.state"
                    @input="loadShippingDistrict"
                    label="State"/>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-md-3">
              <div class="row q-mt-xs">
                <div class="col">
                  <q-input
                    v-if="new_shipping_address.country != 'India'"
                    square
                    filled
                    ref="new_shipping_address_district"
                    v-model="new_shipping_address.district"
                    label="District"
                    @blur="new_shipping_address.district = capitaliseFn(new_shipping_address.district)"
                  />
                  <q-select
                    v-else
                    use-input
                    fill-input
                    hide-selected
                    square
                    filled
                    ref="new_shipping_address_district" :rules="[shippingDistrictRule]"
                    lazy-rules="ondemand"
                    @filter="districtfilterFn"
                    :options="district_options"
                    option-label="name"
                    option-value="id"
                    :loading="new_shipping_address_district_loading"
                    v-model="new_shipping_address.district"
                    label="District"
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="row q-col-gutter-md " v-for="(item,index) in new_shipping_address.phones" :key="index">
            <div class="col-xs-12 col-md-6">
              <div class="row">
                <div class="col-3">
                  <q-select
                    v-model="new_shipping_address.phones[index].country_code"
                    :options="phonecodes"
                    emit-value
                    filled square
                    label="Code"
                    />
                </div>
                <q-input
                  class="col-7"
                  :ref="'shipping_phone_'+index"
                  v-model="new_shipping_address.phones[index].content"
                  :label="'Phone '+ (index+1)"
                  filled
                  square
                  :error-message="new_shipping_address_phones_errors[index].message"
                  :error="new_shipping_address_phones_errors[index].message.length > 0"
                  @input="validateShippingPhones"
                  />
                <div class="col-2">
                  <q-btn icon="delete" class="q-mt-sm" round flat v-if="new_shipping_address.phones.length > 1"
                    @click="new_shipping_address.phones.splice(index,1);new_shipping_address_phones_errors.splice(index,1)"/>
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
    <q-dialog v-model="addressSelectionDialog"
      persistent
      maximized
      transition-show="slide-up"
      transition-hide="slide-down">
      <q-card class="bg-primary text-white">
        <q-bar>
        <q-space />
        <q-btn dense flat icon="close" v-close-popup>
          <q-tooltip content-class="bg-white text-primary">Close</q-tooltip>
        </q-btn>
        </q-bar>
        <q-card-section>
          <div class="text-h6">Select shipping address</div>
        </q-card-section>
        <q-card-section class="q-pt-none">
          <div class="row q-col-gutter-md">
            <template v-for="(addr,i) in addresses">
              <div class="col-md-4 col-12" v-if="addr.tag_name == 'shipping'" :key="i">
                <q-card dark class="bg-white text-black">
                  <q-card-section>
                    {{addr.content}}
                  </q-card-section>
                  <q-card-actions>
                    <q-btn label="Select" color="orange-10" @click="model.shipping_address = addr; addressSelectionDialog=false"/>
                  </q-card-actions>
                </q-card>
              </div>
            </template>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="bundleDialog"
      persistent maximized
      transition-show="slide-up"
      transition-hide="slide-down">
      <q-card class="bg-blue text-white">
        <q-bar>
          <q-space />
          <q-btn dense flat icon="close" @click="closeBundleDialog">
            <q-tooltip content-class="bg-white text-blue">Close</q-tooltip>
          </q-btn>
        </q-bar>
        <q-card-section>
          <div class="text-h6">Select Product Bundle</div>
        </q-card-section>
        <q-card-section class="q-pt-none">
          <q-card>
            <q-card-section>
              <div class="row">
                <div class="col">
                  <q-select
                    filled square dense
                    label="Search Bundle"
                    ref="search_bundle"
                    :options="bundleOptions"
                    @filter="bundleFilter"
                    option-value="id"
                    option-label="name"
                    use-input
                    fill-input
                    hide-selected
                    v-model="bundle"
                    :loading="bundleLoading"
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
              </div>
              <div class="text-h6 text-grey-8 q-my-sm">Bundle Items</div>
              <div class="row">
                <div class="col">
                  <q-markup-table>
                    <thead>
                      <tr>
                        <th class="text-right">#</th>
                        <th class="text-left">Product</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">{{this.model.rate_includes_gst ? 'Rate(Incl GST)': 'Rate(Excl GST)'}}</th>
                        <th class="text-right"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <template v-if="bundleItems.length > 0">
                        <tr v-for="(item,i) in bundleItems" :key="i">
                          <td class="text-right">{{i+1}}</td>
                          <td class="text-left">{{item.product.name}}</td>
                          <td>
                            <q-input
                              filled square dense
                              v-model.number="bundleItems[i].qty" class="input-right"
                              ref="bundle_qty"
                              :rules="[v => !!v || 'Required', v => Number.isInteger(v) || 'Invalid']"
                              @focus="$refs.bundle_qty[i].select()"
                              />
                          </td>
                          <td>
                            <q-input
                              filled square dense
                              v-model="bundleItems[i].rate" class="input-right"
                              ref="bundle_rate"
                              :rules="[v => !!v || 'Required', v => !isNaN(v) || 'Invalid']"
                              @focus="$refs.bundle_rate[i].select()"
                              />
                          </td>
                          <td class="text-right"><q-btn round flat icon="delete" size="sm" @click="bundleItems.splice(i,1)" tabindex="-1"/></td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr>
                          <td colspan="5" class="text-center text-subtitle1">
                            No items
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </q-markup-table>
                </div>
              </div>
            </q-card-section>
            <q-card-actions>
              <q-space/>
              <q-btn color="secondary" flat label="cancel" @click="closeBundleDialog" tabindex="-1"/>
              <q-btn color="primary" label="Add Items" @click="addBundleItems" />
            </q-card-actions>
          </q-card>
        </q-card-section>
      </q-card>
    </q-dialog>
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
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'SaleOrdersCreate',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      useMask: false,
      maskName: null,
      maskDialog: false,
      maskEditIndex: null,
      model: {
        currency: 'INR',
        rate_includes_gst: true,
        serial_no: null,
        customer: {
          name: null,
          id: null,
          non_billable_account: false,
          status: null
        },
        warehouse: {
          name: null,
          id: null
        },
        pricelist: {
          name: null,
          id: null
        },
        items: [],
        source: null,
        subtotal: 0,
        total: 0,
        round: 0,
        discount: 0,
        freight: 0,
        flood_cess_included: false,
        representative: {
          name: null,
          id: null
        },
        remarks: '',
        dentodeal_data: '',
        status: 'Draft',
        shipping_address: {
          content: null
        },
        billing_address: null,
        created_by: {
          name: null,
          id: null
        },
        otc: false,
        cod: false,
        cod_charge: 0,
        order_no: null,
        po_number: null,
        order_date: null
      },
      bundleDialog: false,
      customerOptions: [],
      warehouses: [],
      pricelists: [],
      sources: ['Apexion', 'Dentodeal'],
      representativeOptions: [],
      addresses: [],
      search: null,
      table_loading: false,
      product: null,
      prodLoading: false,
      productOptions: [],
      bundle: null,
      bundleLoading: false,
      bundleOptions: [],
      bundleItems: [],
      gst: null,
      qty: null,
      rate: null,
      expiry_data: null,
      stock: null,
      expiryOptions: [],
      cost: null,
      min_margin: 0,
      row_taxable: null,
      row_tax_amount: null,
      row_total: null,
      expirable: false,
      new_shipping_address: {
        set_as_default: false,
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
      new_shipping_address_district_loading: false,
      new_shipping_address_state_loading: false,
      new_shipping_address_phones_errors: [{
        message: ''
      }],
      shippingDialog: false,
      country_options: [],
      state_options: [],
      district_options: [],
      countries: [],
      phonecodes: [],
      gstOptions: [],
      createdByOptions: [],
      addressSelectionDialog: false,
      saveBtnLoading: false
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('create_sale_order')) {
      // Check whether the user has permission to vie this page. Otherwise show 403 error page
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      const userPermission = ['create_sale_order', 'update_sale_order']
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
        this.$axios.get('representatives').then((res) => {
          this.representativeOptions = res.data
        }),
        this.$axios.get('countries').then((res) => {
          this.countries = res.data
        }),
        this.$axios.get('phonecodes').then((res) => {
          this.phonecodes = res.data
        }),
        this.$axios.get('gst_options').then((res) => {
          this.gstOptions = res.data
        }),
        this.$axios.get('users/has_permission?permissions=' + encodeURIComponent(JSON.stringify(userPermission))).then((res) => {
          this.createdByOptions = res.data
        })
      ]).finally(() => {
        this.$q.loading.hide()
        if (this.model.status !== 'Draft') {
          this.$router.push({ name: 'Error403' })
        }
      })
      if (this.$route.params.id) { // If there is id in route params, then load data for updation
        this.$q.loading.show()
        this.$axios.get('sale_orders/' + this.$route.params.id).then((res) => {
          this.model = res.data
          if (!res.data.remarks) this.model.remarks = '' // This is to avoid browser console errors q-editor component does not accept null
          if (!res.data.dentodeal_data) this.model.dentodeal_data = '' // This is to avoid browser console errors q-editor component does not accept null
        }).finally(() => {
          this.$q.loading.hide()
          if (this.model.status !== 'Draft') {
            this.$router.push({ name: 'Error403' })
          }
        })
      } else if (this.$route.query.duplicate) {
        this.$q.loading.show()
        this.$axios.get('sale_orders/' + this.$route.query.duplicate).then((res) => {
          Object.keys(res.data).forEach((key) => {
            // Get all data except status. We need status to be 'Draft'. Otherwise the 403 error may arise from above Promise call
            if (key !== 'status') {
              this.model[key] = res.data[key]
            }
          })
        }).finally(() => {
          this.$q.loading.hide()
          this.$q.notify({
            message: 'Note!!. This sale order is not saved yet.',
            color: 'blue-10'
          })
        })
      }
      if (this.$route.params.customer_id) {
        // customer_id will be present in route params if clicked on create sale order button from customer vie page. If present load that customer
        this.$axios.get('customers/' + this.$route.params.customer_id).then((res) => {
          if (res.data.status === 'Active' || res.data.status === 'Approved') {
            this.model.customer = {
              name: res.data.name,
              id: res.data.id
            }
            if (res.data.name !== 'OTC') { this.onInputCustomer() }
          } else {
            this.$q.notify({
              message: 'Cannot create sale order for given customer. Contact CRO',
              type: 'info'
            })
            this.$router.push('/')
          }
        })
      }
    }
  },
  methods: {
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
    addBundleItems () {
      let qtyFlag = true
      let rateFlag = true
      this.$refs.bundle_qty.forEach((it) => {
        qtyFlag = it.validate()
      })
      this.$refs.bundle_rate.forEach((it) => {
        rateFlag = it.validate()
      })
      if (qtyFlag && rateFlag) {
        this.$q.loading.show()
        this.bundleItems.forEach((item) => {
          const qty = parseInt(item.qty)
          const rate = parseFloat(item.rate)
          const gst = parseFloat(item.product.gst)
          let taxable = 0
          let taxAmount = 0
          let total = 0
          if (!this.model.rate_includes_gst) {
            taxable = (rate * qty)
            /*
            if (this.model.flood_cess_included && gst !== 5) {
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
          if (this.$_.findIndex(this.model.items, (v) => v.product.id === item.product.id) !== -1) {
            this.$q.dialog({
              title: 'Duplicate Entry  !!',
              message: 'Duplicate entry detected for: ' + item.product.name + '. Proceed?',
              ok: {
                label: 'Yes. Add'
              },
              cancel: {
                label: 'No. Don\'t Add'
              }
            }).onOk(() => {
              this.model.items.push({
                product: item.product,
                expirable: false,
                gst: item.product.gst,
                qty: qty,
                rate: rate,
                expiry_date: null,
                taxable: taxable.toFixed(2),
                tax_amount: taxAmount.toFixed(2),
                total: total.toFixed(2),
                stock: 0,
                cost: 0,
                min_margin: 0
              })
              this.addtoSubtotal(total)
            })
          } else {
            this.model.items.push({
              product: item.product,
              expirable: false,
              gst: item.product.gst,
              qty: qty,
              rate: rate,
              expiry_date: null,
              taxable: taxable.toFixed(2),
              tax_amount: taxAmount.toFixed(2),
              total: total.toFixed(2),
              stock: 0,
              cost: 0,
              min_margin: 0
            })
            this.addtoSubtotal(total)
          }
        })
        this.$q.loading.hide()
        this.closeBundleDialog()
      } else {
        this.$q.notify({
          message: 'Invalid data given.',
          type: 'negative'
        })
      }
    },
    addRowValidation () {
      if (
        this.$refs.product.validate() &
        this.$refs.gst.validate() &
        this.$refs.qty.validate() &
        this.$refs.rate.validate()
      ) {
        if (typeof (this.product) === 'object' && this.$_.findIndex(this.model.items, (v) => v.product.id === this.product.id) !== -1) {
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
                message: 'The rate you entered is less than cost[' + this.cost + ']',
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
      }
    },
    addRow () {
      if (this.expirable && this.expiryOptions.length > 0) {
        let qty = parseInt(this.qty)
        // expiry options are sorted by expiry date
        const groupedItems = this.$_.groupBy(this.$_.filter(this.model.items, (v) => v.id == null), 'product.id') // group items that are newly added ONLY
        if (groupedItems[this.product.id] && groupedItems[this.product.id].length > 0) {
          groupedItems[this.product.id].forEach((it) => {
            this.expiryOptions.forEach((obj, ind) => {
              if (it.qty < obj.qty) {
                this.expiryOptions[ind].qty = parseInt(obj.qty) - parseInt(it.qty)
              } else {
                this.expiryOptions.splice(ind, 1)
              }
            })
          })
        }
        this.expiryOptions.forEach((obj) => {
          if (qty > parseInt(obj.qty)) {
            qty = qty - parseInt(obj.qty)
            this.addItem(parseInt(obj.qty), obj.expiry_date)
          } else if (qty > 0) {
            this.addItem(parseInt(qty), obj.expiry_date)
            qty = 0
          }
        })
        if (qty > 0) {
          this.addItem(qty)
        }
      } else {
        this.addItem(this.qty)
      }
      this.product = null
      this.gst = null
      this.qty = null
      this.rate = null
      this.row_discount = null
      this.row_taxable = null
      this.row_tax_amount = null
      this.row_total = null
      this.stock = null
      this.expiryOptions = []
      this.expiry_data = null
      this.cost = null
      this.min_margin = 0
      this.$nextTick(() => {
        this.$refs.product.focus()
      })
    },
    addItem (qty, expiry_date = null) {
      qty = parseInt(qty)
      const rate = this.rate ? parseFloat(this.rate) : 0
      const gst = this.gst ? parseFloat(this.gst) : 0
      let taxable = 0
      let taxAmount = 0
      let total = 0
      if (!this.model.rate_includes_gst) {
        taxable = (rate * qty)
        /*
        if (this.model.flood_cess_included && gst !== 5) {
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
        product: this.product,
        expirable: this.expirable,
        gst: this.gst,
        qty: qty,
        rate: this.rate,
        expiry_date: expiry_date,
        taxable: taxable.toFixed(2),
        tax_amount: taxAmount.toFixed(2),
        total: total.toFixed(2),
        stock: this.stock,
        cost: this.cost,
        min_margin: this.min_margin
      })
      this.addtoSubtotal(total)
    },
    addtoSubtotal (val) {
      this.model.subtotal = (parseFloat(this.model.subtotal) + parseFloat(val)).toFixed(2)
    },
    deleteFn (index) {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Would you like to delete this record?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        const total = parseFloat(this.model.items[index].total)
        this.model.subtotal = parseFloat(this.model.subtotal) - total
        if (this.model.items[index].id && this.$route.params.id) {
          this.$q.loading.show()
          this.$axios.get('sale_orders/delete_item/' + this.model.items[index].id).then((res) => {
            if (res.data.message === 'success') {
              this.model.items.splice(index, 1)
            }
          }).finally(() => {
            this.$q.loading.hide()
          })
        } else this.model.items.splice(index, 1)
      }).onCancel(() => {

      })
    },
    updateRow (index) {
      const oldTotal = parseFloat(this.model.items[index].total)
      const qty = parseInt(this.model.items[index].qty)
      const rate = parseFloat(this.model.items[index].rate)
      const gst = parseFloat(this.model.items[index].gst)
      let total = 0
      if (!this.model.rate_includes_gst) {
        let taxable = 0
        taxable = (rate * qty)
        this.model.items[index].taxable = taxable.toFixed(2)
        let taxAmount = 0
        /*
        if (this.model.flood_cess_included && gst !== 5) {
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
        total = (rate * qty)
        this.model.items[index].total = total.toFixed(2)
      }
      const diff = total - oldTotal
      this.addtoSubtotal(diff)
    },
    updateTotal () {
      const subtotal = parseFloat(this.model.subtotal)
      const discount = parseFloat(this.model.discount)
      const freight = parseFloat(this.model.freight)
      const codCharge = parseFloat(this.model.cod_charge)
      const round = parseFloat(this.model.round)
      const total = (subtotal - discount + freight + codCharge + round)
      this.model.total = total.toFixed(2)
    },
    customerFilterFn (val, update, abort) {
      if (val) {
        this.$axios.get('customers_search?billable=1&search=' + encodeURIComponent(val)).then((res) => {
          update(() => {
            this.customerOptions = res.data
          })
        })
      }
    },
    qtyValidation () {
      if (this.qty === null || this.qty === '') {
        return 'Required'
      } else if (isNaN(this.qty)) {
        return 'Invalid'
      } else if (parseInt(this.qty) <= 0) {
        return 'Qty must be greater than 0'
      } else {
        return true
      }
    },
    rateValidation () {
      if (this.rate === null || this.rate === '') {
        return 'Required'
      }
      if (isNaN(this.rate)) {
        return 'Invalid'
      }
      return true
    },
    qtyEditValidation (val) {
      if (!val) return 'Required'
      else if (isNaN(val)) return 'invalid'
      else if (!Number.isInteger(Number(val))) return 'Must be Integer'
      return true
    },
    rateEditValidation (val) {
      if (!val) return 'Required'
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
        taxable = (rate * qty)
        this.row_taxable = taxable.toFixed(2)
        let taxAmount = 0
        /*
        if (this.model.flood_cess_included && gst !== 5) {
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
        this.$axios.get('products/search?keyword=' + encodeURIComponent(val)).then((res) => {
          update(() => {
            this.productOptions = res.data
          })
        }).finally(() => {
          this.prodLoading = false
        })
      }
    },
    bundleFilter (val, update, abort) {
      update(() => {
        if (val) {
          this.bundleLoading = true
          this.$axios.get('product_bundles/search?keyword=' + encodeURIComponent(val)).then((res) => {
            this.bundleOptions = res.data
          }).finally(() => {
            this.bundleLoading = false
          })
        }
      })
    },
    openShippingDialog () {
      this.new_shipping_address.country = 'India'
      this.loadShippingState()
      this.shippingDialog = true
    },
    closeShippigDialog () {
      this.resetShippingPhonesErrors()
      this.resetShippingAddress()
      this.shippingDialog = false
    },
    resetShippingAddress () {
      this.new_shipping_address.set_as_default = false
      this.new_shipping_address.line_1 = null
      this.new_shipping_address.line_2 = null
      this.new_shipping_address.pin = null
      this.new_shipping_address.district = ''
      this.new_shipping_address.district_loading = false
      this.new_shipping_address.state = null
      this.new_shipping_address.state_loading = false
      this.new_shipping_address.country = ''
      this.new_shipping_address.phones = [{
        id: 1,
        content: '',
        country_code: '+91'
      }]
      this.shippingDialog = false
    },
    resetShippingPhonesErrors () {
      this.new_shipping_address_phones_errors.forEach((item, index) => {
        this.$set(this.new_shipping_address_phones_errors[index], 'message', '')
      })
    },
    shippingPinRule (val) {
      if (this.new_shipping_address.country === 'India') {
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
    shippingDistrictRule (val) {
      if (this.new_shipping_address.country === 'India') {
        return (val && val.length > 0) || 'Required'
      }
      return true
    },
    loadShippingState (setNull = 1) {
      if (setNull) {
        this.new_shipping_address.state = null
        this.new_shipping_address.district = null
      }
      this.new_shipping_address_state_loading = true
      this.getStates(this.new_shipping_address.country).finally(() => {
        this.new_shipping_address_state_loading = false
      })
    },
    getStates (country) {
      return this.$axios.get('states/' + encodeURIComponent(country)).then((res) => {
        this.states = res.data
      })
    },
    loadShippingDistrict (setNull = 1) {
      if (setNull) {
        this.new_shipping_address.district = null
      }
      this.new_shipping_address_district_loading = true
      this.getDistricts(this.new_shipping_address.state).finally(() => {
        this.new_shipping_address_district_loading = false
      })
    },
    getDistricts (state) {
      return this.$axios.get('districts/' + encodeURIComponent(state)).then((res) => {
        this.districts = res.data
      })
    },
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
    validateShippingPhones () {
      this.resetShippingPhonesErrors()
      let result = false
      this.new_shipping_address.phones.forEach((phone, index) => {
        if (this.new_shipping_address.country === 'India') {
          if (!phone.content) {
            this.$set(this.new_shipping_address_phones_errors[index], 'message', 'Required')
            result = false
          } else if (isNaN(phone.content)) {
            this.$set(this.new_shipping_address_phones_errors[index], 'message', 'Phone must be numeric.')
            result = false
          } else if (phone.content.length !== 10) {
            this.$set(this.new_shipping_address_phones_errors[index], 'message', 'Phone must be 10 digits.')
            result = false
          } else {
            result = true
          }
        } else {
          if (!phone.content) {
            this.$set(this.new_shipping_address_phones_errors[index], 'message', 'Required')
            result = false
          } else if (isNaN(phone.content)) {
            this.$set(this.new_shipping_address_phones_errors[index], 'message', 'Phone must be numeric.')
            result = false
          } else {
            result = true
          }
        }
      })
      return result
    },
    addShippingPhone () {
      this.new_shipping_address.phones.push({
        id: this.new_shipping_address.phones.length + 1,
        content: '',
        country_code: '+91'
      })
      this.new_shipping_address_phones_errors.push({
        message: ''
      })
      this.$nextTick(() => {
        const ind = this.new_shipping_address.phones.length - 1
        const key = 'shipping_phone_' + ind
        this.$refs[key][0].focus()
      })
    },
    addShipping () {
      if (
        this.$refs.new_shipping_address_line_1.validate() &
                this.$refs.new_shipping_address_pin.validate() &
                this.$refs.new_shipping_address_country.validate() &
                this.$refs.new_shipping_address_state.validate() &
                this.$refs.new_shipping_address_district.validate() &
                this.validateShippingPhones()
      ) {
        const obj = this.$_.cloneDeep(this.new_shipping_address)
        this.$axios.post('customers/add_shipping/' + this.customer.id, obj).then((res) => {
          if (res.data.message === 'success') {
            obj.id = res.data.id
            const str = this.addressFormat(obj)
            const obj2 = {
              id: res.data.id,
              tag_name: 'shipping',
              content: str
            }
            this.model.shipping_address = obj2
            this.addresses.push(obj2)
            this.closeShippigDialog()
          }
        })
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
    capitaliseFn (str) {
      if (str) {
        const splitStr = str.split(' ')
        for (let i = 0; i < splitStr.length; i++) {
          splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1)
        }
        return splitStr.join(' ')
      }
      return ''
    },
    sendtoaccounts () {
      this.saveFn('Invoice Pending')
    },
    sendforconfirmation () {
      this.saveFn('Pending Confirmation')
    },
    saveFn (status = 'Draft') {
      this.saveBtnLoading = true
      if (
        this.$refs.customer.validate() &
        this.$refs.representative.validate() &
        this.$refs.pricelist.validate() &
        this.$refs.warehouse.validate() &
        this.$refs.created_by_id.validate() &
        this.$refs.source.validate() &
        this.$refs.order_no.validate() &
        this.validateItemsCount()) {
        let subtotal = 0
        this.model.items.forEach((item) => {
          subtotal += parseFloat(item.total)
        })
        this.model.subtotal = subtotal
        const obj = this.model
        obj.status = status
        obj._method = this.$route.params.id ? 'PUT' : 'POST'
        let route = 'sale_orders'
        if (this.$route.params.id) {
          route = 'sale_orders/' + this.$route.params.id
        }
        this.$q.loading.show()
        this.$axios.post(route, obj).then((res) => {
          if (res.data.message === 'success') {
            this.$q.loading.hide()
            this.$q.notify({
              type: 'positive',
              message: 'Saved Succesfully.'
            })
            this.$router.replace('/sale_orders/view/' + res.data.id)
          }
        }).catch((error) => {
          this.$q.loading.hide()
          if (error.response.status === 422) {
            this.$q.notify({
              type: 'negative',
              message: error.response.data.message
            })
            let str = '<div class="q-markup-table q-table__container q-table__card q-table--horizontal-separator q-table--no-wrap"><table class="q-table">'
            str += '<thead><tr>'
            str += '<th class="text-left">Line</th>'
            str += '<th class="text-left">Product</th>'
            str += '<th class="text-right">Exp</th>'
            str += '<th class="text-right">Given</th>'
            str += '<th class="text-right">Available</th>'
            str += '</tr></thead><tbody>'
            Object.keys(error.response.data.errors).forEach((key) => {
              if (key.indexOf('items') !== -1) {
                const errArr = error.response.data.errors[key][0].split('?')
                str += '<tr>'
                str += '<td class="text-left">' + (parseInt(key.substr(key.indexOf('.') + 1)) + 1) + '</td>'
                str += '<td class="text-left">' + errArr[0] + '</td>'
                str += '<td class="text-right">' + errArr[1] + '</td>'
                str += '<td class="text-right">' + errArr[2] + '</td>'
                str += '<td class="text-right">' + errArr[3] + '</td>'
                str += '</tr>'
                // str += '<p>' + 'Line ' + (parseInt(key.substr(key.indexOf('.') + 1)) + 1) + ':' + error.response.data.errors[key][0] + '</p><br>'
              }
            })
            str += '</tbody></table></div>'
            this.$q.dialog({
              style: { width: '50%', maxWidth: '100%' },
              title: 'Cannot process this Sale Order. Following errors occured',
              message: str,
              html: true
            })
          }
        }).finally(() => {
          this.saveBtnLoading = false
          this.$q.loading.hide()
        })
      } else {
        this.saveBtnLoading = false
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
    onInputCustomer (val) {
      console.log(val)
      if (val.status === 'Disabled') {
        this.model.customer = {
          name: null,
          id: null,
          non_billable_account: false,
          status: null
        }
        this.$q.dialog({
          title: 'Attention',
          message: 'The customer selected was disabled by CRO. Please contact CRO.'
        })
      } else if (val.status !== 'Active' && val.status !== 'Approved') {
        this.model.customer = {
          name: null,
          id: null,
          non_billable_account: false,
          status: null
        }
        this.$q.dialog({
          title: 'Attention',
          message: 'The customer selected is not active or approved by CRO. Please contact CRO.'
        })
      } else if (val.name === 'OTC') {
        this.model.otc = true
      } else {
        this.model.otc = false
        this.$q.loading.show()
        this.model.shipping_address = {
          content: null
        }
        this.model.billing_address = null
        this.$axios.get('customer_addresses/' + this.model.customer.id).then((res) => {
          if (res.data.representatives.length === 1) {
            this.model.representative = res.data.representatives[0]
          }
          this.addresses = res.data.addresses.map((addr) => {
            const str = this.addressFormat(addr)
            if (addr.tag_name === 'billing') {
              this.model.billing_address = str
              /*
              if (addr.state === 'Kerala') this.model.flood_cess_included = true
              else this.model.flood_cess_included = 0
              */
            }
            if (addr.tag_name === 'shipping' && res.data.default_shipping === addr.id) {
              this.model.shipping_address = {
                id: addr.id,
                tag_name: addr.tag_name,
                content: str
              }
            }
            return {
              id: addr.id,
              tag_name: addr.tag_name,
              content: str
            }
          })
        }).finally(() => {
          this.$q.loading.hide()
        })
      }
    },
    getExpiryOptions (productId) {
      this.$axios.get('products/basic/' + productId + '/' + this.model.warehouse.id).then((res) => {
        this.expiryOptions = res.data.expiry_options
        this.cost = res.data.cost
      })
    },
    dateValidation (v) {
      if (!v) {
        return 'Required'
      } else {
        if (!/^-?[\d]+-[0-1]\d-[0-3]\d$/.test(v)) {
          return 'Invalid Date'
        }
      }
      return true
    },
    closeBundleDialog () {
      this.bundleItems = []
      this.bundle = null
      this.bundleOptions = []
      this.bundleDialog = false
    }
  },
  computed: {
    currencySymbol () {
      if (this.model.currency === 'INR') {
        return '₹'
      }
      return '$'
    },
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('send_to_accounts_sale_order') && !this.model.cod && !this.model.customer.non_billable_account) {
        arr.push({
          label: 'Send to Accounts',
          id: 'sendtoaccounts',
          type: 'button',
          color: 'blue-10',
          icon: 'forward'
        })
      }
      if (this.source === 'Dentodeal' && !this.model.customer.non_billable_account) {
        arr.push({
          label: 'Send for Confirmation',
          id: 'sendforconfirmation',
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
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Sale Orders', link: '/sale_orders' },
        { label: this.$route.params.id ? this.model.serial_no : 'Create', link: '/sale_orders/view/' + this.$route.params.id }
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
          sortable: false
        },
        {
          name: 'name',
          field: 'name',
          label: 'Product',
          sortable: false,
          align: 'left'
        },
        {
          name: 'gst',
          field: 'gst',
          label: 'GST',
          sortable: false
        },
        {
          name: 'qty',
          field: 'qty',
          label: 'Qty',
          sortable: false
        },
        {
          name: 'rate',
          field: 'rate',
          label: 'Rate',
          sortable: false
        },
        {
          name: 'expiry_date',
          field: 'expiry_date',
          label: 'Expiry Date',
          sortable: false
        }
      ]
      if (!this.model.rate_includes_gst) {
        arr.push({
          name: 'taxable',
          field: 'taxable',
          label: 'Taxable',
          sortable: false
        },
        {
          name: 'tax_amount',
          field: 'tax_amount',
          label: 'Tax Amount',
          sortable: false
        })
      }
      arr.push({
        name: 'total',
        field: 'total',
        label: 'Total',
        sortable: false
      },
      {
        name: 'actions',
        field: 'actions',
        label: '',
        sortable: false
      })
      return arr
    },
    customer () { return this.model.customer },
    subtotal () { return this.model.subtotal },
    cod_charge () { return this.model.cod_charge },
    discount () { return this.model.discount },
    freight () { return this.model.freight },
    round () { return this.model.round },
    rate_includes_gst () { return this.model.rate_includes_gst },
    flood_cess_included () { return this.model.flood_cess_included }
  },
  watch: {
    product (newV, oldV) {
      if (newV && typeof (newV) === 'object') {
        if (this.model.warehouse.id) {
          this.$axios.get('products/basic/' + newV.id + '/' + this.model.warehouse.id).then((res) => {
            this.gst = res.data.gst
            this.expirable = res.data.expirable
            this.row_weight = res.data.weight
            this.hsn = res.data.hsn
            this.mrp = res.data.mrp
            const stockAdded = this.$_.sumBy(this.model.items, (v) => v.product.id === this.product.id && !v.id ? parseInt(v.qty) : 0)
            if (stockAdded > 0) this.stock = parseInt(res.data.available_stock) - stockAdded
            else this.stock = res.data.available_stock
            this.expiryOptions = res.data.expiry_options
            this.cost = res.data.cost ? res.data.cost : 0
            this.min_margin = res.data.min_margin ? res.data.min_margin : 0
          })
        }
      }
    },
    bundle (newV, oldV) {
      if (newV && typeof (newV) === 'object') {
        this.$axios.get('product_bundles/items/' + newV.id).then((res) => {
          this.bundleItems = res.data.items.map((item) => {
            return { ...item, rate: 0 }
          })
        })
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
    cod_charge () {
      this.updateTotal()
    },
    discount () {
      this.updateTotal()
    },
    freight () {
      this.updateTotal()
    },
    round () {
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
      if (!this.model.rate_includes_gst) {
        this.$q.loading.show()
        let subtotal = 0
        this.model.items.forEach((item, i) => {
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
          this.model.items[i].taxable = taxable.toFixed(2)
          this.model.items[i].tax_amount = taxAmount.toFixed(2)
          this.model.items[i].total = total.toFixed(2)
          subtotal += parseFloat(total)
        })
        this.model.subtotal = subtotal.toFixed(2)
        this.$q.loading.hide()
      }
    }
    */
  }
}
</script>
