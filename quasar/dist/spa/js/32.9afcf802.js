(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[32],{"2c02":function(e,t,s){"use strict";s.r(t);var a=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("q-page",[s("page-toolbar",{attrs:{buttons:e.toolbarButtons},on:{createpurchase:e.createPurchase,activate:e.activateFn,revert:e.revert}}),s("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),s("div",{class:e.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[s("div",{staticClass:"row q-col-gutter-md q-pa-md "},[s("div",{staticClass:"col"},[s("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[s("tbody",[s("tr",[s("td",[e._v("Serial No")]),s("td",[e._v(e._s(e.serial_no))])]),s("tr",[s("td",[e._v("Supplier")]),s("td",[s("router-link",{attrs:{to:"/suppliers/view/"+e.supplier.id}},[e._v(e._s(e.supplier.name))])],1)]),s("tr",[s("td",[e._v("Warehouse")]),s("td",[e._v(e._s(e.warehouse.name))])]),e.purchase?s("tr",[s("td",[e._v("Purchase Entry")]),s("td",[s("router-link",{attrs:{to:"/purchases/view/"+e.purchase.id}},[e._v(e._s(e.purchase.bill_number))])],1)]):e._e()])])],1),s("div",{staticClass:"col"},[s("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[s("tbody",[s("tr",[s("td",[e._v("Created By")]),s("td",[e._v(e._s(e.created_by.name))])]),s("tr",[s("td",[e._v("Status")]),s("td",[e._v(e._s(e.status))])]),s("tr",[s("td",[e._v("Delivery Date")]),s("td",[e._v(e._s(e.delivery_date))])])])])],1)]),s("div",{staticClass:"row q-col-gutter-md q-pa-md "},[s("div",{staticClass:"col"},[s("q-table",{staticClass:"my-sticky-header-table",attrs:{columns:e.columns,title:"Items",data:e.items,"row-key":"index","wrap-cells":"",loading:e.table_loading,"rows-per-page-options":[0],filter:e.search},scopedSlots:e._u([{key:"body",fn:function(t){return[s("q-tr",{attrs:{props:t}},[s("q-td",{staticClass:"text-right"},[e._v(e._s(t.rowIndex+1))]),s("q-td",[s("router-link",{attrs:{to:"/products/view/"+t.row.product.id}},[e._v(" "+e._s(t.row.product.name))]),s("q-btn",{attrs:{icon:"content_copy",flat:"",round:""},on:{click:function(s){return e.$store.dispatch("copyToClipboard",t.row.product.name)}}})],1),s("q-td",{staticClass:"text-right"},[e._v("\n                          "+e._s(t.row.expiry_date)+"\n                        ")]),s("q-td",{},[e._v("\n                          "+e._s(t.row.lot_number)+"\n                        ")]),s("q-td",{staticClass:"text-right"},[e._v("\n                            "+e._s(t.row.qty)+"\n                        ")])],1)]}},{key:"top-right",fn:function(){return[s("q-input",{attrs:{borderless:"",dense:"",debounce:"300",placeholder:"Search"},scopedSlots:e._u([{key:"append",fn:function(){return[s("q-icon",{attrs:{name:"search"}})]},proxy:!0}]),model:{value:e.search,callback:function(t){e.search=t},expression:"search"}})]},proxy:!0}])})],1)]),s("div",{staticClass:"row q-mt-xs"},[s("div",{staticClass:"col-12"},[s("div",{staticClass:"text-h6"},[e._v("Remarks")]),s("div",{domProps:{innerHTML:e._s(e.remarks)}})])])]),s("page-toolbar",{staticClass:"q-mt-md",attrs:{"page-title":"",buttons:e.toolbarButtons},on:{activate:e.activateFn,createpurchase:e.createPurchase,revert:e.revert}})],1)},r=[],o=(s("e6cf"),s("a79d"),s("ddb0"),s("a89c")),i=s("b6c6"),l={name:"PurchasesView",components:{PageToolbar:o["a"],Breadcrumbs:i["a"]},data(){return{serial_no:null,supplier:{name:null},created_by:{name:null},warehouse:{name:null},items:[],delivery_date:null,status:null,table_loading:!1,search:null,remarks:"",purchase:null}},computed:{columns(){const e=[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"name",field:"name",label:"Product",sortable:!0,align:"left"},{name:"expiry_date",field:"expiry_date",label:"Expiry Date",sortable:!0,align:"right"},{name:"lot_number",field:"lot_number",label:"Lot No.",sortable:!0,align:"left"},{name:"qty",field:"qty",label:"Qty",sortable:!0}];return e},toolbarButtons(){const e=[];return this.$store.getters.hasPermissionTo("create_purchase")&&("Active"!==this.status||this.purchase&&"Draft"!==this.purchase.status||e.push({label:"Revert",id:"revert",type:"button",color:"black",icon:"undo"}),"Draft"===this.status||"Draft - Reverted"===this.status?(e.push({label:"Activate",id:"activate",type:"button",color:"green-10",icon:"check"}),e.push({label:"Edit",id:"edit",type:"a",link:"/goods_receive_notes/edit/"+this.$route.params.id,color:"teal",icon:"edit"})):this.purchase||e.push({label:"Enter Purchase",id:"createpurchase",type:"button",color:"blue-10",icon:"forward"})),e},breadcrumbs(){const e=[{label:"Dashboard",link:"/"},{label:"Good Receive Notes",link:"/goods_receive_notes"},{label:this.serial_no}];return e}},mounted(){this.$store.getters.hasPermissionTo("view_purchase")?(this.$q.loading.show(),Promise.all([this.getDataFromApi()]).finally((()=>{this.$q.loading.hide()}))):this.$router.push({name:"Error403"})},methods:{getDataFromApi(){this.$q.loading.show(),this.$axios.get("goods_receive_notes/"+this.$route.params.id).then((e=>{this.serial_no=e.data.serial_no,this.status=e.data.status,this.supplier=e.data.supplier,this.delivery_date=e.data.delivery_date,this.created_by=e.data.created_by,this.warehouse=e.data.warehouse,this.remarks=e.data.remarks,this.items=e.data.items,this.purchase=e.data.purchase})).then((()=>{this.$store.commit("setPageTitle","Goods Receive Note: "+this.serial_no),this.$q.loading.hide()}))},generatePurchase(){this.$q.dialog({message:"Do you want to continue",cancel:!0}).onOk((()=>{this.$router.push("/purchases/create?grn_id="+this.$route.params.id),this.$q.notify({color:"blue-10",message:"Note: This purchase order is not saved yet."})}))},createPurchase(){this.$router.push("/purchases/create?grn_id="+this.$route.params.id),this.$q.notify({message:"Note!. This purchase entry is not saved yet.",color:"blue-10"})},activateFn(){this.$q.dialog({message:"Do you want to continue",cancel:!0}).onOk((()=>{this.$axios.get("goods_receive_notes/activate/"+this.$route.params.id).then((e=>{"success"===e.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.push("/goods_receive_notes"))}))}))},revert(){this.$q.dialog({message:"Do you want to continue",cancel:!0}).onOk((()=>{this.$axios.get("goods_receive_notes/revert/"+this.$route.params.id).then((e=>{"success"===e.data.message&&(this.$q.notify({type:"positive",message:"Data Updated Successfully"}),this.$router.push("/goods_receive_notes"))}))}))}}},n=l,c=s("2877"),d=s("9989"),u=s("2bb1"),h=s("eaac"),p=s("bd08"),m=s("db86"),_=s("9c40"),b=s("27f9"),v=s("0016"),g=s("eebe"),y=s.n(g),q=Object(c["a"])(n,a,r,!1,null,null,null);t["default"]=q.exports;y()(q,"components",{QPage:d["a"],QMarkupTable:u["a"],QTable:h["a"],QTr:p["a"],QTd:m["a"],QBtn:_["a"],QInput:b["a"],QIcon:v["a"]})}}]);