(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[25],{7670:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("page-toolbar",{attrs:{buttons:t.toolbarButtons}}),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("q-card",[a("q-card-section",[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("q-table",{attrs:{columns:t.columns,data:t.sales,"row-key":"serial_no"},scopedSlots:t._u([{key:"body-cell-sl_no",fn:function(e){return[a("q-td",{attrs:{props:e}},[t._v("\n                                  "+t._s(e.rowIndex+1)+"\n                              ")])]}},{key:"body-cell-serial_no",fn:function(e){return[a("q-td",{attrs:{props:e}},[a("router-link",{attrs:{to:"/sale_orders/view/"+e.row.id}},[t._v(t._s(e.row.serial_no))])],1)]}},{key:"body-cell-created_at",fn:function(e){return[a("q-td",{attrs:{props:e}},[t._v("\n                                  "+t._s(t.getLocaleString(e.row.created_at))+"\n                              ")])]}},{key:"body-cell-total",fn:function(e){return[a("q-td",{attrs:{props:e}},[t._v("\n                                  "+t._s(e.row.total)+"\n                              ")])]}}])})],1)])])],1)],1)],1)},l=[],r=(a("e6cf"),a("a79d"),a("ddb0"),a("a89c")),n=a("b6c6"),o={name:"SaleOrderIndex",components:{PageToolbar:r["a"],Breadcrumbs:n["a"]},data(){return{name:null,sales:[],columns:[{name:"sl_no",label:"#",field:"sl_no",align:"left"},{name:"serial_no",label:"Serial No",field:"serial_no",align:"left",sortable:!0},{name:"status",label:"Status",field:"status",align:"left",sortable:!0},{name:"payment_status",label:"Payment Status",field:"payment_status",align:"left",sortable:!0},{name:"created_at",label:"Created At",field:"created_at",align:"right",sortable:!0},{name:"total",label:"Total",field:"total",align:"right",sortable:!0}]}},mounted(){this.$q.loading.show(),Promise.all([this.$axios.get("customers/sales/"+this.$route.params.id).then((t=>{this.sales=t.data})),this.$axios.get("customers/"+this.$route.params.id).then((t=>{this.name=t.data.name}))]).finally((()=>{this.$q.loading.hide(),this.$store.commit("setPageTitle",this.getTitle)}))},computed:{getTitle(){return"Sale Orders of "+this.name},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Customers",link:"/customers"},{label:this.name,link:"/customers/view/"+this.$route.params.id},{label:"Sales"}];return t},toolbarButtons(){const t=[];return t}}},i=o,d=a("2877"),c=a("9989"),u=a("f09f"),m=a("a370"),b=a("eaac"),f=a("db86"),p=a("eebe"),_=a.n(p),g=Object(d["a"])(i,s,l,!1,null,null,null);e["default"]=g.exports;_()(g,"components",{QPage:c["a"],QCard:u["a"],QCardSection:m["a"],QTable:b["a"],QTd:f["a"]})}}]);