(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[105],{cb53:function(e,r,s){"use strict";s.r(r);var a=function(){var e=this,r=e.$createElement,s=e._self._c||r;return s("q-page",[s("page-toolbar",{attrs:{buttons:e.toolbarButtons}}),s("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),s("simple-data-table",{attrs:{route:"user_roles","page-preferences":e.pagePreferences,"edit-permission":"update_user_role","delete-permission":"delete_user_role"}})],1)},t=[],o=s("a89c"),l=s("fde3"),n=s("b6c6"),i={components:{Breadcrumbs:n["a"],PageToolbar:o["a"],SimpleDataTable:l["a"]},mounted(){this.$store.getters.hasPermissionTo("viewAny_user_role")||(this.$router.push({name:"Error403"}),this.$store.commit("setPageTitle","User Roles"))},data(){return{toolbarButtons:[{label:"Create",id:"create",type:"a",link:"/user_roles/create",color:"teal"}],breadcrumbs:[{label:"Dashboard",link:"/"},{label:"User Roles",link:"",disabled:!0}],pagePreferences:{page_index_visible_columns:"user_roles_index_visible_columns",page_index_pagination:"user_roles_index_pagination"}}}},u=i,b=s("2877"),c=s("9989"),p=s("eebe"),d=s.n(p),_=Object(b["a"])(u,a,t,!1,null,null,null);r["default"]=_.exports;d()(_,"components",{QPage:c["a"]})}}]);