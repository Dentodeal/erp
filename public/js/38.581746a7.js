(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[38],{"85f9":function(e,a,t){"use strict";t.r(a);var s=function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("q-page",[t("page-toolbar",{attrs:{buttons:e.toolbarButtons}}),t("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),t("advanced-data-table",{attrs:{"server-sync":"",route:"leads","page-preferences":e.pagePreferences,"edit-permission":"update_lead","delete-permission":"delete_lead"}})],1)},n=[],r=t("a89c"),d=t("ab3c"),l=t("b6c6"),i={name:"LeadsIndex",components:{PageToolbar:r["a"],AdvancedDataTable:d["a"],Breadcrumbs:l["a"]},data(){return{breadcrumbs:[{label:"Dashboard",link:"/"},{label:"Leads",link:"",disabled:!0}],pagePreferences:{page_index_visible_columns:"leads_index_visible_columns",page_index_pagination:"leads_index_pagination",page_index_search:"leads_index_search",page_index_autosearch:"leads_index_autosearch",page_index_filtered:"leads_index_filtered"}}},mounted(){this.$store.commit("setPageTitle","Leads")},computed:{toolbarButtons(){const e=[];return this.$store.getters.hasPermissionTo("create_lead")&&e.push({label:"Create",id:"create",link:"/leads/create",type:"a",color:"teal",icon:"add"}),e}}},o=i,c=t("2877"),b=t("9989"),u=t("eebe"),p=t.n(u),_=Object(c["a"])(o,s,n,!1,null,null,null);a["default"]=_.exports;p()(_,"components",{QPage:b["a"]})}}]);