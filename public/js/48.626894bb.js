(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[48],{cb8d:function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",[s("page-toolbar",{attrs:{"page-title":"Phones",buttons:t.toolbarButtons}}),s("breadcrumbs",{attrs:{breadcrumbs:t.breadcrumbs}}),s("q-card",[s("q-card-section",[s("div",{staticClass:"row"},[s("div",{staticClass:"col-6"},[s("q-markup-table",[s("thead",[s("th"),s("th")]),s("tbody",[s("tr",[s("td",[t._v("Country Code")]),s("td",[t._v(t._s(t.country_code))])]),s("tr",[s("td",[t._v("Phone")]),s("td",[t._v(t._s(t.phone))])]),s("tr",[s("td",[t._v("Source")]),s("td",[t._v(t._s(t.source))])]),s("tr",[s("td",[t._v("Tags")]),s("td",[t._v(t._s(t.tags.toString()))])])])])],1)])]),s("q-card-section",[s("div",{staticClass:"row"},[s("div",{staticClass:"col"},[s("div",{staticClass:"text-subtitle2 q-mb-md"},[t._v("Related To")]),s("q-markup-table",[s("thead",[s("th",{staticClass:"text-left"},[t._v("Addressable Type")]),s("th",{staticClass:"text-left"},[t._v("Name")]),s("th",{staticClass:"text-left"},[t._v("Address")]),s("th",{staticClass:"text-left"},[t._v("Type")])]),s("tbody",t._l(t.addresses,(function(e,a){return s("tr",{key:a},[s("td",[t._v(t._s(e.addressable_type.substr(4)))]),s("td",[s("router-link",{attrs:{to:e.addressable.is_lead?"/leads/view/"+e.addressable.id+"/"+t.phone:"/customers/view/"+e.addressable.id+"/"+t.phone}},[t._v(t._s(e.addressable.name))])],1),s("td",[t._v(t._s(t.getAddress(e)))]),s("td",[t._v(t._s(e.tag_name))])])})),0)])],1)])])],1)],1)},d=[],r=(s("e6cf"),s("a79d"),s("a89c")),o=s("b6c6"),n={name:"PhonesView",components:{PageToolbar:r["a"],Breadcrumbs:o["a"]},data(){return{country_code:null,phone:null,source:null,tags:[],addresses:[]}},computed:{breadcrumbs(){return[{label:"Dashboard",link:"/"},{label:"Phones",link:"/phones"},{label:this.phone,link:""}]},toolbarButtons(){const t=[];return t}},mounted(){this.$q.loading.show(),this.$axios.get("phones/"+this.$route.params.id).then((t=>{this.country_code=t.data.country_code,this.phone=t.data.content,this.source=t.data.source,this.tags=t.data.tags.map((t=>t.content)),this.addresses=t.data.addresses})).finally((()=>{this.$q.loading.hide(),this.$store.commit("setPageTitle","Phones: "+this.phone)}))},methods:{getAddress(t){let e="";return e+=t.line_1+", ",t.line_2&&(e+=t.line_2+", "),t.pin&&(e+=t.pin+", "),t.district&&(e+=t.district+", "),e+=t.state+", ",e+=t.country+", ",t.phones.forEach((t=>{e+="("+t.country_code+")"+t.content+", "})),e=e.substr(0,e.length-2),e}}},l=n,i=s("2877"),c=s("9989"),u=s("f09f"),h=s("a370"),b=s("2bb1"),_=s("eebe"),p=s.n(_),v=Object(i["a"])(l,a,d,!1,null,null,null);e["default"]=v.exports;p()(v,"components",{QPage:c["a"],QCard:u["a"],QCardSection:h["a"],QMarkupTable:b["a"]})}}]);