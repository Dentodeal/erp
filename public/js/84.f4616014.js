(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[84],{"4b54":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{registerpayment:function(e){t.settlementDialog=!0}}}),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Serial No")]),a("td",[a("router-link",{attrs:{to:"/sale_orders/view/"+t.$route.params.id}},[t._v(t._s(t.model.serial_no))])],1)]),a("tr",[a("td",[t._v("Customer")]),a("td",[a("router-link",{attrs:{to:"/customers/view/"+t.model.customer.id}},[t._v(t._s(t.model.customer.name))])],1)]),a("tr",[a("td",[t._v("Warehouse")]),a("td",[t._v(t._s(t.model.warehouse.name))])]),a("tr",[a("td",[t._v("Pricelist")]),a("td",[t._v(t._s(t.model.pricelist.name))])]),a("tr",[a("td",[t._v("Source")]),a("td",[t._v(t._s(t.model.source))])]),t.model.shipment?a("tr",[a("td",[t._v("Shipment")]),a("td",[a("router-link",{attrs:{to:"/shipments/view/"+t.model.shipment.id}},[t._v(t._s(t.model.shipment.serial_no))])],1)]):t._e()])])],1),a("div",{staticClass:"col"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Status")]),a("td",[t._v(t._s(t.model.status))])]),a("tr",[a("td",[t._v("Created By")]),a("td",[t._v(t._s(t.model.created_by.name))])]),a("tr",[a("td",[t._v("Representative")]),a("td",[t._v(t._s(t.model.representative.name))])]),a("tr",[a("td",[t._v("Created At")]),a("td",[t._v(t._s(t.getLocaleString(t.model.created_at)))])]),a("tr",[a("td",[t._v("Invoiced At")]),a("td",[t._v(t._s(t.getLocaleString(t.model.invoiced_at)))])]),t.model.invoiced_by?a("tr",[a("td",[t._v("Invoiced By")]),a("td",[t._v(t._s(t.model.invoiced_by.name))])]):t._e()])])],1)]),a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Payment Status:\n                "),a("q-chip",{attrs:{color:t.getPaymentStatusColor(),"text-color":"white"}},[t._v(t._s(t.model.payment_status))])],1),a("td",[t._v("Paid: "+t._s(t.model.paid_amount))]),a("td",[t._v("Balance: "+t._s(t.balance_amount))])])])])],1)]),a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("Rate Includes GST: "+t._s(t.model.rate_includes_gst?"Yes":"No"))]),a("td",[t._v("Include Flood Cess ?: "+t._s(t.model.flood_cess_included?"Yes":"No"))]),a("td",[t._v("OTC: "+t._s(t.model.otc?"Yes":"No"))]),a("td",[t._v("COD: "+t._s(t.model.otc?"Yes":"No"))])])])])],1)]),a("div",{staticClass:"row q-col-gutter-md q-pa-md "},[a("div",{staticClass:"col"},[a("q-table",{staticClass:"my-sticky-header-table",attrs:{columns:t.columns,title:"Items",data:t.model.items,"row-key":"sl_no","wrap-cells":"",loading:t.table_loading,"rows-per-page-options":[0],filter:t.search},scopedSlots:t._u([{key:"body",fn:function(e){return[a("q-tr",{attrs:{props:e}},[a("q-td",{staticClass:"text-right"},[t._v(t._s(e.rowIndex+1))]),a("q-td",[t._v("\n                "+t._s(e.row.product.name)+"\n                "),a("q-btn",{attrs:{icon:"content_copy",flat:"",round:""},on:{click:function(a){return t.$store.dispatch("copyToClipboard",e.row.product.name)}}})],1),a("q-td",{staticClass:"text-right"},[e.row.gst?a("div",[t._v("\n                  "+t._s(e.row.gst)+"%\n                ")]):t._e()]),a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(parseFloat(e.row.qty).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}))+"\n              ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(parseFloat(e.row.rate).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}))+"\n              ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(e.row.expiry_date)+"\n              ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(e.row.is_free?"Yes":"No")+"\n              ")]),t.model.rate_includes_gst?t._e():a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(parseFloat(e.row.taxable).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}))+"\n              ")]),t.model.rate_includes_gst?t._e():a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(parseFloat(e.row.tax_amount).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}))+"\n              ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                "+t._s(parseFloat(e.row.total).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2}))+"\n              ")])],1)]}},{key:"top-right",fn:function(){return[a("q-input",{attrs:{borderless:"",dense:"",debounce:"300",placeholder:"Search"},scopedSlots:t._u([{key:"append",fn:function(){return[a("q-icon",{attrs:{name:"search"}})]},proxy:!0}]),model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})]},proxy:!0},{key:"bottom-row",fn:function(){return[a("q-tr",{staticClass:"bg-blue-grey-3"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Subtotal")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(parseFloat(t.model.subtotal).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1),a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Discount")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(parseFloat(t.model.discount).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1),a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Freight")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(parseFloat(t.model.freight).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1),a("q-tr",{staticClass:"bg-blue-grey-1"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Round")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(parseFloat(t.model.round).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1),a("q-tr",{staticClass:"bg-blue-grey-3"},[a("q-td",{attrs:{colspan:t.model.rate_includes_gst?6:8}}),a("q-td",{staticClass:"text-right"},[t._v("Total")]),a("q-td",{staticClass:"text-right"},[t._v(t._s(parseFloat(t.model.total).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])],1)]},proxy:!0}])})],1)]),a("div",{staticClass:"row q-mt-sm"},[a("div",{staticClass:"col q-px-md"},[a("div",{staticClass:"text-h6"},[t._v(" Additional Information")]),a("q-markup-table",{attrs:{"wrap-cells":"",separator:"cell"}},[a("tbody",[a("tr",[a("td",[t._v("PO Number: "+t._s(t.model.po_number))]),a("td",[t._v("Order Date: "+t._s(t.model.order_date))])])])])],1)]),a("div",{staticClass:"row q-mt-sm"},[a("div",{staticClass:"col q-px-md"},[a("div",{staticClass:"text-h6"},[t._v("Remarks")]),a("div",{domProps:{innerHTML:t._s(t.model.remarks)}})])])]),a("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{registerpayment:function(e){t.settlementDialog=!0}}}),a("q-dialog",{attrs:{persistent:"",position:"top"},model:{value:t.settlementDialog,callback:function(e){t.settlementDialog=e},expression:"settlementDialog"}},[a("settlement-dialog",{attrs:{status:t.model.status,customer:t.model.customer,total:t.model.total,id:t.$route.params.id,route:"register_payment",serial_no:t.model.serial_no},on:{close:function(e){t.settlementDialog=!1,t.getDataFromApi()}}})],1)],1)},l=[],o=(a("e6cf"),a("a79d"),a("a89c")),i=a("b6c6"),r=a("b456"),n={name:"SaleOrdersRevisitsView",components:{PageToolbar:o["a"],Breadcrumbs:i["a"],settlementDialog:r["a"]},data(){return{model:{serial_no:"",customer:{name:null},representative:{name:null},created_by:{name:null},created_at:null,invoiced_at:null,status:null,remarks:"",warehouse:{name:null},pricelist:{name:null},source:null,rate_includes_gst:null,flood_cess_included:null,items:[],subtotal:null,discount:null,freight:null,round:null,total:null,cod:0,otc:0,payment_status:null,paid_amount:null,cod_charge:null,shipment:null,invoiced_by:{name:null}},search:null,table_loading:!1,remarkDialog:!1,approveRemark:"",settlementDialog:!1,amount:0,reference_id:"",payment_via:null,settlement_remarks:""}},mounted(){this.getDataFromApi()},computed:{balance_amount(){return this.model.total-this.model.paid_amount},toolbarButtons(){const t=[];return t.push({label:"Register Payment",id:"registerpayment",type:"button",color:"teal-10",icon:"add"}),t},columns(){const t=[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"name",field:t=>t.product.name,label:"Product",sortable:!0,align:"left"},{name:"gst",field:"gst",label:"GST",sortable:!0},{name:"qty",field:"qty",label:"Qty",sortable:!0},{name:"rate",field:"rate",label:"Rate",sortable:!0},{name:"expiry_date",field:"expiry_date",label:"Exp Date",sortable:!0},{name:"is_free",field:"is_free",label:"Free ?",sortable:!0}];return this.model.rate_includes_gst||t.push({name:"taxable",field:"taxable",label:"Taxable",sortable:!0},{name:"tax_amount",field:"tax_amount",label:"Tax Amount",sortable:!0}),t.push({name:"total",field:"total",label:"Total",sortable:!0}),t},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Sale Orders Revists",link:"/sale_order_revisits"},{label:this.model.serial_no}];return t}},methods:{getDataFromApi(){this.$q.loading.show(),this.$axios.get("sale_orders/"+this.$route.params.id).then((t=>{this.model=t.data})).finally((()=>{this.$store.commit("setPageTitle",this.model.serial_no),this.$q.loading.hide()}))},getPaymentStatusColor(){return"Settled"===this.model.payment_status?"green-10":"Partially Settled"===this.model.payment_status?"orange-10":"Unsettled"===this.model.payment_status?"red-10":void 0},submitPayment(){null===this.amount||void 0===this.amount||""===this.amount?this.$q.notify({message:"Amount should not be empty",type:"negative"}):null===this.payment_via&&parseFloat(this.amount)>0?this.$q.notify({message:"Payment Via is not specified",type:"negative"}):this.$axios.post("sale_orders/register_payment/"+this.$route.params.id,{payment_via:this.payment_via,amount:this.amount,reference_id:this.reference_id,remarks:this.settlement_remarks}).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Payment Registered Successfully"}),this.settlementDialog=!1,this.getDataFromApi())}))},getLocaleString(t){if(t){const e=new Date(t);return e.toLocaleString()}return""}}},d=n,m=a("2877"),c=a("9989"),u=a("2bb1"),_=a("b047"),g=a("eaac"),p=a("bd08"),b=a("db86"),v=a("9c40"),h=a("27f9"),y=a("0016"),q=a("24e8"),x=a("eebe"),f=a.n(x),C=Object(m["a"])(d,s,l,!1,null,null,null);e["default"]=C.exports;f()(C,"components",{QPage:c["a"],QMarkupTable:u["a"],QChip:_["a"],QTable:g["a"],QTr:p["a"],QTd:b["a"],QBtn:v["a"],QInput:h["a"],QIcon:y["a"],QDialog:q["a"]})}}]);