(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[64],{"0aa7":function(t,a,e){"use strict";e.r(a);var s=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("q-page",[e("page-toolbar",{attrs:{buttons:t.toolbarButtons}}),e("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),e("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[e("q-card",[e("q-card-section",[e("div",{staticClass:"row"},[e("div",{staticClass:"col"},[e("q-table",{attrs:{columns:t.columns,data:t.sales,"row-key":"bill_number"},scopedSlots:t._u([{key:"body",fn:function(a){return[e("q-tr",{attrs:{props:a}},[e("q-td",{staticClass:"text-left"},[t._v("\n                                  "+t._s(a.rowIndex+1)+"\n                              ")]),e("q-td",{staticClass:"text-left"},[e("router-link",{attrs:{to:"/purchases/view/"+a.row.purchase.id}},[t._v(t._s(a.row.purchase.bill_number))])],1),e("q-td",{staticClass:"text-left"},[t._v("\n                                  "+t._s(a.row.purchase.status)+"\n                              ")]),e("q-td",{staticClass:"text-left"},[t._v("\n                                "+t._s(a.row.purchase.type)+"\n                              ")]),e("q-td",{staticClass:"text-right"},[t._v("\n                                  "+t._s(a.row.qty)+"\n                              ")]),e("q-td",{staticClass:"text-right"},[t._v("\n                                  "+t._s(a.row.cost)+"\n                              ")]),e("q-td",{staticClass:"text-right"},[t._v("\n                                  "+t._s(a.row.purchase.bill_date)+"\n                              ")])],1)]}}])})],1)])])],1)],1)],1)},l=[],r=(e("e6cf"),e("a79d"),e("ddb0"),e("a89c")),n=e("b6c6"),i={name:"SaleOrderIndex",components:{PageToolbar:r["a"],Breadcrumbs:n["a"]},data(){return{name:null,sales:[],columns:[{name:"sl_no",label:"#",field:"sl_no",align:"left"},{name:"bill_number",label:"Bill No.",field:t=>t.purchase.bill_number,align:"left",sortable:!0},{name:"status",label:"Status",field:t=>t.purchase.status,align:"left",sortable:!0},{name:"type",label:"Type",field:t=>t.purchase.type,align:"left",sortable:!0},{name:"qty",label:"Qty",field:"qty",align:"right",sortable:!0},{name:"cost",label:"Cost",field:"cost",align:"right",sortable:!0},{name:"bill_date",label:"Bill Date",field:t=>t.purchase.bill_date,align:"right",sortable:!0}]}},mounted(){this.$q.loading.show(),Promise.all([this.$axios.get("products/purchases/"+this.$route.params.id).then((t=>{this.sales=t.data.filter((t=>null!==t.purchase))})),this.$axios.get("products/"+this.$route.params.id).then((t=>{this.name=t.data.name}))]).finally((()=>{this.$store.commit("setPageTitle","Purchases of "+this.name),this.$q.loading.hide()}))},computed:{breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Products",link:"/products"},{label:this.name,link:"/products/view/"+this.$route.params.id},{label:"Purchases"}];return t},toolbarButtons(){const t=[];return t}}},o=i,c=e("2877"),d=e("9989"),u=e("f09f"),b=e("a370"),m=e("eaac"),h=e("bd08"),p=e("db86"),f=e("eebe"),_=e.n(f),g=Object(c["a"])(o,s,l,!1,null,null,null);a["default"]=g.exports;_()(g,"components",{QPage:d["a"],QCard:u["a"],QCardSection:b["a"],QTable:m["a"],QTr:h["a"],QTd:p["a"]})}}]);