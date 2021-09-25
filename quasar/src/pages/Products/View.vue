<template>
    <q-page>
        <page-toolbar :buttons="toolbarButtons"
            v-on:sendforapproval="sendForApproval"
            v-on:approve="approve"
            v-on:activate="activate"
            v-on:revert="revert"/>
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
                                        <td>SKU</td>
                                        <td>{{sku}}</td>
                                    </tr>
                                    <tr v-if="default_supplier">
                                        <td>Default Supplier</td>
                                        <td>{{default_supplier.name}}</td>
                                    </tr>
                                    <tr >
                                        <td>Alias</td>
                                        <td>{{alias}}</td>
                                    </tr>
                                    <tr >
                                        <td>Mask Name</td>
                                        <td>{{mask_name}}</td>
                                    </tr>
                                    <tr >
                                        <td>Enabled</td>
                                        <td>{{enabled ? 'Yes':'No'}}</td>
                                    </tr>
                                    <tr >
                                        <td>Expirable</td>
                                        <td>{{expirable ? 'Yes':'No'}}</td>
                                    </tr>
                                    <tr >
                                        <td>HSN</td>
                                        <td>{{hsn}}</td>
                                    </tr>
                                    <tr >
                                        <td>Reorder Code / MPN</td>
                                        <td>{{reorder_code}}</td>
                                    </tr>
                                    <tr >
                                        <td>GTIN</td>
                                        <td>{{gtin}}</td>
                                    </tr>
                                     <tr >
                                        <td>Origin Country</td>
                                        <td>{{origin_country}}</td>
                                    </tr>
                                    <tr >
                                        <td>Reorder Point</td>
                                        <td>{{reorder_point}}</td>
                                    </tr>
                                    <tr >
                                        <td>GST</td>
                                        <td>{{gst}}%</td>
                                    </tr>
                                    <tr >
                                        <td>Weight</td>
                                        <td>{{weight}}</td>
                                    </tr>
                                    <tr v-if="length !== null && breadth !== null && height !== null">
                                        <td>Dimension (L x B x H)</td>
                                        <td>{{length}}cm x {{breadth}}cm x {{height}}</td>
                                    </tr>
                                    <tr v-if="$store.getters.hasPermissionTo('view_product_pricing')" class="bg-grey-10">
                                      <td class="text-green text-subtitle2">Cost Price</td>
                                      <td class="text-green text-subtitle2">{{cost}}</td>
                                    <tr >
                                        <td>MRP</td>
                                        <td>{{mrp}}</td>
                                    </tr>
                                    <tr >
                                        <td>GSP Customer</td>
                                        <td>{{gsp_customer}}</td>
                                    </tr>
                                    <tr >
                                        <td>GSP Dealer</td>
                                        <td>{{gsp_dealer}}</td>
                                    </tr>
                                    <tr >
                                        <td>Product Type</td>
                                        <td>{{type.name}}</td>
                                    </tr>
                                    <tr >
                                        <td>Department</td>
                                        <td>{{department.name}}</td>
                                    </tr>
                                    <tr >
                                        <td>Category</td>
                                        <td>{{category.name}}</td>
                                    </tr>
                                    <tr >
                                        <td>Sub Category</td>
                                        <td>{{sub_category.name}}</td>
                                    </tr>
                                    <tr >
                                        <td>Brand</td>
                                        <td>{{brand.name}}</td>
                                    </tr>
                                    <tr >
                                        <td>Description</td>
                                        <td v-html="description"></td>
                                    </tr>
                                    <tr >
                                        <td>Remarks</td>
                                        <td v-html="remarks"></td>
                                    </tr>
                                </tbody>
                            </q-markup-table>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="row">
                                <div class="col-12">
                                    <q-btn v-if="$store.getters.hasPermissionTo('view_product_pricing')" class="q-mt-md" label="Cost & Pricing" color="blue-10" :to="'/products/cost/'+$route.params.id"/>
                                    <q-btn label="Stock" class="q-ml-md q-mt-md" color="blue-10" :to="'/products/stock/'+$route.params.id"/>
                                    <q-btn v-if="$store.getters.hasPermissionTo('viewAny_sale_order')" label="Sales" class="q-ml-md q-mt-md" color="blue-10" :to="'/products/sales/'+$route.params.id"/>
                                    <q-btn v-if="$store.getters.hasPermissionTo('viewAny_purchase')" label="Purchases" class="q-ml-md q-mt-md" color="blue-10" :to="'/products/purchases/'+$route.params.id"/>
                                </div>
                            </div>
                            <q-separator class="q-mt-md"/>
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-h6 text-center">Images</div>
                                    <div class="text-center text-subtitle1 q-mt-md" v-if="images.length == 0">No Images Available</div>
                                    <div class="q-gutter-md row items-start q-mt-md" v-else>
                                        <q-img
                                            v-for="(img,ind) in images"
                                            :key="ind+'img'"
                                            :src="img.thumbnail_uri"
                                            style="width: 150px"
                                            ratio="1"
                                            spinner-color="white"
                                            class="rounded-borders"
                                            @click="openImgDialog(ind)">
                                        </q-img>
                                    </div>
                                </div>
                            </div>
                            <q-separator class="q-mt-md"/>
                            <div class="text-h6 text-center q-mt-md">Dentodeal Details</div>
                            <q-markup-table separator="cell">
                              <tbody>
                                <tr >
                                      <td>Dentodeal SKU</td>
                                      <td>{{dentodeal_sku}}</td>
                                  </tr>
                                  <tr >
                                      <td>Dentodeal Enabled</td>
                                      <td>{{dentodeal_enabled ? 'Yes':'No'}}</td>
                                  </tr>
                                  <tr >
                                      <td>Dentodeal Bundled</td>
                                      <td>{{dentodeal_bundled ? 'Yes':'No'}}</td>
                                  </tr>
                                  <tr >
                                      <td>Sync Stock with Dentodeal</td>
                                      <td>{{sync_stock_dentodeal ? 'Yes':'No'}}</td>
                                  </tr>
                                  <tr v-if="dentodeal_bundled">
                                      <td>Dentodeal Bundle Qty</td>
                                      <td>{{dentodeal_bundle_qty}}</td>
                                  </tr>
                                  <tr v-if="dentodeal_frontend_url">
                                      <td>Dentodeal Frontend URL</td>
                                      <td><q-btn type="a" :href="dentodeal_frontend_url" target="_blank">Visit</q-btn></td>
                                  </tr>
                                  <tr v-if="dentodeal_id">
                                      <td>Dentodeal Backend URL</td>
                                      <td><q-btn type="a" unelevated color="primary" :href="'https://dentodeal.com/admin_9910/catalog/product/edit/id/' + dentodeal_id" target="_blank">Visit</q-btn></td>
                                  </tr>
                              </tbody>
                            </q-markup-table>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </div>
        <q-dialog v-model="imgDialog" persistent maximized transition-show="slide-up" transition-hide="slide-down">
            <q-card class="">
                 <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">Image Details</div>
                    <q-space />
                    <q-btn icon="close" flat round dense @click="closeImgDialog"/>
                </q-card-section>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <div class="col-xs-12 col-md-9">
                            <q-img :src="images.length > 0 ? images[this.img_index].uri: ''"/>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <q-input disable v-model="img_name" label="Name" outlined square />
                            <q-input disable type="textarea" class="q-mt-md" v-model="img_description" label="Description" outlined square />                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </q-dialog>
        <page-toolbar :page-title="name+' Details' " :buttons="toolbarButtons"
            v-on:sendforapproval="sendForApproval"
            v-on:approve="approve"
            v-on:activate="activate"
            v-on:revert="revert"
            class="q-mt-md" />
    </q-page>
