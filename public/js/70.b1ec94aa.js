(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[70],{7235:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{activate:function(e){return t.activate()}}}),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("q-card",[a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col-12 col-md-6"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Serial No")]),a("td",[t._v(t._s(t.serial_no))])]),t.purchase.supplier?a("tr",[a("td",[t._v("Supplier")]),a("td",[a("router-link",{attrs:{to:"/suppliers/view/"+t.purchase.supplier.id}},[t._v(t._s(t.purchase.supplier.name))])],1)]):t._e(),t.purchase.warehouse?a("tr",[a("td",[t._v("Warehouse")]),a("td",[t._v(t._s(t.purchase.warehouse.name))])]):t._e(),a("tr",[a("td",[t._v("Purchase")]),a("td",[a("router-link",{attrs:{to:"/purchases/view/"+t.purchase.id}},[t._v(t._s(t.purchase.bill_number))])],1)])])])],1),a("div",{staticClass:"col-12 col-md-6"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Status")]),a("td",[t._v(t._s(t.status))])]),a("tr",[a("td",[t._v("Created By")]),a("td",[t._v(t._s(t.created_by.name))])]),a("tr",[a("td",[t._v("Created At")]),a("td",[t._v(t._s(t.getLocaleString(t.created_at)))])])])])],1)]),a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-table",{staticClass:"my-sticky-header-table",attrs:{columns:t.columns,title:"Items",data:t.items,"row-key":"sl_no","wrap-cells":"",loading:t.table_loading,"rows-per-page-options":[0],filter:t.search},scopedSlots:t._u([{key:"body",fn:function(e){return[a("q-tr",{attrs:{props:e}},[a("q-td",{staticClass:"text-right"},[t._v(t._s(e.rowIndex+1))]),a("q-td",[t._v("\n                        "+t._s(e.row.product.name)+"\n                        "),a("q-btn",{attrs:{icon:"content_copy",flat:"",round:""},on:{click:function(a){return t.$store.dispatch("copyToClipboard",e.row.name)}}})],1),a("q-td",{staticClass:"text-right"},[t._v("\n                        "+t._s(e.row.qty.toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}))+"\n                        "),a("q-popup-edit",{attrs:{buttons:"","label-set":"Save","label-cancel":"Close",validate:function(){return!!t.$refs.qty_edit.validate()}},on:{hide:function(a){t.update(),e.row.qty=parseInt(e.row.qty)}},model:{value:e.row.qty,callback:function(a){t.$set(e.row,"qty",a)},expression:"props.row.qty"}},[a("q-input",{ref:"qty_edit",staticClass:"input-right",attrs:{dense:"",autofocus:"",rules:[function(t){return!isNaN(t)||"Invalid"}]},on:{focus:function(e){return t.$refs.qty_edit.select()}},model:{value:e.row.qty,callback:function(a){t.$set(e.row,"qty",a)},expression:"props.row.qty"}})],1)],1)],1)]}},{key:"top-right",fn:function(){return[a("q-input",{attrs:{borderless:"",dense:"",debounce:"300",placeholder:"Search"},scopedSlots:t._u([{key:"append",fn:function(){return[a("q-icon",{attrs:{name:"search"}})]},proxy:!0}]),model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})]},proxy:!0}])})],1)]),a("div",{staticClass:"row q-mt-sm"},[a("div",{staticClass:"col q-px-md"},[a("div",{staticClass:"text-h6"},[t._v("Remarks")]),a("q-editor",{attrs:{rows:"3"},model:{value:t.remarks,callback:function(e){t.remarks=e},expression:"remarks"}})],1)])])],1),a("page-toolbar",{staticClass:"q-mt-md",attrs:{"page-title":"",buttons:t.toolbarButtons},on:{activate:function(e){return t.activate()}}})],1)},r=[],o=(a("e6cf"),a("a79d"),a("a89c")),i=a("b6c6"),n={name:"SaleOrdersView",components:{PageToolbar:o["a"],Breadcrumbs:i["a"]},data(){return{serial_no:"",purchase:{id:null,bill_no:null,supplier:{id:null,name:null},warehouse:null},created_by:{name:null},created_at:null,status:null,remarks:"",items:[],search:null,table_loading:!1,errorQty:!1,errorMessageQty:""}},mounted(){this.getDataFromApi()},computed:{toolbarButtons(){const t=[];return this.$store.getters.hasPermissionTo("invoice_sale_order")&&"Active"!==this.status&&t.push({label:"Activate",id:"activate",type:"button",color:"green-7",icon:"check"}),t},columns(){const t=[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"name",field:"name",label:"Product",sortable:!0,align:"left"},{name:"qty",field:"qty",label:"Qty",sortable:!0}];return t},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Purchase Returns",link:"/purchase_returns"},{label:this.serial_no}];return t}},watch:{},methods:{update(){this.$q.loading.show(),this.$axios.post("purchase_returns/"+this.$route.params.id,{_method:"PUT",items:this.items}).then((t=>{"success"===t.data.message&&this.$q.notify({type:"positive",message:"Updated Successfully"})})).finally((()=>{this.$q.loading.hide()}))},getDataFromApi(){this.$q.loading.show(),this.$axios.get("purchase_returns/"+this.$route.params.id).then((t=>{this.serial_no=t.data.serial_no,this.purchase=t.data.purchase,this.created_by=t.data.created_by,this.created_at=t.data.created_at,this.status=t.data.status,this.remarks=t.data.remarks,this.items=t.data.items})).finally((()=>{this.$store.commit("setPageTitle",this.serial_no),this.$q.loading.hide()}))},getLocaleString(t){if(t){const e=new Date(t);return e.toLocaleString("en-IN")}return""},qtyEditValidation(t){return t&&Number.isInteger(Number(t))?(this.errorQty=!1,this.errorMessageQty="",!0):(this.errorQty=!0,this.errorMessageQty="Invalid Entry",!1)},activate(){this.$axios.post("purchase_returns/activate/"+this.$route.params.id,{remarks:this.remarks}).then((t=>{"success"===t.data.message&&(this.$router.back(),this.$q.notify({type:"positive",message:"Purchase Return Activated Successfully"}))}))}}},l=n,c=a("2877"),u=a("9989"),d=a("f09f"),p=a("2bb1"),m=a("eaac"),h=a("bd08"),b=a("db86"),_=a("9c40"),v=a("42a1"),y=a("27f9"),q=a("0016"),g=a("d66b"),f=a("eebe"),w=a.n(f),k=Object(c["a"])(l,s,r,!1,null,null,null);e["default"]=k.exports;w()(k,"components",{QPage:u["a"],QCard:d["a"],QMarkupTable:p["a"],QTable:m["a"],QTr:h["a"],QTd:b["a"],QBtn:_["a"],QPopupEdit:v["a"],QInput:y["a"],QIcon:q["a"],QEditor:g["a"]})}}]);