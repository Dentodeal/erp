(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[62],{"39c8":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("page-toolbar",{attrs:{buttons:t.toolbarButtons}}),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("q-card",[a("q-card-section",[a("div",{staticClass:"row q-col-gutter-md"},[a("div",{staticClass:"col-12 col-md-6"},[a("q-card",[a("q-card-section",[a("q-file",{ref:"upfile",attrs:{label:"Click here to Upload",clearable:""},scopedSlots:t._u([{key:"prepend",fn:function(){return[a("q-icon",{attrs:{name:"attach_file"}})]},proxy:!0}]),model:{value:t.file,callback:function(e){t.file=e},expression:"file"}}),a("q-select",{attrs:{options:["create","update"],label:"Method"},model:{value:t.method,callback:function(e){t.method=e},expression:"method"}}),a("q-btn",{staticClass:"q-mt-md",attrs:{color:"teal",icon:"backup",label:"upload"},on:{click:function(e){return t.process()}}})],1)],1)],1),a("div",{staticClass:"col-12 col-md-6"},[a("q-card",[a("q-card-section",[a("div",{staticClass:"subtitle2"},[t._v("Log")]),a("q-list",{attrs:{separator:""}},t._l(t.errors,(function(e,s){return a("q-item",{key:s,staticClass:"bg-negative text-white"},[a("q-item-section",[t._l(e.errors,(function(e,s){return a("q-item-label",{key:s},[t._v(t._s(e))])})),a("q-item-label",{staticClass:"text-white",attrs:{caption:""}},[t._v("Row: "+t._s(e.row))])],2)],1)})),1)],1)],1)],1)])])],1)],1)},o=[],r=(a("e6cf"),a("a79d"),a("a89c")),l=a("b6c6"),i={name:"ProductsImport",components:{PageToolbar:r["a"],Breadcrumbs:l["a"]},mounted(){this.$store.commit("setPageTitle","Products Import")},data(){return{file:null,method:null,toolbarButtons:[],errors:[],breadcrumbs:[{label:"Dashboard",link:"/"},{label:"Products",link:"/products"},{label:"Import",link:"",disabled:!0}]}},methods:{process(){this.$q.loading.show(),this.errors=[];const t=new FormData;t.append("file",this.file),t.append("method",null==this.method?"":this.method),this.$axios.post("/products/import",t,{headers:{"Content-Type":"multipart/form-data"}}).then((t=>{"success"===t.data.message&&(this.$q.notify({type:"positive",message:"Saved Succesfully."}),this.errors=[])})).catch((t=>{422===t.response.status&&(this.errors=t.response.data.errors,this.$q.notify({type:"negative",message:t.response.data.message}))})).finally((()=>{this.$q.loading.hide()}))}}},c=i,n=a("2877"),d=a("9989"),u=a("f09f"),m=a("a370"),p=a("7d53"),b=a("0016"),h=a("ddd8"),f=a("9c40"),q=a("1c1c"),g=a("66e5"),v=a("4074"),k=a("0170"),w=a("eebe"),y=a.n(w),C=Object(n["a"])(c,s,o,!1,null,null,null);e["default"]=C.exports;y()(C,"components",{QPage:d["a"],QCard:u["a"],QCardSection:m["a"],QFile:p["a"],QIcon:b["a"],QSelect:h["a"],QBtn:f["a"],QList:q["a"],QItem:g["a"],QItemSection:v["a"],QItemLabel:k["a"]})}}]);