(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[82],{"803a":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",[a("page-toolbar",{attrs:{buttons:t.toolbarButtons}}),a("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),a("div",{class:t.$q.screen.gt.sm?"q-px-lg q-mt-md":"q-px-xs q-mt-sm"},[a("q-card",[a("q-card-section",[a("q-table",{attrs:{data:t.items,columns:t.columns},scopedSlots:t._u([{key:"body",fn:function(e){return[a("q-tr",{attrs:{props:e}},[a("q-td",{staticClass:"text-right"},[t._v(t._s(e.rowIndex+1))]),a("q-td",[t._v("\n                    "+t._s(e.row.product.name)+"\n                    "),a("q-btn",{attrs:{icon:"content_copy",flat:"",round:""},on:{click:function(a){return t.$store.dispatch("copyToClipboard",e.row.name)}}})],1),a("q-td",[a("router-link",{attrs:{to:"/sale_returns/view/"+e.row.sale_return.id}},[t._v(t._s(e.row.sale_return.serial_no))])],1),a("q-td",{staticClass:"text-right"},[e.row.gst?a("div",[t._v("\n                        "+t._s(e.row.gst)+"%\n                    ")]):t._e()]),a("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.qty)+"\n\n                ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.rate)+"\n                ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                  "+t._s(e.row.expiry_date)+"\n                ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.is_free?"Yes":"No")+"\n                ")]),t.rate_includes_gst?t._e():a("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.taxable)+"\n                ")]),t.rate_includes_gst?t._e():a("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.tax_amount)+"\n                ")]),a("q-td",{staticClass:"text-right"},[t._v("\n                    "+t._s(e.row.total)+"\n                ")])],1)]}}])})],1)],1)],1)],1)},r=[],l=(a("e6cf"),a("a79d"),a("a89c")),n=a("b6c6"),o={name:"SaleOrdersReturns",components:{PageToolbar:l["a"],Breadcrumbs:n["a"]},data(){return{toolBarButtons:[],serial_no:null,items:[],rate_includes_gst:null}},computed:{breadcrumbs(){const t=[{label:"Dashboard",link:"/"},{label:"Sale Orders",link:"/sale_orders"},{label:this.serial_no,link:"/sale_orders/view/"+this.$route.params.id},{label:"Returns"}];return t},columns(){const t=[{name:"sl_no",field:"sl_no",label:"#",sortable:!0},{name:"name",field:"name",label:"Product",sortable:!0,align:"left"},{name:"record",field:"record",label:"Record",sortable:!0,align:"left"},{name:"gst",field:"gst",label:"GST",sortable:!0},{name:"qty",field:"qty",label:"Qty",sortable:!0},{name:"rate",field:"rate",label:"Rate",sortable:!0},{name:"expiry_date",field:"expiry_date",label:"Exp Date",sortable:!0},{name:"is_free",field:"is_free",label:"Free ?",sortable:!0}];return this.rate_includes_gst||t.push({name:"taxable",field:"taxable",label:"Taxable",sortable:!0},{name:"tax_amount",field:"tax_amount",label:"Tax Amt",sortable:!0}),t.push({name:"total",field:"total",label:"Total",sortable:!0}),t}},mounted(){this.$q.loading.show(),this.$axios.get("sale_orders/returns/"+this.$route.params.id).then((t=>{this.items=t.data.items,this.serial_no=t.data.model.serial_no,this.rate_includes_gst=t.data.model.rate_includes_gst})).finally((()=>{this.$store.commit("setPageTitle","Sale Returns of "+this.serial_no),this.$q.loading.hide()}))}},i=o,d=a("2877"),_=a("9989"),c=a("f09f"),b=a("a370"),u=a("eaac"),m=a("bd08"),g=a("db86"),h=a("9c40"),f=a("eebe"),p=a.n(f),q=Object(d["a"])(i,s,r,!1,null,null,null);e["default"]=q.exports;p()(q,"components",{QPage:_["a"],QCard:c["a"],QCardSection:b["a"],QTable:u["a"],QTr:m["a"],QTd:g["a"],QBtn:h["a"]})}}]);