(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[52],{d424:function(e,t,r){"use strict";r.r(t);var a=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("q-page",[r("page-toolbar",{attrs:{buttons:e.toolbarButtons},on:{create:e.createFn}}),r("breadcrumbs",{attrs:{breadcrumbs:e.breadcrumbs}}),r("simple-data-table",{ref:"simpleDataTable",attrs:{route:"product_brands","page-preferences":e.pagePreferences,"edit-permission":"update_product","delete-permission":"delete_product","page-edit":!0},on:{"edit-fn":e.editFn}}),r("q-dialog",{attrs:{persistent:""},model:{value:e.createDialog,callback:function(t){e.createDialog=t},expression:"createDialog"}},[r("q-card",{staticStyle:{width:"700px","max-width":"80vw"}},[r("q-card-section",{staticClass:"row items-center q-pb-none"},[e.edit_mode?r("div",[e._v("Edit product brand: "+e._s(e.name))]):r("div",[e._v("Create new product brand")]),r("q-space"),r("q-btn",{attrs:{icon:"close",flat:"",round:"",dense:""},on:{click:function(t){return e.closeCreateDialog()}}})],1),r("q-card-section",[r("div",{staticClass:"row"},[r("div",{staticClass:"col"},[r("q-input",{ref:"name",attrs:{label:"Name",outlined:"",square:"",error:e.nameError,"error-message":e.nameErrorMessage},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.saveFn()},blur:function(t){e.name=e.capitaliseFn(e.name)}},model:{value:e.name,callback:function(t){e.name=t},expression:"name"}}),r("q-input",{ref:"code",attrs:{label:"Code",outlined:"",square:"",error:e.codeError,"error-message":e.codeErrorMessage},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.saveFn()},blur:function(t){e.code=e.code.toUpperCase()}},model:{value:e.code,callback:function(t){e.code=t},expression:"code"}})],1)])]),r("q-card-actions",[r("q-btn",{attrs:{label:"Save",color:"green-10",icon:"save"},on:{click:function(t){return e.saveFn()}}}),r("q-btn",{attrs:{label:"cancel",color:"secondary",flat:""},on:{click:function(t){return e.closeCreateDialog()}}})],1)],1)],1)],1)},s=[],o=r("a89c"),n=r("b6c6"),i=r("fde3"),c={name:"ProductBrandsIndex",components:{PageToolbar:o["a"],Breadcrumbs:n["a"],SimpleDataTable:i["a"]},data(){return{edit_mode:!1,edit_id:null,name:null,nameError:null,nameErrorMessage:"",code:null,codeError:null,codeErrorMessage:"",createDialog:!1,breadcrumbs:[{label:"Dashboard",link:"/"},{label:"Product Brands",link:"/product_brands"}],pagePreferences:{page_index_visible_columns:"product_brands_index_visible_columns",page_index_pagination:"product_brands_index_pagination"}}},mounted(){this.$store.commit("setPageTitle","Product Brands")},computed:{toolbarButtons(){const e=[];return this.$store.getters.hasPermissionTo("create_product")&&e.push({label:"Create",id:"create",type:"button",color:"teal",icon:"add"}),e}},methods:{createFn(){this.resetFn(),this.edit_mode=!1,this.createDialog=!0,this.$nextTick((()=>{this.$refs.name.focus()}))},editFn(e){this.edit_id=e.id,this.name=e.name,this.nameError=!1,this.nameErrorMessage="",this.code=e.code,this.codeError=!1,this.codeErrorMessage="",this.edit_mode=!0,this.createDialog=!0,this.$nextTick((()=>{this.$refs.name.focus(),this.$refs.name.select()}))},closeCreateDialog(){this.resetFn(),this.edit_id=null,this.edit_mode=!1,this.createDialog=!1},resetFn(){this.name=null,this.nameError=!1,this.nameErrorMessage="",this.code=null,this.codeError=!1,this.codeErrorMessage=""},saveFn(){let e="product_brands";this.edit_mode&&(e="product_brands/"+this.edit_id);const t={name:this.capitaliseFn(this.name),code:this.code.toUpperCase(),_method:this.edit_mode?"PUT":"POST"};this.$q.loading.show(),this.$axios.post(e,t).then((e=>{"success"===e.data.message&&(this.$q.loading.hide(),this.$q.notify({type:"positive",message:"Data Saved Successfully"}),this.closeCreateDialog(),this.refreshFn())})).catch((e=>{this.$q.loading.hide(),422===e.response.status&&(this.$q.notify({type:"negative",message:e.response.data.message}),this.nameError=!0,e.response.data.errors.name&&(this.nameError=!0,this.nameErrorMessage=e.response.data.errors.name[0]),e.response.data.errors.code&&(this.codeError=!0,this.codeErrorMessage=e.response.data.errors.code[0]))}))},refreshFn(){const e=this.$refs.simpleDataTable;e.getDataFromApi()},capitaliseFn(e){if(e){const t=e.split(" ");for(let e=0;e<t.length;e++)t[e]=t[e].charAt(0).toUpperCase()+t[e].substring(1);return t.join(" ")}return""}}},d=c,l=r("2877"),u=r("9989"),p=r("24e8"),m=r("f09f"),h=r("a370"),b=r("2c91"),g=r("9c40"),f=r("27f9"),_=r("4b7e"),E=r("eebe"),v=r.n(E),k=Object(l["a"])(d,a,s,!1,null,null,null);t["default"]=k.exports;v()(k,"components",{QPage:u["a"],QDialog:p["a"],QCard:m["a"],QCardSection:h["a"],QSpace:b["a"],QBtn:g["a"],QInput:f["a"],QCardActions:_["a"]})}}]);