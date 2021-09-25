(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[71],{"79b3":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",[s("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{sendforapproval:t.sendForApproval,approve:t.approve,activate:t.activate,revert:t.revert}}),s("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),s("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[s("q-card",[s("q-card-section",[s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-xs-12 col-md-6"},[s("q-markup-table",{attrs:{separator:"cell"}},[s("tbody",[s("tr",[s("td",[t._v("Status")]),s("td",[t._v(t._s(t.status))])]),s("tr",[s("td",[t._v("Name")]),s("td",[t._v(t._s(t.name))])]),s("tr",[s("td",[t._v("SKU")]),s("td",[t._v(t._s(t.sku))])]),t.default_supplier?s("tr",[s("td",[t._v("Default Supplier")]),s("td",[t._v(t._s(t.default_supplier.name))])]):t._e(),s("tr",[s("td",[t._v("Alias")]),s("td",[t._v(t._s(t.alias))])]),s("tr",[s("td",[t._v("Enabled")]),s("td",[t._v(t._s(t.enabled?"Yes":"No"))])]),s("tr",[s("td",[t._v("Expirable")]),s("td",[t._v(t._s(t.expirable?"Yes":"No"))])]),s("tr",[s("td",[t._v("HSN")]),s("td",[t._v(t._s(t.hsn))])]),s("tr",[s("td",[t._v("Reorder Code / MPN")]),s("td",[t._v(t._s(t.reorder_code))])]),s("tr",[s("td",[t._v("GTIN")]),s("td",[t._v(t._s(t.gtin))])]),s("tr",[s("td",[t._v("Origin Country")]),s("td",[t._v(t._s(t.origin_country))])]),s("tr",[s("td",[t._v("Reorder Point")]),s("td",[t._v(t._s(t.reorder_point))])]),s("tr",[s("td",[t._v("GST")]),s("td",[t._v(t._s(t.gst)+"%")])]),s("tr",[s("td",[t._v("Weight")]),s("td",[t._v(t._s(t.weight))])]),null!==t.length&&null!==t.breadth&&null!==t.height?s("tr",[s("td",[t._v("Dimension (L x B x H)")]),s("td",[t._v(t._s(t.length)+"cm x "+t._s(t.breadth)+"cm x "+t._s(t.height))])]):t._e(),t.$store.getters.hasPermissionTo("view_product_pricing")?s("tr",{staticClass:"bg-grey-10"},[s("td",{staticClass:"text-green text-subtitle2"},[t._v("Cost Price")]),s("td",{staticClass:"text-green text-subtitle2"},[t._v(t._s(t.cost))])]):t._e(),s("tr",[s("td",[t._v("MRP")]),s("td",[t._v(t._s(t.mrp))])]),s("tr",[s("td",[t._v("GSP Customer")]),s("td",[t._v(t._s(t.gsp_customer))])]),s("tr",[s("td",[t._v("GSP Dealer")]),s("td",[t._v(t._s(t.gsp_dealer))])]),s("tr",[s("td",[t._v("Product Type")]),s("td",[t._v(t._s(t.type.name))])]),s("tr",[s("td",[t._v("Department")]),s("td",[t._v(t._s(t.department.name))])]),s("tr",[s("td",[t._v("Category")]),s("td",[t._v(t._s(t.category.name))])]),s("tr",[s("td",[t._v("Sub Category")]),s("td",[t._v(t._s(t.sub_category.name))])]),s("tr",[s("td",[t._v("Brand")]),s("td",[t._v(t._s(t.brand.name))])]),s("tr",[s("td",[t._v("Description")]),s("td",{domProps:{innerHTML:t._s(t.description)}})]),s("tr",[s("td",[t._v("Remarks")]),s("td",{domProps:{innerHTML:t._s(t.remarks)}})])])])],1),s("div",{staticClass:"col-xs-12 col-md-6"},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-12"},[t.$store.getters.hasPermissionTo("view_product_pricing")?s("q-btn",{staticClass:"q-mt-md",attrs:{label:"Cost & Pricing",color:"blue-10",to:"/products/cost/"+t.$route.params.id}}):t._e(),s("q-btn",{staticClass:"q-ml-md q-mt-md",attrs:{label:"Stock",color:"blue-10",to:"/products/stock/"+t.$route.params.id}}),t.$store.getters.hasPermissionTo("viewAny_sale_order")?s("q-btn",{staticClass:"q-ml-md q-mt-md",attrs:{label:"Sales",color:"blue-10",to:"/products/sales/"+t.$route.params.id}}):t._e(),t.$store.getters.hasPermissionTo("viewAny_purchase")?s("q-btn",{staticClass:"q-ml-md q-mt-md",attrs:{label:"Purchases",color:"blue-10",to:"/products/purchases/"+t.$route.params.id}}):t._e()],1)]),s("q-separator",{staticClass:"q-mt-md"}),s("div",{staticClass:"row"},[s("div",{staticClass:"col-12"},[s("div",{staticClass:"text-h6 text-center"},[t._v("Images")]),0==t.images.length?s("div",{staticClass:"text-center text-subtitle1 q-mt-md"},[t._v("No Images Available")]):s("div",{staticClass:"q-gutter-md row items-start q-mt-md"},t._l(t.images,(function(e,a){return s("q-img",{key:a+"img",staticClass:"rounded-borders",staticStyle:{width:"150px"},attrs:{src:e.thumbnail_uri,ratio:"1","spinner-color":"white"},on:{click:function(e){return t.openImgDialog(a)}}})})),1)])]),s("q-separator",{staticClass:"q-mt-md"}),s("div",{staticClass:"text-h6 text-center q-mt-md"},[t._v("Dentodeal Details")]),s("q-markup-table",{attrs:{separator:"cell"}},[s("tbody",[s("tr",[s("td",[t._v("Dentodeal SKU")]),s("td",[t._v(t._s(t.dentodeal_sku))])]),s("tr",[s("td",[t._v("Dentodeal Enabled")]),s("td",[t._v(t._s(t.dentodeal_enabled?"Yes":"No"))])]),s("tr",[s("td",[t._v("Dentodeal Bundled")]),s("td",[t._v(t._s(t.dentodeal_bundled?"Yes":"No"))])]),t.dentodeal_bundled?s("tr",[s("td",[t._v("Dentodeal Bundle Qty")]),s("td",[t._v(t._s(t.dentodeal_bundle_qty))])]):t._e(),t.dentodeal_frontend_url?s("tr",[s("td",[t._v("Dentodeal Frontend URL")]),s("td",[s("q-btn",{attrs:{type:"a",href:t.dentodeal_frontend_url,target:"_blank"}},[t._v("Visit")])],1)]):t._e(),t.dentodeal_id?s("tr",[s("td",[t._v("Dentodeal Backend URL")]),s("td",[s("q-btn",{attrs:{type:"a",unelevated:"",color:"primary",href:"https://dentodeal.com/admin_9910/catalog/product/edit/id/"+t.dentodeal_id,target:"_blank"}},[t._v("Visit")])],1)]):t._e()])])],1)])])],1)],1),s("q-dialog",{attrs:{persistent:"",maximized:"","transition-show":"slide-up","transition-hide":"slide-down"},model:{value:t.imgDialog,callback:function(e){t.imgDialog=e},expression:"imgDialog"}},[s("q-card",{},[s("q-card-section",{staticClass:"row items-center q-pb-none"},[s("div",{staticClass:"text-h6"},[t._v("Image Details")]),s("q-space"),s("q-btn",{attrs:{icon:"close",flat:"",round:"",dense:""},on:{click:t.closeImgDialog}})],1),s("q-card-section",[s("div",{staticClass:"row q-col-gutter-md"},[s("div",{staticClass:"col-xs-12 col-md-9"},[s("q-img",{attrs:{src:t.images.length>0?t.images[this.img_index].uri:""}})],1),s("div",{staticClass:"col-xs-12 col-md-3"},[s("q-input",{attrs:{disable:"",label:"Name",outlined:"",square:""},model:{value:t.img_name,callback:function(e){t.img_name=e},expression:"img_name"}}),s("q-input",{staticClass:"q-mt-md",attrs:{disable:"",type:"textarea",label:"Description",outlined:"",square:""},model:{value:t.img_description,callback:function(e){t.img_description=e},expression:"img_description"}})],1)])])],1)],1),s("page-toolbar",{staticClass:"q-mt-md",attrs:{"page-title":t.name+" Details",buttons:t.toolbarButtons},on:{sendforapproval:t.sendForApproval,approve:t.approve,activate:t.activate,revert:t.revert}})],1)},r=[],i=(s("e01a"),s("e6cf"),s("a79d"),s("ddb0"),s("a89c")),o=s("b6c6"),l={name:"ProductsView",components:{PageToolbar:i["a"],Breadcrumbs:o["a"]},data(){return{name:null,sku:null,reorder_code:null,alias:null,hsn:null,gst:null,enabled:null,expirable:null,mrp:null,cost:null,gsp_customer:null,gsp_dealer:null,weight:null,length:null,breadth:null,height:null,gtin:null,origin_country:null,description:"",remarks:"",default_supplier:{name:null},type:{name:null},department:{name:null},category:{name:null},sub_category:{name:null},brand:{name:null},created_at:null,updated_at:null,images:[],img_index:0,img_name:null,img_description:null,imgDialog:!1,status:null,dentodeal_enabled:!1,dentodeal_sku:null,dentodeal_bundled:!1,dentodeal_bundle_qty:null,dentodeal_frontend_url:null,dentodeal_id:null,reorder_point:null}},computed:{toolbarButtons(){const t=[];return this.$store.getters.hasPermissionTo("revert_product")&&"Draft"!==this.status&&t.push({label:"Revert",id:"revert",type:"button",color:"grey-10",icon:"undo"}),this.$store.getters.hasPermissionTo("approve_product")&&"Pending Approval"===this.status&&t.push({label:"Approve",id:"approve",type:"button",color:"green-10",icon:"check"}),this.$store.getters.hasPermissionTo("activate_product")&&"Approved"===this.status&&t.push({label:"Activate",id:"activate",type:"button",color:"green-10",icon:"check"}),this.$store.getters.hasPermissionTo("update_product")&&"Draft"===this.status&&t.push({label:"Send for Approval",id:"sendforapproval",type:"button",color:"orange-10",icon:"pan_tool"}),this.$store.getters.hasPermissionTo("update_product")&&t.push({label:"Edit",id:"edit",type:"a",link:"/products/edit/"+this.$route.params.id,color:"teal",icon:"edit"}),t},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Products",link:"/products"},{label:this.name,link:""}];return t}},mounted(){this.$store.getters.hasPermissionTo("view_product")?(this.$q.loading.show(),Promise.all([this.$axios.get("/products/cost/"+this.$route.params.id).then((t=>{this.cost=t.data.cost?t.data.cost:0})),this.$axios.get("products/"+this.$route.params.id).then((t=>{Object.keys(t.data).forEach((e=>{this[e]=t.data[e]}))}))]).finally((()=>{this.$store.commit("setPageTitle","Product: "+this.name),this.$q.loading.hide()}))):this.$router.push({name:"Error403"})},methods:{openImgDialog(t){this.img_index=t,this.img_name=this.images[t].name,this.img_description=this.images[t].description,this.imgDialog=!0},closeImgDialog(){this.img_index=0,this.img_name=null,this.img_description=null,this.imgDialog=!1},sendForApproval(){this.$axios.get("products/request_approval/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.back())}))},approve(){this.$axios.get("products/approve/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.back())}))},activate(){this.$axios.get("products/activate/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.back())}))},revert(){this.$axios.get("products/revert/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.back())}))}}},d=l,n=s("2877"),c=s("9989"),u=s("f09f"),p=s("a370"),_=s("2bb1"),m=s("9c40"),v=s("eb85"),g=s("068f"),h=s("24e8"),b=s("2c91"),q=s("27f9"),y=s("eebe"),x=s.n(y),$=Object(n["a"])(d,a,r,!1,null,null,null);e["default"]=$.exports;x()($,"components",{QPage:c["a"],QCard:u["a"],QCardSection:p["a"],QMarkupTable:_["a"],QBtn:m["a"],QSeparator:v["a"],QImg:g["a"],QDialog:h["a"],QSpace:b["a"],QInput:q["a"]})}}]);