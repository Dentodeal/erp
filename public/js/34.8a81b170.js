(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[34],{b8b5:function(e,a,t){"use strict";t.r(a);var s=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("q-page",[t("page-toolbar",{attrs:{buttons:e.toolbarButtons}}),t("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),t("advanced-data-table",{attrs:{"server-sync":"","update-on-visibility":!1,route:"goods_receive_notes","page-preferences":e.pagePreferences,"edit-permission":"update_purchase","delete-permission":"delete_purchase"}})],1)},o=[],r=t("a89c"),n=t("ab3c"),c=t("b6c6"),d={name:"GoodsReceiveNotesIndex",components:{PageToolbar:r["a"],AdvancedDataTable:n["a"],Breadcrumbs:c["a"]},mounted(){this.$store.commit("setPageTitle","Goods Receive Notes")},data(){return{breadcrumbs:[{label:"Dashboard",link:"/"},{label:"Goods Receive Notes",link:"",disabled:!0}],pagePreferences:{page_index_autosearch:"goods_receive_notes_index_autosearch"}}},computed:{toolbarButtons(){const e=[];return this.$store.getters.hasPermissionTo("create_purchase")&&e.push({label:"Create",id:"create",link:"/goods_receive_notes/create",type:"a",color:"teal",icon:"add"}),e}}},i=d,l=t("2877"),b=t("9989"),u=t("eebe"),p=t.n(u),m=Object(l["a"])(i,s,o,!1,null,null,null);a["default"]=m.exports;p()(m,"components",{QPage:b["a"]})}}]);