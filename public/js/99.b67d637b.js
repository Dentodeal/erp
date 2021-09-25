(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[99],{4315:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("page-toolbar",{attrs:{buttons:t.toolbarButtons},on:{activate:function(e){return t.activate()},sendtoaccounts:function(e){return t.sendtoaccounts()},revert:function(e){return t.revert()}}}),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("q-card",[a("q-card-section",[a("div",{staticClass:"row q-col-gutter-md q-mb-md"},[a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",dense:"",square:"",readonly:"",label:"Name"},model:{value:t.model.name,callback:function(e){t.$set(t.model,"name",e)},expression:"model.name"}})],1),a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",dense:"",square:"",readonly:"",label:"Created By"},model:{value:t.model.created_by.name,callback:function(e){t.$set(t.model.created_by,"name",e)},expression:"model.created_by.name"}})],1),a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",dense:"",square:"",readonly:"",label:"Status"},model:{value:t.model.status,callback:function(e){t.$set(t.model,"status",e)},expression:"model.status"}})],1)]),a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("q-table",{attrs:{columns:t.columns,title:"Items",data:t.model.items,"row-key":"sl_no","wrap-cells":"","rows-per-page-options":[0],filter:t.search},scopedSlots:t._u([{key:"top-right",fn:function(){return[a("q-input",{attrs:{borderless:"",dense:"",debounce:"300",placeholder:"Search"},scopedSlots:t._u([{key:"append",fn:function(){return[a("q-icon",{attrs:{name:"search"}})]},proxy:!0}]),model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})]},proxy:!0},{key:"body",fn:function(e){return[a("q-tr",{attrs:{props:e}},[a("q-td",{staticClass:"text-right"},[t._v(t._s(e.rowIndex+1))]),a("q-td",[a("router-link",{attrs:{to:"/products/view/"+e.row.product.id}},[t._v("\n                      "+t._s(e.row.product.name)+"\n                      ")]),a("q-btn",{attrs:{icon:"content_copy",flat:"",round:""},on:{click:function(a){return t.$store.dispatch("copyToClipboard",e.row.product.name)}}})],1),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.weight)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.length)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.breadth)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.height)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.gtin)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.reorder_code)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.origin_country)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.qty)+"\n                    ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                      "+t._s(e.row.expirable?"Yes":"No")+"\n                    ")]),a("q-td",{staticClass:"text-right"},[e.row.expirable?a("q-btn",{attrs:{flat:"",color:"primary",label:"Expiry Data"},on:{click:function(a){return t.loadExpiryData(e.row)}}}):t._e()],1)],1)]}}])})],1)]),a("q-card-section",[a("div",{staticClass:"text-subtitle2"},[t._v("Remarks")]),a("div",{domProps:{innerHTML:t._s(t.model.remarks)}})])],1)],1)],1),a("q-dialog",{model:{value:t.expiryDialog,callback:function(e){t.expiryDialog=e},expression:"expiryDialog"}},[a("q-card",{staticStyle:{width:"500px"}},[a("q-card-section",[a("div",{staticClass:"text-subtitle1"},[t._v("Expiry Details")])]),a("q-card-section",t._l(t.expiry_data,(function(e,s){return a("div",{key:s,staticClass:"row"},[a("div",{staticClass:"col"},[a("q-input",{attrs:{filled:"",dense:"",label:"Expiry Date",readonly:""},model:{value:t.expiry_data[s].date,callback:function(e){t.$set(t.expiry_data[s],"date",e)},expression:"expiry_data[i].date"}})],1),a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",square:"",dense:"",label:"Qty",readonly:""},model:{value:t.expiry_data[s].qty,callback:function(e){t.$set(t.expiry_data[s],"qty",e)},expression:"expiry_data[i].qty"}})],1)])})),0),a("q-card-actions",[a("q-btn",{attrs:{label:"close",flat:"",color:"primary"},on:{click:t.closeExpiryDialog}})],1)],1)],1)],1)},i=[],r=(a("e6cf"),a("a79d"),a("a89c")),o=a("b6c6"),l={name:"StockEntriesView",components:{PageToolbar:r["a"],Breadcrumbs:o["a"]},mounted(){this.$route.params.id&&this.$axios.get("stock_entries/"+this.$route.params.id).then((t=>{this.model=t.data}))},computed:{toolbarButtons(){const t=[];return"Draft"===this.model.status?(t.push({label:"Send to Accounts",id:"sendtoaccounts",type:"button",color:"blue",icon:"forward"}),t.push({label:"Edit",id:"edit",type:"a",color:"grey-9",link:"/stock_entries/edit/"+this.$route.params.id,icon:"edit"})):t.push({label:"Revert",id:"revert",type:"button",color:"grey-9",icon:"undo"}),(this.$store.getters.hasPermissionTo("invoice_sale_order")||this.$store.getters.hasRole("Super Admin"))&&"Pending"===this.model.status&&t.push({label:"Activate",id:"activate",type:"button",color:"teal",icon:"check"}),t},breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Stock Entries",link:"/stock_entries"},{label:this.model.name,link:"/stock_entries/view/"+this.$route.params.id}];return t}},data(){return{model:{created_by:{name:null},status:null,name:null,remarks:"",items:[]},search:null,expiry_data:[{date:null,qty:null}],expiryDialog:!1,columns:[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"product",field:t=>t.product.name,label:"Product",sortable:!0,align:"left"},{name:"weight",field:"weight",label:"W",sortable:!0},{name:"length",field:"length",label:"L",sortable:!0},{name:"breadth",field:"breadth",label:"B",sortable:!0},{name:"height",field:"height",label:"H",sortable:!0},{name:"gtin",field:"gtin",label:"GTIN",sortable:!0},{name:"mpn",field:"mpn",label:"MPN",sortable:!0},{name:"origin_country",field:"origin_country",label:"Country",sortable:!0},{name:"qty",field:"qty",label:"Qty",sortable:!0},{name:"expirable",field:"expirable",label:"Expirable",sortable:!0},{name:"expiry_data",field:"expiry_data",label:"Expiry Data",sortable:!0}]}},methods:{loadExpiryData(t){const e=this.$_.findIndex(this.model.items,(e=>e.id===t.id));this.expiry_data=this.model.items[e].expiry_data,this.expiryDialog=!0},closeExpiryDialog(){this.expiryDialog=!1},activate(){this.$q.loading.show(),this.$axios.get("stock_entries/activate/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({message:"Activated Successfully",type:"positive"}),this.$router.push({path:"/stock_entries"}))})).finally((()=>{this.$q.loading.hide()}))},sendtoaccounts(){this.$q.loading.show(),this.$axios.get("stock_entries/sendtoaccounts/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({message:"Sent to Accounts Successfully",type:"positive"}),this.$router.push({path:"/stock_entries"}))})).finally((()=>{this.$q.loading.hide()}))},revert(){this.$q.loading.show(),this.$axios.get("stock_entries/revert/"+this.$route.params.id).then((t=>{"success"===t.data.message&&(this.$q.notify({message:"Reverted Successfully",type:"info"}),this.$router.push({path:"/stock_entries"}))})).finally((()=>{this.$q.loading.hide()}))}}},n=l,c=a("2877"),d=a("9989"),u=a("f09f"),p=a("a370"),m=a("27f9"),h=a("eaac"),b=a("0016"),y=a("bd08"),_=a("db86"),g=a("9c40"),x=a("24e8"),q=a("4b7e"),f=a("eebe"),v=a.n(f),k=Object(c["a"])(n,s,i,!1,null,null,null);e["default"]=k.exports;v()(k,"components",{QPage:d["a"],QCard:u["a"],QCardSection:p["a"],QInput:m["a"],QTable:h["a"],QIcon:b["a"],QTr:y["a"],QTd:_["a"],QBtn:g["a"],QDialog:x["a"],QCardActions:q["a"]})}}]);