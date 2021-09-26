(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[50],{fefe:function(e,t,a){"use strict";a.r(t);var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("q-page",[a("page-toolbar",{attrs:{buttons:e.toolbarButtons},on:{create:e.createFn}}),a("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),a("simple-data-table",{ref:"simpleDataTable",attrs:{route:"pricelists","page-preferences":e.pagePreferences,"edit-permission":"update_pricelist","delete-permission":"delete_pricelist","page-edit":!0},on:{"edit-fn":e.editFn}}),a("q-dialog",{attrs:{persistent:""},model:{value:e.createDialog,callback:function(t){e.createDialog=t},expression:"createDialog"}},[a("q-card",{staticStyle:{width:"700px","max-width":"80vw"}},[a("q-card-section",{staticClass:"row items-center q-pb-none"},[e.edit_mode?a("div",[e._v("Edit pricelist: "+e._s(e.name))]):a("div",[e._v("Create new pricelist")]),a("q-space"),a("q-btn",{attrs:{icon:"close",flat:"",round:"",dense:""},on:{click:function(t){return e.closeCreateDialog()}}})],1),a("q-card-section",[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("q-input",{ref:"name",attrs:{label:"Name",outlined:"",square:"",error:e.nameError,"error-message":e.nameErrorMessage},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.saveFn()},blur:function(t){e.name=e.capitaliseFn(e.name)}},model:{value:e.name,callback:function(t){e.name=t},expression:"name"}})],1)])]),a("q-card-actions",[a("q-btn",{attrs:{label:"Save",color:"green-10",icon:"save"},on:{click:function(t){return e.saveFn()}}}),a("q-btn",{attrs:{label:"cancel",color:"secondary",flat:""},on:{click:function(t){return e.closeCreateDialog()}}})],1)],1)],1)],1)},i=[],r=a("a89c"),n=a("b6c6"),o=a("fde3"),l={name:"PricelistsIndex",components:{PageToolbar:r["a"],Breadcrumbs:n["a"],SimpleDataTable:o["a"]},data(){return{edit_mode:!1,edit_id:null,name:null,nameError:null,nameErrorMessage:"",createDialog:!1,breadcrumbs:[{label:"Dashboard",link:"/"},{label:"Pricelists",link:"/pricelists"}],pagePreferences:{page_index_visible_columns:"pricelists_index_visible_columns",page_index_pagination:"pricelists_index_pagination"}}},mounted(){this.$store.commit("setPageTitle","Pricelists")},computed:{toolbarButtons(){const e=[];return this.$store.getters.hasPermissionTo("create_pricelist")&&e.push({label:"Create",id:"create",type:"button",color:"teal",icon:"add"}),e}},methods:{createFn(){this.name=null,this.nameError=!1,this.nameErrorMessage="",this.edit_mode=!1,this.createDialog=!0,this.$nextTick((()=>{this.$refs.name.focus()}))},editFn(e){this.edit_id=e.id,this.name=e.name,this.nameError=!1,this.nameErrorMessage="",this.edit_mode=!0,this.createDialog=!0,this.$nextTick((()=>{this.$refs.name.focus(),this.$refs.name.select()}))},closeCreateDialog(){this.nameError=!1,this.nameErrorMessage="",this.name=null,this.edit_id=null,this.edit_mode=!1,this.createDialog=!1},saveFn(){let e="pricelists";this.edit_mode&&(e="pricelists/"+this.edit_id);const t={name:this.capitaliseFn(this.name),_method:this.edit_mode?"PUT":"POST"};this.$q.loading.show(),this.$axios.post(e,t).then((e=>{"success"===e.data.message&&(this.$q.loading.hide(),this.$q.notify({type:"positive",message:"Data Saved Successfully"}),this.closeCreateDialog(),this.refreshFn())})).catch((e=>{this.$q.loading.hide(),422===e.response.status&&(this.$q.notify({type:"negative",message:e.response.data.message}),this.nameError=!0,this.nameErrorMessage=e.response.data.errors.name[0])}))},refreshFn(){const e=this.$refs.simpleDataTable;e.getDataFromApi()},capitaliseFn(e){if(e){const t=e.split(" ");for(let e=0;e<t.length;e++)t[e]=t[e].charAt(0).toUpperCase()+t[e].substring(1);return t.join(" ")}return""}}},c=l,d=a("2877"),m=a("9989"),p=a("24e8"),u=a("f09f"),h=a("a370"),g=a("2c91"),b=a("9c40"),f=a("27f9"),_=a("4b7e"),v=a("eebe"),q=a.n(v),D=Object(d["a"])(c,s,i,!1,null,null,null);t["default"]=D.exports;q()(D,"components",{QPage:m["a"],QDialog:p["a"],QCard:u["a"],QCardSection:h["a"],QSpace:g["a"],QBtn:b["a"],QInput:f["a"],QCardActions:_["a"]})}}]);