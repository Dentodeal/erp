(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[66],{ab7b:function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("q-page",[a("page-toolbar"),a("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),a("div",{class:e.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("q-card",[a("q-card-section",[a("div",{staticClass:"row"},[a("q-card",{staticClass:"bg-cyan-9",attrs:{dark:""}},[a("q-card-section",[a("div",{staticClass:"text-h4"},[e._v("Total Available Stock: "+e._s(e.total))])])],1)],1),a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("q-table",{attrs:{title:"Stock Status",columns:e.columns,data:e.stocks,"rows-per-page-options":[0]},scopedSlots:e._u([{key:"body",fn:function(t){return[a("q-tr",{attrs:{props:t}},[a("q-td",{key:"sl_no",attrs:{props:t}},[e._v("\n                                      "+e._s(t.rowIndex+1)+"\n                                  ")]),a("q-td",{key:"qty",attrs:{props:t}},[e._v("\n                                      "+e._s(t.row.qty)+"\n                                  ")]),a("q-td",{key:"expiry_date",attrs:{props:t}},[e._v("\n                                      "+e._s(t.row.expiry_date)+"\n                                  ")]),a("q-td",{key:"lot_number",attrs:{props:t}},[e._v("\n                                      "+e._s(t.row.lot_number)+"\n                                  ")]),a("q-td",{key:"reservation",attrs:{props:t}},[0==t.row.reservable_id?a("q-chip",{attrs:{color:"green-10","text-color":"white"}},[e._v("Available")]):a("q-chip",{attrs:{color:"orange-10","text-color":"white"}},[e._v("Reserved")])],1),a("q-td",{key:"reserved_under",attrs:{props:t}},[0!=t.row.reservable_id?a("router-link",{attrs:{to:"/sale_orders/view/"+t.row.reservable_id}},[e._v(e._s(t.row.saleorder.serial_no)+" ("+e._s(t.row.saleorder.status)+")")]):e._e()],1),a("q-td",{key:"warehouse",attrs:{props:t}},[e._v("\n                                      "+e._s(t.row.warehouse.name)+"\n                                  ")])],1)]}}])})],1)])])],1)],1)],1)},s=[],o=(a("e6cf"),a("a79d"),a("ddb0"),a("a89c")),l=a("b6c6"),n={name:"ProductsStock",components:{PageToolbar:o["a"],Breadcrumbs:l["a"]},mounted(){this.$q.loading.show(),Promise.all([this.$axios.get("products/"+this.$route.params.id).then((e=>{this.expirable=e.data.expirable,this.name=e.data.name})),this.$axios.get("/products/stock/"+this.$route.params.id).then((e=>{this.stocks=e.data.status,this.total=e.data.total}))]).finally((()=>{this.$store.commit("setPageTitle","Stock: "+this.name),this.$q.loading.hide()}))},computed:{breadcrumbs(){const e=[{label:"Dashboard",link:"/"},{label:"Products",link:"/products"},{label:this.name,link:"/products/view/"+this.$route.params.id},{label:"Stock"}];return e}},data(){return{expirable:null,stocks:[],name:null,total:0,columns:[{name:"sl_no",label:"#",field:"sl_no",align:"left"},{name:"qty",label:"Qty",field:"qty",align:"right",sortable:!0},{name:"expiry_date",label:"Expiry Date",field:"expiry_date",align:"right",sortable:!0},{name:"lot_number",label:"Lot No.",field:"lot_number",align:"left",sortable:!0},{name:"reservation",label:"Reservation",field:"reservation",align:"left",sortable:!0},{name:"reserved_under",label:"Reserved Under",field:"reserved_under",align:"left",sortable:!0},{name:"warehouse",label:"Warehouse",field:"warehouse",align:"left",sortable:!0}]}},methods:{}},i=n,d=a("2877"),c=a("9989"),b=a("f09f"),p=a("a370"),u=a("eaac"),m=a("bd08"),_=a("db86"),h=a("b047"),v=a("eebe"),w=a.n(v),q=Object(d["a"])(i,r,s,!1,null,null,null);t["default"]=q.exports;w()(q,"components",{QPage:c["a"],QCard:b["a"],QCardSection:p["a"],QTable:u["a"],QTr:m["a"],QTd:_["a"],QChip:h["a"]})}}]);