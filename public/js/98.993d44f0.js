(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[98],{c00d:function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("q-page",[a("page-toolbar",{attrs:{buttons:e.toolbarButtons}}),a("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),a("simple-data-table",{ref:"simpleDataTable",attrs:{route:"stock_entries","page-preferences":{},"edit-permission":"update_warehouse","delete-permission":"delete_warehouse","page-edit":!0,"infinite-rows":""}})],1)},s=[],o=a("a89c"),n=a("b6c6"),l=a("fde3"),i={name:"StockEntriesIndex",components:{PageToolbar:o["a"],Breadcrumbs:n["a"],SimpleDataTable:l["a"]},mounted(){this.$store.commit("setPageTitle","Stock Entries")},data(){return{edit_mode:!1,edit_id:null,name:null,nameError:null,nameErrorMessage:"",createDialog:!1,breadcrumbs:[{label:"Dashboard",link:"/"},{label:"Stock Entries",link:"/stock_entries"}]}},computed:{toolbarButtons(){const e=[];return this.$store.getters.hasPermissionTo("create_warehouse")&&e.push({label:"Create",id:"create",type:"a",link:"/stock_entries/create",color:"teal",icon:"add"}),e}},methods:{}},c=i,u=a("2877"),d=a("9989"),b=a("eebe"),m=a.n(b),p=Object(u["a"])(c,r,s,!1,null,null,null);t["default"]=p.exports;m()(p,"components",{QPage:d["a"]})}}]);