</template>

<script>
import PageToolbar from 'components/PageToolbar.vue'
import Breadcrumbs from 'components/Breadcrumbs.vue'
export default {
  name: 'ProductsView',
  components: {
    PageToolbar,
    Breadcrumbs
  },
  data () {
    return {
      name: null,
      sku: null,
      reorder_code: null,
      alias: null,
      hsn: null,
      gst: null,
      enabled: null,
      expirable: null,
      mrp: null,
      cost: null,
      gsp_customer: null,
      gsp_dealer: null,
      weight: null,
      length: null,
      breadth: null,
      height: null,
      gtin: null,
      mask_name: null,
      origin_country: null,
      description: '',
      remarks: '',
      default_supplier: {
        name: null
      },
      type: {
        name: null
      },
      department: {
        name: null
      },
      category: {
        name: null
      },
      sub_category: {
        name: null
      },
      brand: {
        name: null
      },
      created_at: null,
      updated_at: null,
      images: [],
      img_index: 0,
      img_name: null,
      img_description: null,
      imgDialog: false,
      status: null,
      dentodeal_enabled: false,
      dentodeal_sku: null,
      dentodeal_bundled: false,
      dentodeal_bundle_qty: null,
      dentodeal_frontend_url: null,
      dentodeal_id: null,
      sync_stock_dentodeal: null,
      reorder_point: null
    }
  },
  computed: {
    toolbarButtons () {
      const arr = []
      if (this.$store.getters.hasPermissionTo('revert_product') && this.status !== 'Draft') {
        arr.push({
          label: 'Revert',
          id: 'revert',
          type: 'button',
          color: 'grey-10',
          icon: 'undo'
        })
      }
      if (this.$store.getters.hasPermissionTo('approve_product') && this.status === 'Pending Approval') {
        arr.push({
          label: 'Approve',
          id: 'approve',
          type: 'button',
          color: 'green-10',
          icon: 'check'
        })
      }
      if (this.$store.getters.hasPermissionTo('activate_product') && this.status === 'Approved') {
        arr.push({
          label: 'Activate',
          id: 'activate',
          type: 'button',
          color: 'green-10',
          icon: 'check'
        })
      }
      if (this.$store.getters.hasPermissionTo('update_product') && this.status === 'Draft') {
        arr.push({
          label: 'Send for Approval',
          id: 'sendforapproval',
          type: 'button',
          color: 'orange-10',
          icon: 'pan_tool'
        })
      }
      if (this.$store.getters.hasPermissionTo('update_product')) {
        arr.push({
          label: 'Edit',
          id: 'edit',
          type: 'a',
          link: '/products/edit/' + this.$route.params.id,
          color: 'teal',
          icon: 'edit'
        })
      }
      return arr
    },
    breadcrumbs () {
      const arr = [
        { label: 'Dashboard', link: '/' },
        { label: 'Products', link: '/products' },
        { label: this.name, link: '' }
      ]
      return arr
    }
  },
  mounted () {
    if (!this.$store.getters.hasPermissionTo('view_product')) {
      this.$router.push({ name: 'Error403' })
    } else {
      this.$q.loading.show()
      Promise.all([
        this.$axios.get('/products/cost/' + this.$route.params.id).then((res) => {
          this.cost = res.data.cost ? res.data.cost : 0
        }),
        this.$axios.get('products/' + this.$route.params.id).then((res) => {
          Object.keys(res.data).forEach((key) => {
            this[key] = res.data[key]
          })
        })
      ]).finally(() => {
        this.$store.commit('setPageTitle', 'Product: ' + this.name)
        this.$q.loading.hide()
      })
    }
  },
  methods: {
    openImgDialog (ind) {
      this.img_index = ind
      this.img_name = this.images[ind].name
      this.img_description = this.images[ind].description
      this.imgDialog = true
    },
    closeImgDialog () {
      this.img_index = 0
      this.img_name = null
      this.img_description = null
      this.imgDialog = false
    },
    sendForApproval () {
      this.$axios.get('products/request_approval/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.back()
        }
      })
    },
    approve () {
      this.$axios.get('products/approve/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.back()
        }
      })
    },
    activate () {
      this.$axios.get('products/activate/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.back()
        }
      })
    },
    revert () {
      this.$axios.get('products/revert/' + this.$route.params.id).then((res) => {
        if (res.data.message === 'success') {
          this.$q.notify({
            type: 'positive',
            message: 'Data Updated Successfully'
          })
          this.$router.back()
        }
      })
    }
  }
}
</script>
