<template>
  <q-page>
    <page-toolbar :buttons="toolbarButtons" v-on:save="saveFn()" />
    <breadcrumbs :breadcrumbs="breadcrumbs" />
    <div :class="$q.screen.gt.sm ? 'q-px-lg q-mt-md' : 'q-px-xs q-mt-sm'">
      <q-card>
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
        >
          <q-tab name="basic" label="Basic" />
          <q-tab name="dentodeal" label="Dentodeal" />
        </q-tabs>
        <q-separator />

        <q-tab-panels v-model="tab" animated keep-alive>
          <q-tab-panel name="basic">
            <div class="text-h6 q-mt-md">Basic Details</div>
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-6">
                <q-input
                  v-model="name"
                  ref="name"
                  dense
                  :readonly="status != 'Draft'"
                  filled
                  square
                  label="Name"
                  :error="errors.name"
                  :error-message="errorMessages.name"
                  :rules="[(v) => !!v || 'Required']"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-3">
                <q-input
                  v-model="status"
                  dense
                  filled
                  square
                  readonly
                  label="Status"
                />
              </div>
              <div class="col-12 col-md-3">
                <q-input
                  v-model="sku"
                  dense
                  ref="sku"
                  filled
                  square
                  label="SKU"
                  readonly
                  :error="errors.sku"
                  :error-message="errorMessages.sku"
                  :rules="[(v) => !!v || 'Required']"
                >
                  <template v-slot:append v-if="status == 'Draft'">
                    <q-btn
                      label="GENERATE"
                      color="primary"
                      @click="generateSku()"
                    />
                  </template>
                </q-input>
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  v-model="alias"
                  filled
                  square
                  dense
                  label="Alias"
                  hint="Enter aliases in comma separated"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  v-model="mask_name"
                  filled
                  square
                  dense
                  label="Mask Name"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="hsn"
                  class="input-right"
                  filled
                  square
                  dense
                  label="HSN"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="reorder_code"
                  filled
                  square
                  dense
                  label="Reorder Code / MPN"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="gtin"
                  filled
                  square
                  dense
                  label="GTIN"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-3">
                <q-select
                  :options="countries"
                  v-model="origin_country"
                  filled
                  square
                  dense
                  label="Origin Country"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-6 col-md-2">
                <q-checkbox
                  v-model="enabled"
                  :true-value="1"
                  :false-value="0"
                  label="Enabled?"
                />
              </div>
              <div class="col-6 col-md-2">
                <q-checkbox
                  v-model="expirable"
                  :true-value="1"
                  :false-value="0"
                  :disable="$route.params.id && $route.params.id !== null"
                  label="Expirable?"
                />
              </div>
            </div>
            <div class="text-h6 q-mt-md">Weight & Dimensions</div>
            <div class="row  q-col-gutter-md">
              <div class="col-12 col-md-2">
                <q-input
                  v-model="weight"
                  dense
                  ref="weight"
                  class="input-right"
                  filled
                  square
                  label="Weight"
                  hint="Enter weight in grams"
                  :error="errors.weight"
                  :error-message="errorMessages.weight"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                >
                <template v-slot:after>
                  <div class="text-body2">grams</div>
                </template>
                </q-input>
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="length"
                  dense
                  ref="length"
                  class="input-right"
                  filled
                  square
                  label="Length"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                >
                <template v-slot:after>
                  <div class="text-body2">cm</div>
                </template>
                </q-input>
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="breadth"
                  dense
                  ref="breadth"
                  class="input-right"
                  filled
                  square
                  label="Breadth"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                >
                <template v-slot:after>
                  <div class="text-body2">cm</div>
                </template>
                </q-input>
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="height"
                  dense
                  ref="height"
                  class="input-right"
                  filled
                  square
                  label="Height"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                >
                <template v-slot:after>
                  <div class="text-body2">cm</div>
                </template>
                </q-input>
              </div>
            </div>
            <div class="text-h6 q-mt-md">Price Details</div>
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-2">
                <q-input
                  v-model="mrp"
                  ref="mrp"
                  class="input-right"
                  filled
                  square
                  dense
                  label="MRP"
                  :error="errors.mrp"
                  :error-message="errorMessages.mrp"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="gsp_customer"
                  ref="gsp_customer"
                  class="input-right"
                  filled
                  square
                  dense
                  label="GSP Customer"
                  :error="errors.gsp_customer"
                  :error-message="errorMessages.gsp_customer"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="gsp_dealer"
                  ref="gsp_dealer"
                  class="input-right"
                  filled
                  square
                  dense
                  label="GSP Dealer"
                  :error="errors.gsp_dealer"
                  :error-message="errorMessages.gsp_dealer"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-select
                  v-model="gst"
                  ref="gst"
                  :options="gstOptions"
                  emit-value
                  dense
                  map-options
                  :rules="[(v) => !!v || 'Required']"
                  filled
                  square
                  label="GST"
                />
              </div>
            </div>
            <div class="text-h6 q-mt-md">Purchase Details</div>
            <div class="row q-col-gutter-md">
              <div class="col-xs-12 col-md-6">
                <q-select
                  label="Default Supplier"
                  v-model="default_supplier"
                  :options="suppliers"
                  filled dense square
                  @filter="supplierFilterFn"
                  use-input
                  fill-input
                  hide-selected
                  option-label="name"
                  option-value="id"
                  :loading="supplierLoading"/>
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="reorder_point"
                  filled
                  square
                  dense
                  label="Reorder Point"
                  class="input-right"
                  ref="reorder_point"
                  :rules="[(v) => !isNaN(v) || 'Invalid']"
                  @focus="$event.target.select()"
                />
              </div>
            </div>
            <div class="text-h6 q-mt-md">Categorization</div>
            <div class="row q-col-gutter-md">
              <div class="col-xs-12 col-md">
                <q-select
                  label="Type"
                  ref="type"
                  filled
                  :readonly="status != 'Draft'"
                  dense
                  square
                  v-model="type"
                  use-input
                  fill-input
                  hide-selected
                  option-label="name"
                  option-value="id"
                  :options="typeOptions"
                  @filter="typeFilterFn"
                  :rules="[(v) => !!v || 'Required']"
                  :error="errors.type"
                  :error-message="errorMessages.type"
                >
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        <q-btn
                          flat
                          no-caps
                          color="blue-10"
                          label="Create New"
                          @click="openNewTaxonomyDialog('type')"
                        />
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
              </div>
              <div class="col-xs-12 col-md">
                <q-select
                  label="Department"
                  ref="department"
                  filled
                  dense
                  :readonly="status != 'Draft'"
                  square
                  v-model="department"
                  use-input
                  fill-input
                  hide-selected
                  option-label="name"
                  option-value="id"
                  :options="departmentOptions"
                  @filter="departmentFilterFn"
                  :rules="[(v) => !!v || 'Required']"
                  :error="errors.department"
                  :error-message="errorMessages.department"
                >
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        <q-btn
                          flat
                          no-caps
                          color="blue-10"
                          label="Create New"
                          @click="openNewTaxonomyDialog('department')"
                        />
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
              </div>
              <div class="col-xs-12 col-md">
                <q-select
                  label="Category"
                  ref="category"
                  filled
                  square
                  :readonly="status != 'Draft'"
                  dense
                  v-model="category"
                  use-input
                  fill-input
                  hide-selected
                  option-label="name"
                  option-value="id"
                  :options="categoryOptions"
                  @filter="categoryFilterFn"
                  :rules="[(v) => !!v || 'Required']"
                  :error="errors.category"
                  :error-message="errorMessages.category"
                  :loading="categoryLoading"
                >
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        <q-btn
                          flat
                          no-caps
                          color="blue-10"
                          label="Create New"
                          @click="openNewTaxonomyDialog('category')"
                        />
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
              </div>
              <div class="col-xs-12 col-md">
                <q-select
                  label="Sub Category"
                  ref="sub_category"
                  filled
                  square
                  :readonly="status != 'Draft'"
                  dense
                  v-model="sub_category"
                  use-input
                  fill-input
                  hide-selected
                  option-label="name"
                  option-value="id"
                  :options="subCategoryOptions"
                  @filter="subCategoryFilterFn"
                  :rules="[(v) => !!v || 'Required']"
                  :error="errors.sub_category"
                  :error-message="errorMessages.sub_category"
                  :loading="subCategoryLoading"
                >
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        <q-btn
                          flat
                          no-caps
                          color="blue-10"
                          label="Create New"
                          @click="openNewTaxonomyDialog('sub_category')"
                        />
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
              </div>
              <div class="col-xs-12 col-md">
                <q-select
                  label="Brand"
                  ref="brand"
                  filled
                  square
                  dense
                  v-model="brand"
                  use-input
                  :readonly="status != 'Draft'"
                  fill-input
                  hide-selected
                  option-label="name"
                  option-value="id"
                  :options="brandOptions"
                  @filter="brandFilterFn"
                  :rules="[(v) => !!v || 'Required']"
                  :error="errors.brand"
                  :error-message="errorMessages.brand"
                  :loading="brandLoading"
                >
                  <template v-slot:no-option>
                    <q-item>
                      <q-item-section class="text-grey">
                        <q-btn
                          flat
                          no-caps
                          color="blue-10"
                          label="Create New"
                          @click="openNewTaxonomyDialog('brand')"
                        />
                      </q-item-section>
                    </q-item>
                  </template>
                </q-select>
              </div>
            </div>
            <div class="row q-mt-md q-col-gutter-md">

            </div>
            <div class="q-gutter-md row items-start q-mt-md">
              <q-img
                v-for="(img, ind) in images"
                :key="ind + 'img'"
                :src="img.thumbnail_url"
                style="width: 150px"
                ratio="1"
                spinner-color="white"
                class="rounded-borders"
                @click="openImgDialog(ind)"
              >
                <q-btn
                  round
                  flat
                  color="primary"
                  icon="delete"
                  @click="deleteImg(ind)"
                />
              </q-img>
              <q-btn
                label="Add Image"
                color="primary"
                @click="triggerImgUpload()"
              />
              <input
                type="file"
                ref="imgupload"
                @change="uploadImg()"
                accept="image/*"
                style="display: none"
              />
            </div>
            <div class="row q-mt-md">
              <div class="col">
                <div class="text-subtitle2">Description</div>
                <q-editor rows="3" v-model="description" />
              </div>
            </div>
            <div class="row q-mt-md">
              <div class="col">
                <div class="text-subtitle2">Remarks</div>
                <q-editor rows="3" v-model="remarks" />
              </div>
            </div>
          </q-tab-panel>
          <q-tab-panel name="dentodeal">
            <div class="row q-mt-md q-col-gutter-md">
              <div class="col-12 col-md-4">
                <q-input
                  v-model="dentodeal_sku"
                  dense
                  filled
                  square
                  label="Dentodeal SKU"
                />
              </div>
              <div class="col-12 col-md-4">
                <q-toggle
                  v-model="dentodeal_enabled"
                  filled
                  square
                  label="Dentodeal Enabled"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-toggle
                  v-model="dentodeal_bundled"
                  filled
                  square
                  label="Dentodeal Bundled"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="dentodeal_bundle_qty"
                  dense
                  filled
                  :disable="!dentodeal_bundled"
                  square
                  label="Dentodeal Bundle Qty"
                />
              </div>
            </div>
            <div class="row q-mt-md q-col-gutter-md">
              <div class="col-12 col-md-10">
                <q-input
                  v-model="dentodeal_frontend_url"
                  dense
                  filled
                  square
                  label="Dentodeal Frontend URL"
                />
              </div>
              <div class="col-12 col-md-2">
                <q-input
                  v-model="dentodeal_id"
                  dense
                  filled
                  square
                  label="Dentodeal Product ID"
                />
              </div>
            </div>
            <div class="row q-mt-md q-col-gutter-md">
              <div class="col-12">
                <q-toggle
                  v-model="sync_stock_dentodeal"
                  label="Sync stock with dentodeal"
                />
              </div>
            </div>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>

    <q-dialog persistent v-model="newTaxonomyDialog">
      <q-card style="width: 500px">
        <q-card-section>
          <div class="text-h6">New {{ newTaxonomyPageTitle }}</div>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col">
              <q-input
                v-model="newTaxonomyName"
                label="Name"
                filled
                square
                ref="new_taxonomy_name"
                :error="newTaxonomyNameError"
                :error-message="newTaxonomyNameErrorMessage"
              >
              </q-input>
            </div>
          </div>
          <div class="row q-mt-md">
            <div class="col">
              <q-input
                v-model="newTaxonomyCode"
                label="Code"
                filled
                square
                :error="newTaxonomyCodeError"
                :error-message="newTaxonomyCodeErrorMessage"
              >
              </q-input>
            </div>
          </div>
        </q-card-section>
        <q-card-actions>
          <q-btn label="Submit" color="teal" @click="submitNewTaxonomy()" />
          <q-btn
            label="Cancel"
            flat
            color="grey-7"
            @click="closeNewTaxonomyDialog"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog
      v-model="imgDialog"
      persistent
      maximized
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card class="">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Image Details</div>
          <q-space />
          <q-btn icon="close" flat round dense @click="closeImgDialog" />
        </q-card-section>
        <q-card-section>
          <div class="row q-col-gutter-md">
            <div class="col-xs-12 col-md-9">
              <q-img
                :src="images.length > 0 ? images[this.img_index].url : ''"
              />
            </div>
            <div class="col-xs-12 col-md-3">
              <q-input v-model="img_name" label="Name" filled square />
              <q-input
                type="textarea"
                class="q-mt-md"
                v-model="img_description"
                label="Description"
                filled
                square
              />
              <q-btn
                label="Save"
                color="primary"
                class="q-mt-md"
                @click="saveImgDetails()"
              />
            </div>
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ProductsCreate',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  mounted () {
    // console.log(this.$route.params.id)
    if (!this.$store.getters.hasPermissionTo('create_product')) {
      this.$router.push({ name: 'Error403' })
    } else {
      if (this.$route.params.id) {
        this.$q.loading.show()
        this.$axios
          .get('products/' + this.$route.params.id)
          .then((res) => {
            Object.keys(res.data).forEach((key) => {
              if (['description', 'remarks'].includes(key)) {
                this[key] = res.data[key] ? res.data[key] : ''
              } else {
                this[key] = res.data[key]
              }
            })
          })
          .finally(() => {
            this.$store.commit('setPageTitle', this.getpageTitle)
            this.$q.loading.hide()
          })
      } else {
        this.$store.commit('setPageTitle', this.getpageTitle)
      }
      Promise.all([
        this.$axios.get('product_types').then((res) => {
          this.types = res.data.model
        }),
        this.$axios.get('product_departments').then((res) => {
          this.departments = res.data.model
        }),
        this.$axios.get('gst_options').then((res) => {
          this.gstOptions = res.data
        }),
        this.$axios.get('countries').then((res) => {
          this.countries = res.data
        })
      ])
    }
  },
  watch: {},
  data () {
    return {
      toolbarButtons: [
        {
          label: 'Save',
          id: 'save',
          type: 'button',
          color: 'teal',
          icon: 'save'
        }
      ],
      errors: {
        name: false,
        sku: false,
        weight: false,
        mrp: false,
        gsp_customer: false,
        gsp_dealer: false,
        type: false,
        department: false,
        category: false,
        sub_category: false,
        brand: false
      },
      errorMessages: {
        name: '',
        sku: '',
        weight: '',
        mrp: '',
        gsp_dealer: '',
        gsp_customer: '',
        type: '',
        department: '',
        category: '',
        sub_category: '',
        brand: ''
      },
      images: [],
      img_index: 0,
      img_name: null,
      img_description: null,
      imgDialog: false,
      status: 'Draft',
      name: null,
      sku: null,
      reorder_code: null,
      alias: null,
      hsn: null,
      gst: null,
      gstOptions: [],
      enabled: 1,
      expirable: 0,
      mrp: 0,
      gsp_customer: 0,
      gsp_dealer: 0,
      weight: 0,
      description: '',
      remarks: '',
      type: null,
      types: [],
      typeOptions: [],
      department: null,
      departments: [],
      departmentOptions: [],
      category: null,
      categoryOptions: [],
      categoryLoading: false,
      sub_category: null,
      subCategoryOptions: [],
      subCategoryLoading: false,
      brand: null,
      brandOptions: [],
      brandLoading: false,
      created_at: null,
      updated_at: null,
      newTaxonomyDialog: false,
      newTaxonomyName: null,
      newTaxonomyNameError: false,
      newTaxonomyNameErrorMessage: '',
      newTaxonomyCode: null,
      newTaxonomyCodeError: false,
      newTaxonomyCodeErrorMessage: '',
      newTaxonomyPageTitle: '',
      newTaxonomyRoute: '',
      mask_name: null,
      newTaxonomy: null,
      tab: 'basic',
      dentodeal_enabled: false,
      dentodeal_sku: null,
      dentodeal_bundled: false,
      dentodeal_bundle_qty: null,
      dentodeal_frontend_url: null,
      dentodeal_id: null,
      sync_stock_dentodeal: true,
      reorder_point: null,
      suppliers: [],
      default_supplier_id: null,
      default_supplier: {
        id: null,
        name: null
      },
      supplierLoading: false,
      length: 0,
      breadth: 0,
      height: 0,
      gtin: null,
      origin_country: null,
      countries: []
    }
  },
  computed: {
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '/products' },
        {
          label: this.$route.params.id ? this.name : 'Create',
          link: '/products/view/' + this.$route.params.id
        }
      ]
      if (this.$route.params.id) {
        arr.push({ label: 'Edit', link: '' })
      }
      return arr
    },
    getpageTitle () {
      return this.$route.params.id ? 'Edit: ' + this.name : 'Create Product'
    }
  },
  methods: {
    saveFn () {
      if (
        this.$refs.name.validate() &
        this.$refs.sku.validate() &
        this.$refs.weight.validate() &
        this.$refs.length.validate() &
        this.$refs.breadth.validate() &
        this.$refs.height.validate() &
        this.$refs.mrp.validate() &
        this.$refs.gsp_customer.validate() &
        this.$refs.gsp_dealer.validate() &
        this.$refs.gst.validate() &
        this.$refs.type.validate() &
        this.$refs.department.validate() &
        this.$refs.category.validate() &
        this.$refs.sub_category.validate() &
        this.$refs.brand.validate() &
        this.$refs.reorder_point.validate()
      ) {
        this.resetErrors()
        const obj = {
          status: this.status,
          name: this.name,
          hsn: this.hsn,
          reorder_code: this.reorder_code,
          gst: this.gst,
          alias: this.alias,
          sku: this.sku,
          weight: this.weight,
          length: this.length,
          breadth: this.breadth,
          height: this.height,
          mask_name: this.mask_name,
          gtin: this.gtin,
          mrp: this.mrp,
          gsp_dealer: this.gsp_dealer,
          gsp_customer: this.gsp_customer,
          enabled: this.enabled,
          expirable: this.expirable,
          type_id: this.type.id,
          department_id: this.department.id,
          category_id: this.category.id,
          sub_category_id: this.sub_category.id,
          brand_id: this.brand.id,
          description: this.description,
          remarks: this.remarks,
          images: JSON.stringify(this.images),
          dentodeal_sku: this.dentodeal_sku,
          dentodeal_enabled: this.dentodeal_enabled,
          dentodeal_bundled: this.dentodeal_bundled,
          dentodeal_bundle_qty: this.dentodeal_bundle_qty,
          dentodeal_frontend_url: this.dentodeal_frontend_url,
          dentodeal_id: this.dentodeal_id,
          sync_stock_dentodeal: this.sync_stock_dentodeal,
          reorder_point: this.reorder_point,
          origin_country: this.origin_country,
          default_supplier_id: this.default_supplier ? this.default_supplier.id : null,
          _method: this.$route.params.id ? 'PUT' : 'POST'
        }
        let route = 'products'
        if (this.$route.params.id) {
          route = 'products/' + this.$route.params.id
        }
        this.$axios
          .post(route, obj)
          .then((res) => {
            if (res.data.message === 'success') {
              this.$q.notify({
                type: 'positive',
                message: 'Saved Succesfully.'
              })
              this.$router.back()
            }
          })
          .catch((error) => {
            if (error.response.status === 422) {
              this.$q.notify({
                type: 'negative',
                message: error.response.data.message
              })
              Object.keys(error.response.data.errors).forEach((key) => {
                this.errors[key] = true
                this.errorMessages[key] = error.response.data.errors[key][0]
              })
            }
          })
      } else {
        this.$q.notify({
          type: 'negative',
          message: 'Invalid Data Given'
        })
      }
    },

    resetErrors () {
      Object.keys(this.errors).forEach((key, i) => {
        this.errors[key] = false
        this.errorMessages[key] = ''
      })
    },

    generateSku () {
      this.$q.loading.show()
      this.errors.sku = null
      let route = 'generate_sku'
      if (this.$route.params.id) {
        route = 'generate_sku/' + this.$route.params.id
      }
      if (
        this.$refs.type.validate() &
        this.$refs.department.validate() &
        this.$refs.category.validate() &
        this.$refs.sub_category.validate() &
        this.$refs.brand.validate()
      ) {
        const obj = {
          type_id: this.type.id,
          department_id: this.department.id,
          category_id: this.category.id,
          sub_category_id: this.sub_category.id,
          brand_id: this.brand.id
        }

        this.$axios
          .post(route, obj)
          .then((res) => {
            if (res.status === 200) {
              this.sku = res.data.code
            }
          })
          .finally(() => {
            this.$q.loading.hide()
          })
      } else {
        this.$q.notify({
          type: 'negative',
          message: 'Please fill up Type,Department,Category,Sub Category,Brand'
        })
        this.$q.loading.hide()
      }
    },
    triggerImgUpload () {
      // console.log(this.$refs.imgupload)
      this.$refs.imgupload.click()
    },
    uploadImg () {
      const imgToUpload = this.$refs.imgupload.files[0]
      const fD = new FormData()
      fD.append('file', imgToUpload)
      this.$axios
        .post('/img_upload?folder=products', fD, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then((res) => {
          const obj = res.data
          obj.name = ''
          obj.description = ''
          this.images.push(obj)
        })
    },
    deleteImg (index) {
      const fD = new FormData()
      fD.append('data', JSON.stringify(this.images[index]))
      this.$axios.post('/img_delete', fD).then((res) => {
        if (res.data.message === 'success') this.images.splice(index, 1)
      })
    },
    openImgDialog (ind) {
      this.img_index = ind
      this.img_name = this.images[ind].name
      this.img_description = this.images[ind].description
      this.imgDialog = true
    },
    saveImgDetails () {
      const ind = this.img_index
      this.images[ind].name = this.img_name
      this.images[ind].description = this.img_description
      this.closeImgDialog()
    },
    closeImgDialog () {
      this.img_index = 0
      this.img_name = null
      this.img_description = null
      this.imgDialog = false
    },
    blurFn (key, i) {
      if (this.attributes[key][i].field_type === 'Decimal') {
        this.attributes[key][i].value = Number(
          this.attributes[key][i].value
        ).toFixed(2)
      }
    },
    typeFilterFn (val, update, abort) {
      update(() => {
        if (val && val.length > 1) {
          const needle = val.toLowerCase()
          this.typeOptions = this.types.filter(
            (v) => v.name.toLowerCase().indexOf(needle) > -1
          )
        }
      })
    },
    departmentFilterFn (val, update, abort) {
      update(() => {
        if (val && val.length > 1) {
          const needle = val.toLowerCase()
          this.departmentOptions = this.departments.filter(
            (v) => v.name.toLowerCase().indexOf(needle) > -1
          )
        }
      })
    },
    categoryFilterFn (val, update, abort) {
      update(() => {
        if (val && val.length > 1) {
          this.categoryLoading = true
          this.$axios
            .get('product_categories/search?keyword=' + encodeURIComponent(val))
            .then((res) => {
              this.categoryOptions = res.data
            })
            .finally(() => {
              this.categoryLoading = false
            })
        }
      })
    },
    subCategoryFilterFn (val, update, abort) {
      update(() => {
        if (val && val.length > 1) {
          this.subCategoryLoading = true
          this.$axios
            .get(
              'product_sub_categories/search?keyword=' + encodeURIComponent(val)
            )
            .then((res) => {
              this.subCategoryOptions = res.data
            })
            .finally(() => {
              this.subCategoryLoading = false
            })
        }
      })
    },
    brandFilterFn (val, update, abort) {
      update(() => {
        if (val && val.length > 1) {
          this.brandLoading = true
          this.$axios
            .get('product_brands/search?keyword=' + encodeURIComponent(val))
            .then((res) => {
              this.brandOptions = res.data
            })
            .finally(() => {
              this.brandLoading = false
            })
        }
      })
    },
    supplierFilterFn (val, update, abort) {
      update(() => {
        if (val) {
          this.supplierLoading = true
          this.$axios.get('suppliers_search?&search=' + encodeURIComponent(val)).then((res) => {
            this.suppliers = res.data
          }).finally(() => {
            this.supplierLoading = false
          })
        }
      })
    },
    openNewTaxonomyDialog (taxonomy) {
      this.newTaxonomy = taxonomy
      if (taxonomy === 'type') {
        this.newTaxonomyPageTitle = 'Product Type'
        this.newTaxonomyRoute = 'product_types'
      }
      if (taxonomy === 'department') {
        this.newTaxonomyPageTitle = 'Product Department'
        this.newTaxonomyRoute = 'product_departments'
      }
      if (taxonomy === 'category') {
        this.newTaxonomyPageTitle = 'Product Category'
        this.newTaxonomyRoute = 'product_categories'
      }
      if (taxonomy === 'sub_category') {
        this.newTaxonomyPageTitle = 'Product Sub Category'
        this.newTaxonomyRoute = 'product_sub_categories'
      }
      if (taxonomy === 'brand') {
        this.newTaxonomyPageTitle = 'Product Brand'
        this.newTaxonomyRoute = 'product_brands'
      }
      this.resetNewTaxonomyForm()
      this.newTaxonomyDialog = true
      this.$nextTick(() => {
        this.$refs.new_taxonomy_name.focus()
      })
    },
    resetNewTaxonomyForm () {
      this.newTaxonomyName = null
      this.newTaxonomyCode = null
      this.newTaxonomyNameError = false
      this.newTaxonomyNameErrorMessage = ''
      this.newTaxonomyCodeError = false
      this.newTaxonomyCodeErrorMessage = ''
    },
    closeNewTaxonomyDialog () {
      this.newTaxonomy = null
      this.resetNewTaxonomyForm()
      this.newTaxonomyPageTitle = ''
      this.newTaxonomyRoute = ''
      this.newTaxonomyDialog = false
    },
    submitNewTaxonomy () {
      this.$q.loading.show()
      const obj = {
        name: this.capitaliseFn(this.newTaxonomyName),
        code: this.newTaxonomyCode.toUpperCase()
      }
      this.newTaxonomyNameError = false
      this.newTaxonomyNameErrorMessage = ''
      this.newTaxonomyCodeError = false
      this.newTaxonomyCodeErrorMessage = ''
      this.$axios
        .post(this.newTaxonomyRoute, obj)
        .then((res) => {
          if (res.data.message === 'success') {
            this[this.newTaxonomy] = {
              id: res.data.id,
              name: res.data.name
            }
            if (this.newTaxonomy === 'type') {
              this.types.push({
                id: res.data.id,
                name: res.data.name
              })
            }
            if (this.newTaxonomy === 'department') {
              this.departments.push({
                id: res.data.id,
                name: res.data.name
              })
            }
            this.closeNewTaxonomyDialog()
          }
        })
        .catch((error) => {
          if (error.response.data.errors.name) {
            this.newTaxonomyNameError = true
            this.newTaxonomyNameErrorMessage =
              error.response.data.errors.name[0]
          }
          if (error.response.data.errors.code) {
            this.newTaxonomyCodeError = true
            this.newTaxonomyCodeErrorMessage =
              error.response.data.errors.code[0]
          }
        })
        .finally(() => {
          this.$q.loading.hide()
        })
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
    }
  }
}
</script>
