(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[65],{"9bca":function(t,s,a){"use strict";a.r(s);var e=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("q-page",[a("page-toolbar"),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("q-card",[a("q-card-section",[a("div",{staticClass:"row"},[a("div",{staticClass:"col"},[a("q-markup-table",{staticClass:"bg-blue-grey-10",attrs:{"wrap-cells":"",separator:"cell",bordered:"",square:"",dark:""}},[a("tbody",[a("tr",[a("td",[a("div",{staticClass:"text-subtitle1"},[t._v("Landing Price:"),a("span",{staticClass:"text-green-5"},[t._v(" "+t._s(t.landing))])])]),a("td",[a("div",{staticClass:"text-subtitle1"},[t._v("Cost Price: "),a("span",{staticClass:"text-blue-5"},[t._v(" "+t._s(t.cost))])])]),a("td",[a("div",{staticClass:"text-subtitle1"},[t._v("MRP: "),a("span",{staticClass:"text-red-5"},[t._v(" "+t._s(t.mrp))])])]),a("td",[a("div",{staticClass:"text-subtitle1"},[t._v("GST: "),a("span",{staticClass:"text-green-5"},[t._v(" "+t._s(t.gst))])])])]),a("tr",[a("td",[a("div",{staticClass:"text-subtitle1"},[t._v("GSP Customer "),a("span",{staticClass:"text-grey-6"},[t._v(" "+t._s(t.gsp_customer))])])]),a("td",[a("div",{staticClass:"text-subtitle1"},[t._v("GSP Dealer "),a("span",{staticClass:"text-grey-6"},[t._v(" "+t._s(t.gsp_dealer))])])]),a("td"),a("td")])])])],1)]),a("div",{staticClass:"row q-col-gutter-md q-mt-md"},[a("div",{staticClass:"col"},[a("q-input",{ref:"min_margin",attrs:{outlined:"",square:"",label:"Min Margin",rules:[function(t){return!!t&&!isNaN(t)||"Invalid"}],"lazy-rules":"ondemand"},model:{value:t.min_margin,callback:function(s){t.min_margin=t._n(s)},expression:"min_margin"}})],1),a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",square:"",readonly:"",value:Math.ceil(t.cost*(1+t.min_margin/100)),label:"Minimum Selling Price"}})],1),a("div",{staticClass:"col"},[a("q-btn",{attrs:{label:"Save",color:"blue-10"},on:{click:t.saveMinMargin}})],1)]),a("div",{staticClass:"row q-mt-md"},[a("div",{staticClass:"col-12"},[a("div",{staticClass:"text-h6"},[t._v("Pricelists")]),t._l(t.pricelists,(function(s,e){return a("div",{key:e,staticClass:"row q-col-gutter-md"},[a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",square:"",label:s.name,readonly:""}})],1),a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",square:"",suffix:"%",label:"Margin(%)"},model:{value:t.pricelists[e].margin,callback:function(s){t.$set(t.pricelists[e],"margin",t._n(s))},expression:"pricelists[i].margin"}})],1),a("div",{staticClass:"col"},[a("q-input",{attrs:{outlined:"",square:"",readonly:"",value:Math.ceil(t.cost*(1+t.pricelists[e].margin/100)),label:"Selling Price"}})],1)])}))],2),a("div",{staticClass:"col-12 q-mt-md"},[a("q-btn",{staticClass:"q-mr-md ",attrs:{label:"Save Pricelist",color:"primary"},on:{click:t.savePricelist}}),a("q-btn",{attrs:{label:"load purchase data",color:"teal-10",depressed:""},on:{click:t.loadPurchases}})],1)]),t.purchases.length>0?a("div",{staticClass:"row q-mt-md"},[a("div",{staticClass:"col"},[a("q-table",{attrs:{columns:t.columns,data:t.purchases,title:"Purchases of "+t.name,"row-key":"bill_number"},scopedSlots:t._u([{key:"body",fn:function(s){return[a("q-tr",{attrs:{props:s}},[a("q-td",{staticClass:"text-left"},[t._v("\n                                    "+t._s(s.rowIndex+1)+"\n                                ")]),a("q-td",{staticClass:"text-left"},[t._v("\n                                    "+t._s(s.row.purchase.bill_number)+"\n                                ")]),a("q-td",{staticClass:"text-left"},[t._v("\n                                    "+t._s(s.row.purchase.status)+"\n                                ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                                    "+t._s(s.row.purchase.bill_date)+"\n                                ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                                    "+t._s(s.row.qty)+"\n                                ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                                    "+t._s(s.row.rate)+"\n                                ")]),a("q-td",{staticClass:"text-center"},[a("q-btn",{attrs:{flat:"",color:"blue-10",to:"/purchases/view/"+s.row.purchase_id,label:"view bill"}})],1)],1)]}}],null,!1,2173550765)})],1)]):t._e()])],1)],1)],1)},i=[],l=(a("e6cf"),a("a79d"),a("ddb0"),a("a89c")),r=a("b6c6"),n={name:"ProductsCost",components:{PageToolbar:l["a"],Breadcrumbs:r["a"]},mounted(){this.$store.getters.hasPermissionTo("view_product_pricing")?(this.$q.loading.show(),Promise.all([this.$axios.get("/products/cost/"+this.$route.params.id).then((t=>{this.landing=t.data.landing?t.data.landing:0,this.cost=t.data.cost?t.data.cost:0})),this.$axios.get("/products/"+this.$route.params.id).then((t=>{this.mrp=t.data.mrp,this.gst=t.data.gst,this.gsp_customer=t.data.gsp_customer,this.gsp_dealer=t.data.gsp_dealer,this.name=t.data.name,this.min_margin=t.data.min_margin})),this.$axios.get("pricelists").then((t=>{const s=t.data.model;s.forEach(((t,a)=>{s[a].margin=0})),this.$axios.get("products/pricelists/"+this.$route.params.id).then((t=>{t.data&&t.data.forEach((t=>{s.forEach(((a,e)=>{a.id===t.pricelist_id&&(s[e].margin=t.margin)}))})),this.pricelists=s}))}))]).finally((()=>{this.$store.commit("setPageTitle","Cost & Pricing: "+this.name),this.$q.loading.hide()}))):this.$router.push({name:"Error403"})},computed:{breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Products",link:"/products"},{label:this.name,link:"/products/view/"+this.$route.params.id},{label:"Cost"}];return t}},data(){return{landing:0,cost:0,name:null,mrp:null,gst:null,gsp_customer:null,gsp_dealer:null,columns:[{name:"sl_no",label:"#",field:"sl_no",align:"left"},{name:"bill_number",label:"Bill No.",field:"bill_number",align:"left"},{name:"status",label:"Status",field:"status",align:"left"},{name:"bill_date",label:"Bill Date",field:"bill_date",align:"right"},{name:"qty",label:"Qty",field:"qty",align:"right"},{name:"rate",label:"Rate",field:"rate",align:"right"},{name:"action",label:"Action",field:"action",align:"center"}],purchases:[],pricelists:[],min_margin:0}},methods:{loadPurchases(){this.$axios.get("products/purchases/"+this.$route.params.id).then((t=>{this.purchases=t.data}))},saveMinMargin(){this.$refs.min_margin.validate()&&this.$axios.post("products/min_margin/"+this.$route.params.id,{min_margin:this.min_margin}).then((t=>{"success"===t.data.message&&this.$q.notify({message:"Minimum Margin saved successfully",type:"positive"})}))},savePricelist(){const t={pricelists:this.pricelists};this.$axios.post("products/pricelists/"+this.$route.params.id,t).then((t=>{"success"===t.data.message&&this.$q.notify({message:"Pricelist saved successfully",type:"positive"})}))}}},c=n,o=a("2877"),d=a("9989"),u=a("f09f"),m=a("a370"),p=a("2bb1"),g=a("27f9"),b=a("9c40"),h=a("eaac"),_=a("bd08"),v=a("db86"),q=a("eebe"),C=a.n(q),f=Object(o["a"])(c,e,i,!1,null,null,null);s["default"]=f.exports;C()(f,"components",{QPage:d["a"],QCard:u["a"],QCardSection:m["a"],QMarkupTable:p["a"],QInput:g["a"],QBtn:b["a"],QTable:h["a"],QTr:_["a"],QTd:v["a"]})}}]);