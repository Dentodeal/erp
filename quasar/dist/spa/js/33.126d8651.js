(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[33],{"8b24":function(t,s,a){"use strict";a.r(s);var e=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("q-page",{staticClass:"q-pa-md"},[a("div",{staticClass:"row q-col-gutter-md"},[a("div",{staticClass:"col-12 col-md-6"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-12"},[a("q-card",{staticClass:"bg-grey-10",attrs:{dark:""}},[a("q-item",[a("q-item-section",[a("q-item-label",[a("div",{staticClass:"text-h6"},[t._v("Today Sales")])]),a("q-item-label",{attrs:{caption:""}},[a("div",{staticClass:"text-right text-white text-h6"},[t._v("OTC: "),a("span",{staticClass:"text-green"},[t._v(t._s(parseFloat(t.sale_data.otc).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])]),a("div",{staticClass:"text-right text-white text-h6"},[t._v("Non OTC: "),a("span",{staticClass:"text-green"},[t._v(t._s(parseFloat(t.sale_data.non_otc).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])]),a("div",{staticClass:"text-right text-white text-h6"},[t._v("Total: "),a("span",{staticClass:"text-green"},[t._v(t._s(parseFloat(t.sale_data.total).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])])])],1)],1)],1)],1),a("div",{staticClass:"col-12 col-md-6"})])]),a("div",{staticClass:"col-12 col-md-6"},[a("div",{staticClass:"row"},[a("div",{staticClass:"col-12"},[a("q-card",{staticClass:"bg-blue-grey-10",attrs:{dark:""}},[a("q-item",[a("q-item-section",[a("q-item-label",[a("div",{staticClass:"text-h6"},[t._v("Today Transactions")])]),a("q-item-label",{attrs:{caption:""}},[a("div",{staticClass:"text-right text-white text-h6"},[t._v("Cash: "),a("span",{staticClass:"text-green"},[t._v(t._s(parseFloat(t.sale_data.cash).toLocaleString("en",{minimumFractionDigits:2,maximumFractionDigits:2})))])])])],1)],1)],1)],1),a("div",{staticClass:"col-12 col-md-6"})])])]),a("div",{staticClass:"q-col-gutter-md row q-mt-sm"},[a("div",{staticClass:"col-12 col-md-6"},[a("q-card",{staticClass:"bg-cyan-7",attrs:{dark:""}},[a("q-item",[a("q-item-section",[a("q-item-label",[a("div",{staticClass:"text-h6"},[t._v("Sale Orders Status")])]),a("q-markup-table",{staticClass:"bg-cyan-10",staticStyle:{width:"100%"},attrs:{dark:"",separator:"cell",flat:"",dense:""}},[a("thead",[a("th",[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(s){return t.$router.push({path:"/sale_orders",query:{source:"Apexion"}})}}},[t._v("Apexion")])],1),a("th",[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(s){return t.$router.push({path:"/sale_orders",query:{source:"Dentodeal"}})}}},[t._v("Dentodeal")])],1)]),t.sales_count?a("tbody",t._l(Object.keys(t.sales_count),(function(s,e){return a("tr",{key:e},[t._l(t.sales_count[s],(function(e,o){return[2===t.sales_count[s].length?["Apexion"===e.source?[a("td",{key:o+"ap"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/sale_orders",query:{status:s,source:"Apexion"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1)]:t._e(),"Dentodeal"===e.source?[a("td",{key:o+"de"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/sale_orders",query:{status:s,source:"Dentodeal"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1)]:t._e()]:["Apexion"===e.source?[a("td",{key:o+"en"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/sale_orders",query:{status:s,source:"Apexion"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1),a("td",{key:o+"em"})]:t._e(),"Dentodeal"===e.source?[a("td",{key:o+"em"}),a("td",{key:o+"en"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/sale_orders",query:{status:s,source:"Dentodeal"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1)]:t._e()]]}))],2)})),0):t._e()])],1)],1)],1)],1),a("div",{staticClass:"col-12 col-md-6"},[a("q-card",{staticClass:"bg-blue-7",attrs:{dark:""}},[a("q-item",[a("q-item-section",[a("q-item-label",[a("div",{staticClass:"text-h6"},[t._v("Shipments Status")])]),a("q-markup-table",{staticClass:"bg-blue -10",staticStyle:{width:"100%"},attrs:{dark:"",separator:"cell",flat:"",dense:""}},[a("thead",[a("th",[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(s){return t.$router.push({path:"/shipments",query:{source:"Apexion"}})}}},[t._v("Apexion")])],1),a("th",[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(s){return t.$router.push({path:"/shipments",query:{source:"Dentodeal"}})}}},[t._v("Dentodeal")])],1)]),t.shipments_count?a("tbody",t._l(Object.keys(t.shipments_count),(function(s,e){return a("tr",{key:e},[t._l(t.shipments_count[s],(function(e,o){return[2===t.shipments_count[s].length?["Apexion"===e.source?[a("td",{key:o+"ap"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/shipments",query:{status:s,source:"Apexion"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1)]:t._e(),"Dentodeal"===e.source?[a("td",{key:o+"de"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/shipments",query:{status:s,source:"Dentodeal"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1)]:t._e()]:["Apexion"===e.source?[a("td",{key:o+"en"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/shipments",query:{status:s,source:"Apexion"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1),a("td",{key:o+"em"})]:t._e(),"Dentodeal"===e.source?[a("td",{key:o+"em"}),a("td",{key:o+"en"},[a("q-btn",{attrs:{flat:"",dark:"","no-caps":""},on:{click:function(a){return t.$router.push({path:"/shipments",query:{status:s,source:"Dentodeal"}})}}},[t._v("\n                              "+t._s(s)+": "),a("q-badge",{staticClass:"q-ml-sm q-pa-xs",attrs:{color:"white","text-color":"grey-10"}},[t._v(t._s(e.count||"0"))])],1)],1)]:t._e()]]}))],2)})),0):t._e()])],1)],1)],1)],1)])])},o=[],n={name:"Dashboard",data(){return{today_sale:null,sale_data:{otc:null,non_otc:null,total:null},sales_count:null,shipments_count:null}},mounted(){this.updateSaleStatus(),this.updateTodaySale(),this.updateShipmentStatus(),this.$store.getters.user,this.$store.commit("setPageTitle","ApexionERP")},methods:{updateSaleStatus(){this.$axios.get("sales/count").then((t=>{this.sales_count=this.$_.groupBy(t.data,"status")}))},updateTodaySale(){this.$axios.get("sales/today").then((t=>{this.sale_data=t.data}))},updateShipmentStatus(){this.$axios.get("shipments/count").then((t=>{this.shipments_count=this.$_.groupBy(t.data,"status")}))}}},r=n,i=a("2877"),c=a("9989"),l=a("f09f"),u=a("66e5"),d=a("4074"),p=a("0170"),m=a("2bb1"),_=a("9c40"),h=a("58a81"),q=a("eebe"),g=a.n(q),x=Object(i["a"])(r,e,o,!1,null,null,null);s["default"]=x.exports;g()(x,"components",{QPage:c["a"],QCard:l["a"],QItem:u["a"],QItemSection:d["a"],QItemLabel:p["a"],QMarkupTable:m["a"],QBtn:_["a"],QBadge:h["a"]})}}]);